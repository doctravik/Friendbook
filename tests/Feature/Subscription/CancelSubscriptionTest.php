<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CancelSubscriptionTest extends TestCase {
    use DatabaseTransactions;

    /** @test */
    public function unauthenticated_user_cannot_cancel_own_subscription () {
        [$charles, $emma] = factory(User::class, 2)->create();
        
        $response = $this->json('delete', '/unfollow/{$emma->id}');

        $response->assertStatus(401);
    }

    /** @test */
    public function authenticated_user_can_cancel_own_subscription () {
        [$charles, $emma] = factory(User::class, 2)->create();
        $charles->follow($emma);

        $response = $this->actingAs($charles)->json('delete', "/unfollow/{$emma->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('followers', [
            'follower_id' => $charles->id,
            'followed_id' => $emma->id
        ]);
    }
}
