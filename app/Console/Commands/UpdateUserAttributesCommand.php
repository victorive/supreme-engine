<?php

namespace App\Console\Commands;

use Exception;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateUserAttributesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:user-attributes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'An artisan command that updates user\'s first_name, last_name and timezone to new random ones';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('User attributes update starting...');

        try {
            User::query()->lazy()
                ->each(function ($user): void {
                    $userData = [
                        'first_name' => fake()->firstName(),
                        'last_name' => fake()->lastName(),
                        'timezone' => collect(['CET', 'CST', 'GMT+1'])->random(),
                    ];

                    $user->update($userData);
                });
        } catch (Exception $exception) {
            Log::error($exception);
            $this->error('Error during user attributes update: ' . $exception->getMessage());
        }

        $this->info('User attributes update completed successfully.');
    }
}
