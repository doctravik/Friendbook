<?php

namespace App\Relationship;

use App\User;

interface RelationInterface {
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function senders();

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function recipients();

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function partners();

    /**
     * @param User $user
     * @return void
     */
    public function create(User $user);

    /**
     * @param User $user
     * @return void
     */
    public function remove(User $user);

    /**
     * @param User $user
     * @return boolean
     */
    public function hasSender(User $user);

    /**
     * @param User $user
     * @return boolean
     */
    public function hasRecepient(User $user); 

    /**
     * @param User $user
     * @return boolean
     */
    public function hasRelationsWith(User $user);

    public function selectSendersCount();
    public function selectRecipientsCount();
    public function selectPartnersCount();
}