<?php

namespace Tests\Unit\FriendRequest;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SendFriendRequestTest extends TestCase {
    use DatabaseTransactions;

    /** @test */
    public function user_can_send_friend_request () {
        [$john, $bobby] = factory(User::class, 2)->create();

        $john->sendFriendRequestTo($bobby);

        $this->assertTrue($john->hasSentFriendRequestTo($bobby));
    }

    /** @test */
    public function user_cannot_send_friend_request_to_his_friend_with_pending_status () {
        [$john, $bobby] = factory(User::class, 2)->create();

        $john->sendFriendRequestTo($bobby);
        $john->sendFriendRequestTo($bobby);
        $bobby->sendFriendRequestTo($john);

        $this->assertCount(1, $john->friendRequestsSent);
        $this->assertCount(0, $john->friendRequestsReceived);
    }

    /** @test */
    public function user_cannot_send_friend_request_to_his_friend_with_accepted_status () {
        [$john, $bobby] = factory(User::class, 2)->create();

        $john->sendFriendRequestTo($bobby);
        $bobby->acceptFriendRequest($john);
        $john->sendFriendRequestTo($bobby);

        $this->assertCount(0, $john->friendRequestsSent);
    }

    /** @test */
    public function user_cannot_send_friend_request_to_himself () {
        $user = factory(User::class)->create();

        $user->sendFriendRequestTo($user);

        $this->assertFalse($user->canBeFriendOf($user));
        $this->assertFalse($user->hasSentFriendRequestTo($user));
    }
}
