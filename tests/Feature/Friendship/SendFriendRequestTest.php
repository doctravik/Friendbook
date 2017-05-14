<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SendFriendRequestTest extends TestCase {
    use DatabaseTransactions;

    /** @test */
    public function unauthenticated_user_cannot_send_friend_request () {
        [$john, $bobby] = factory(User::class, 2)->create();

        $response = $this->json('post', "/friends/requests/{$bobby->id}");

        $response->assertStatus(401);
    }

    /** @test */
    public function authenticated_user_can_send_friend_request () {
        [$john, $bobby] = factory(User::class, 2)->create();

        $response = $this->actingAs($john)->json('post', "/friends/requests/{$bobby->id}");

        $response->assertStatus(200);
        $this->assertTrue($john->hasSentRequestTo($bobby));
    }

    /** @test */
    public function user_becomes_a_follower_when_send_a_friend_request () {
        [$john, $bobby] = factory(User::class, 2)->create();

        $response = $this->actingAs($john)->json('post', "/friends/requests/{$bobby->id}");

        $response->assertStatus(200);
        $this->assertTrue($john->hasSentRequestTo($bobby));
        $this->assertTrue($john->isFollowerOf($bobby));
    }
}
