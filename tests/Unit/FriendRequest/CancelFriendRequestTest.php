<?php

namespace Tests\Unit\FriendRequest;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CancelFriendRequestTest extends TestCase {
    use DatabaseTransactions;

    /** @test */
    public function user_can_cancel_his_own_friend_request_to_another_user () {
        [$john, $bobby] = factory(User::class, 2)->create();

        $john->sendFriendRequestTo($bobby);
        $john->cancelFriendRequestTo($bobby);

        $this->assertFalse($john->hasSentFriendRequestTo($bobby));
    }
}
