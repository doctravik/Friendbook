<?php

namespace Tests\Unit\User;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase {
    use DatabaseTransactions;

    /** @test */
    public function it_can_check_if_user_is_the_same_person_as_the_given_one () {
        [ $emma, $leon ] = factory(User::class, 2)->create();

        $this->assertTrue($emma->is($emma));
        $this->assertFalse($emma->is($leon));
    }

    /** @test */
    public function it_can_get_full_name_of_the_user () {
        $user = factory(User::class)->create([
            'first_name' => 'John',
            'last_name' => 'Doe'
        ]);

        $this->assertEquals('John Doe', $user->getFullName());
    }
}
