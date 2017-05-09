<?php

namespace App\Observers;

use App\User;
use App\Service\Slug;

class UserObserver {
    /**
     * Observe user created event.
     * 
     * @param User $user
     * @return void
     */
    public function creating(User $user) {
        $user->slug = Slug::for($user)->generate($user->getFullName());
    }

    /**
     * Observe user updating event.
     * 
     * @param User $user
     * @return void
     */
    public function updating (User $user) {
        $user->slug = Slug::for($user)->generate($user->getFullName());
    }
}