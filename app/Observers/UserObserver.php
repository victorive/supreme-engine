<?php

namespace App\Observers;

use App\Models\User;
use App\Models\UpdatedUserAttributes;
use Illuminate\Support\Arr;

class UserObserver
{
    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        UpdatedUserAttributes::create([
            'user_id' => $user->id,
            'attributes' => array_merge(Arr::except($user->getDirty(), ['updated_at']), ['email' => $user->email])
        ]);
    }
}
