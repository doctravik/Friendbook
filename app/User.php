<?php

namespace App;

use App\Friendship\FriendshipTrait;
use App\Friendship\FriendRequestTrait;
use App\Subscription\SubscriptionTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {
    use Notifiable, SubscriptionTrait, FriendshipTrait, FriendRequestTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @type array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'slug', 'full_name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @type array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];


    /**
     * @return string
     */
    public function getFirstName () {
        return $this->first_name;
    }

    /**
     * @return string
     */
    public function getLastName () {
        return $this->last_name;
    }

    /**
     * @return string
     */
    public function getFullName () {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Scope a query to find a user with the given slug.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @param  string $slug
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFindBySlug ($builder, $slug) {
        return $builder->where('slug', $slug);
    }
}
