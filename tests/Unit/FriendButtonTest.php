<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use App\Relationship\FriendButton;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FriendButtonTest extends TestCase {
    use DatabaseTransactions;

    /** @test */
    public function button_has_not_friend_state_if_users_are_not_friends () {
        [$emma, $leon] = factory(User::class, 2)->create();

        $this->assertEquals(FriendButton::NOT_FRIEND_STATE, $emma->getFriendButtonStateFor($leon));
        $this->assertEquals(FriendButton::NOT_FRIEND_STATE, $leon->getFriendButtonStateFor($emma));
    }

    /** @test */
    public function button_has_friend_state_if_users_are_friends () {
        [$emma, $leon] = factory(User::class, 2)->create();

        $emma->invite($leon);
        $leon->beFriend($emma);

        $this->assertEquals(FriendButton::FRIEND_STATE, $emma->getFriendButtonStateFor($leon));
        $this->assertEquals(FriendButton::FRIEND_STATE, $leon->getFriendButtonStateFor($emma));
    }

    /** @test */
    public function button_has_request_state_for_invitation_partners () {
        [$emma, $leon] = factory(User::class, 2)->create();

        $leon->invite($emma);

        $this->assertEquals(FriendButton::REQUEST_SENT_STATE, $leon->getFriendButtonStateFor($emma));
        $this->assertEquals(FriendButton::REQUEST_RECEIVED_STATE, $emma->getFriendButtonStateFor($leon));        
    }
}
