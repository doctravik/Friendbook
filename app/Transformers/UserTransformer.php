<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract {
    /**
     * List of resources possible to include.
     *
     * @var array
     */
    protected $availableIncludes = [
        'followers', 
        'followedUsers', 
        'friendRequestsSent',
        'friendRequestsReceived'
    ];

    /**
     * Turn this User object into a generic array.
     *
     * @return array
     */
    public function transform (User $user) {
        return [
            'id' => (int) $user->id,
            'name' => $user->name
        ];
    }

    /**
     * @return League\Fractal\Resource\Collection
     */
    public function includeFollowers (User $user) {
        return $this->collection($user->followers, new UserTransformer);
    }

    /**
     * @return League\Fractal\Resource\Collection
     */
    public function includeFollowedUsers (User $user) {
        return $this->collection($user->followedUsers, new UserTransformer);
    }

    /**
     * @return League\Fractal\Resource\Collection
     */
    public function includeFriendRequestsSent (User $user) {
        return $this->collection($user->friendRequestsReceived, new UserTransformer);
    }

    /**
     * @return League\Fractal\Resource\Collection
     */
    public function includeFriendRequestsReceived (User $user) {
        return $this->collection($user->friendRequestsReceived, new UserTransformer);
    }
}