<?php

namespace Tests\Feature\Auth;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginUserTest extends TestCase {
    Use DatabaseTransactions;

    /** @test */
    public function user_can_login_with_valid_credentials()
    {
        factory(User::class)->create([
            'first_name' => 'John',
            'email' => 'john@example.com',
            'password' => bcrypt('secret')
        ]);

        $response = $this->post('/login', [
            'email' => 'john@example.com',
            'password' => 'secret',
        ]);

        $response->assertRedirect('/home');
        $this->assertTrue(auth()->check());
    }


    /** @test */
    public function user_can_login_with_invalid_credentials()
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'john@example.com',
            'password' => 'secret',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function user_can_logout()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertFalse(auth()->check());
    }
}
