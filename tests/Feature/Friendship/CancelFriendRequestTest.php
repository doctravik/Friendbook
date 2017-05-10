<?php

namespace Tests\Feature\Friendship;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CancelFriendRequestTest extends TestCase {
    use DatabaseTransactions;

    /** @test */
    public function unauthenticated_user_cannot_cancel_friend_request () {
        [$john, $bobby] = factory(User::class, 2)->create();
        $john->sendFriendRequestTo($bobby);

        $response = $this->json('delete', "/friends/requests/{$bobby->id}");

        $response->assertStatus(401);
        $this->assertTrue($john->hasSentFriendRequestTo($bobby));
    }

    /** @test */
    public function authenticated_user_can_cancel_friend_request_sent_to_another_user () {
        [$john, $bobby] = factory(User::class, 2)->create();
        $john->sendFriendRequestTo($bobby);

        $response = $this->actingAs($john)->json('delete', "/friends/requests/{$bobby->id}");

        $response->assertStatus(200);
        $this->assertFalse($john->hasSentFriendRequestTo($bobby));
    }

    /** @test */
    public function user_is_still_follower_after_cancel_request () {
        [$john, $bobby] = factory(User::class, 2)->create();
        $john->sendFriendRequestTo($bobby);
        $john->follow($bobby);

        $response = $this->actingAs($john)->json('delete', "/friends/requests/{$bobby->id}");

        $response->assertStatus(200);
        $john->isFollowerOf($bobby);        
    }
}
