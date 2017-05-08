<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Friendship extends Model {
    /**
     * @type string
     */
    protected $table = 'friends';

    /**
     * Scope only senders with the given id.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $senderId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereSender ($query, $senderId) {
        return $query->where('requester_id', $senderId);
    }

    /**
     * Scope only recipients with the given id.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $recipientId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereRecipient ($query, $recipientId) {
        return $query->where('requested_id', $recipientId);
    }

    /**
     * Scope friendships between two users.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param User $sender
     * @param User $recipient
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBetween ($query, $sender, $recipient) {
        return $query->where(function ($query) use ($sender, $recipient) {
            $query->where(function ($query) use ($sender, $recipient) {
                $query->whereSender($recipient->id)->whereRecipient($sender->id);
            })
            ->orWhere(function ($query) use ($sender, $recipient) {
                $query->whereSender($sender->id)->whereRecipient($recipient->id);
            });
        });
    }

    /**
     * Scope friendships of the given user.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOf ($query, User $user) {
        return $query->where(function ($query) use ($user) {
            $query->where('requester_id', $user->id)->orWhere('requested_id', $user->id);
        });   
    }
}
