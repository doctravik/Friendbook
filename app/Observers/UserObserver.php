<?php

namespace App\Observers;

use App\User;

class UserObserver {
    /**
     * Observe user created event.
     * 
     * @param User $user
     * @return void
     */
    public function created (User $user) {
        $slug = str_slug($user->name) . '-' . $user->id;

        if (User::findBySlug($slug)->first() !== null)
            throw new \Exception('Can not create a unique slug');
            
        $user->update([ 'slug' => $slug ]);
    }
}