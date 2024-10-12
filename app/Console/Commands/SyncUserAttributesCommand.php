<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Response;
use App\Models\UpdatedUserAttributes;
use App\Services\ThirdPartySyncService;

class SyncUserAttributesCommand extends Command
{
    public function __construct(private ThirdPartySyncService $thirdPartySyncService)
    {
        parent::__construct();
    }

    public const CHUNK_SIZE = 1000;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:user-attributes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync user attributes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('User attributes sync starting...');

        $batch = [];

        try {
            UpdatedUserAttributes::query()->where('status', 'pending')->lazy()
                ->each(function ($userAttributes) use (&$batch): void {
                    $batch[] = $userAttributes->attributes;

                    if (count($batch) >= self::CHUNK_SIZE) {
                        $this->dispatchBatchRequest($batch);
                        $batch = [];
                    }
                });

            if (!empty($batch)) {
                $this->dispatchBatchRequest($batch);
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            $this->error('Error during user attributes sync: ' . $exception->getMessage());
        }

        $this->info('User attributes sync completed successfully.');
    }

    private function dispatchBatchRequest(array $batch): void
    {
        $this->checkRateLimits($batch);

        $payload = $this->createPayload($batch);

        $response = $this->syncUserAttributes($payload);

        if ($response === true) {
            $this->updateUserAttributes($batch);
        }
    }

    private function checkRateLimits(array &$batch): void
    {
        foreach ($batch as $key => $userAttributes) {
            if ($this->thirdPartySyncService->getIndividualRequestCount($userAttributes['email']) >= 3600) {
                Log::warning("Individual request rate limit exceeded for user {$userAttributes['email']}");

                unset($batch[$key]);

                continue;
            }
        }
    }

    private function createPayload(array $batch): array
    {
        return [
            'batches' => [
                [
                    'subscribers' => $batch,
                ],
            ],
        ];
    }

    private function syncUserAttributes(array $payload): Response|bool
    {
        // Make the API call
        return true;
    }

    private function updateUserAttributes(array $batch): void
    {
        foreach ($batch as $key => $userAttributes) {
            $this->thirdPartySyncService->incrementIndividualRequest($userAttributes['email']);

            UpdatedUserAttributes::whereJsonContains('attributes->email', $userAttributes['email'])
                ->where('status', 'pending')
                ->update(['status' => 'successful']);

            Log::info(sprintf(
                "[%d] email: %s, name: %s, timezone: %s",
                $key,
                $userAttributes['email'],
                $userAttributes['first_name'] . ' ' .  $userAttributes['last_name'],
                $userAttributes['timezone'] ?? ''
            ));
        }
    }
}
