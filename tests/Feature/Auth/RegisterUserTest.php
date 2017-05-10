<?php

namespace Tests\Feature\Auth;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegisterUserTest extends TestCase {
    use DatabaseTransactions;

    /** @test */
    public function it_can_register_new_user () {
        $response = $this->post('/register', $this->fillDummyCredentials());

        $response->assertRedirect('/home');
        $this->assertTrue(auth()->check());
        $this->assertDatabaseHas('users', ['email' => 'johndoe@example.com']);
    }

    /** @test */
    public function user_cannot_be_registered_without_first_name () {
        $response = $this->post('/register', $this->fillDummyCredentials([
            'first_name' => ''
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors('first_name');
        $this->assertCount(0, User::all());
    }

    /** @test */
    public function user_cannot_be_registered_without_last_name () {
        $response = $this->post('/register', $this->fillDummyCredentials([
            'last_name' => ''
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors('last_name');
        $this->assertCount(0, User::all());
    }

    /** @test */
    public function user_cannot_be_registered_without_password () {
        $response = $this->post('/register', $this->fillDummyCredentials([
            'password' => ''
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors('password');
        $this->assertCount(0, User::all());
    }

    /**
     * @param array $attributes
     * @return array
     */
    private function fillDummyCredentials ($attributes = []) {
        return [
            'first_name' => $attributes['first_name'] ?? 'John',
            'last_name' => $attributes['last_name'] ?? 'Doe',
            'email' => $attributes['email'] ?? 'johndoe@example.com',
            'password' => $attributes['password'] ?? 'secret',
            'password_confirmation' => $attributes['password_confirmation'] ?? 'secret'
        ];
    } 
}
