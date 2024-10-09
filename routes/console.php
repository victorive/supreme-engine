<?php

use App\Models\UpdatedUserAttributes;
use Illuminate\Support\Facades\Schedule;
use App\Services\ThirdPartySyncService;

Schedule::command('sync:user-attributes')->everyMinute()->when(function () {
    return (new ThirdPartySyncService)->getBatchRequestCount() < 50 && UpdatedUserAttributes::where('status', 'pending')->exists();
});

Schedule::command('reset:api-request-counter')->hourly();
