<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RejectFriendRequestTest extends TestCase {
    use DatabaseTransactions;

    /** @test */
    public function unauthenticated_user_cannot_reject_friend_request_from_another_user() {
        [$john, $bobby] = factory(User::class, 2)->create();
        $john->sendFriendRequestTo($bobby);

        $response = $this->json('delete', '/friends/requests/{$john->id}');

        $response->assertStatus(401);
        $this->assertTrue($john->hasSentFriendRequestTo($bobby));
    }

    /** @test */
    public function authenticated_user_can_reject_friend_request_from_another_user () {
        [$john, $bobby] = factory(User::class, 2)->create();
        $john->sendFriendRequestTo($bobby);

        $response = $this->actingAs($bobby)->json('delete', "/friends/requests/{$john->id}");

        $response->assertStatus(200);
        $this->assertFalse($john->hasSentFriendRequestTo($bobby));
    }
}
