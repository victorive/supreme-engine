<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class ThirdPartySyncService
{
    private const BATCH_REQUEST_KEY = 'api_batch_requests';
    private const INDIVIDUAL_REQUEST_KEY_PREFIX = 'api_individual_requests_';

    public function incrementBatchRequest(): void
    {
        Cache::add(self::BATCH_REQUEST_KEY, 0, 3600);
        Cache::increment(self::BATCH_REQUEST_KEY);
    }

    public function incrementIndividualRequest(string $email): void
    {
        $individualRequestKey = self::INDIVIDUAL_REQUEST_KEY_PREFIX . $email;
        Cache::add($individualRequestKey, 0, 3600);
        Cache::increment($individualRequestKey);
    }

    public function getBatchRequestCount(): int
    {
        return Cache::get(self::BATCH_REQUEST_KEY) ?? 0;
    }

    public function getIndividualRequestCount(string $email): int
    {
        $individualRequestKey = self::INDIVIDUAL_REQUEST_KEY_PREFIX . $email;
        return Cache::get($individualRequestKey) ?? 0;
    }

    public static function resetCounts(): void
    {
        Cache::flush();
    }
}
