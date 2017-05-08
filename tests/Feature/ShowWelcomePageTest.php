<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ShowWelcomePageTest extends TestCase {
    use DatabaseTransactions;

    /** @test */
    public function guest_can_see_welcome_page () {
        $response = $this->get('/');

        $response->assertSee('Welcome to Fakebook');
    }

    /** @test */
    public function authenticated_user_redirected_to_home_page () {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertRedirect('/home');
        $response->assertDontSee('Welcome to Fakebook');
    }
}
