<?php

namespace Tests\Feature;

use App\User;
use App\Friendship;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AcceptFriendRequestTest extends TestCase {
    use DatabaseTransactions;

    /** @test */
    public function unauthenticated_user_cannot_accept_friend_request () {
        [$john, $bobby] = factory(User::class, 2)->create();
        $john->invite($bobby);

        $response = $this->json('post', '/friends/{$john->id}');

        $response->assertStatus(401);
        $this->assertFalse($john->isFriendWith($bobby));
    }

    /** @test */
    public function authenticated_user_can_accept_friend_request () {
        [$john, $bobby] = factory(User::class, 2)->create();
        $john->invite($bobby);

        $response = $this->actingAs($bobby)->json('post', "/friends/{$john->id}");

        $response->assertStatus(200);
        $this->assertTrue($john->isFriendWith($bobby));
    }
}
