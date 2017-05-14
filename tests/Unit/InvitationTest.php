<?php

namespace Tests\Unit\Relationship;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class InvitationTest extends TestCase {
    use DatabaseTransactions;

    /** @test */
    public function user_can_invite_another_user () {
        [$emma, $leon] = factory(User::class, 2)->create();

        $leon->invite($emma);

        $this->assertTrue($leon->hasSentRequestTo($emma));
        $this->assertTrue($emma->hasReceivedRequestFrom($leon));
        $this->assertTrue($emma->hasPendingRelation($leon));
        $this->assertTrue($leon->hasPendingRelation($emma));
    }

    /** @test */
    public function sender_can_cancel_his_invitation () {
        [$emma, $leon] = factory(User::class, 2)->create();

        $leon->invite($emma);
        $leon->reject($emma);

        $this->assertFalse($leon->hasSentRequestTo($emma));
    }

    /** @test */
    public function recipient_can_reject_invitation () {
        [$emma, $leon] = factory(User::class, 2)->create();

        $leon->invite($emma);
        $emma->reject($leon);

        $this->assertFalse($leon->hasSentRequestTo($emma));
    }

    /** @test */
    public function user_can_not_invite_himself () {
        $leon = factory(User::class)->create();

        $leon->invite($leon);

        $this->assertCount(0, $leon->invitedUsers);
    }

    /** @test */
    public function user_can_not_invite_existing_friend () {
        [$emma, $leon] = factory(User::class, 2)->create();

        $leon->invite($emma);
        $emma->befriend($leon);
        $leon->invite($emma);

        $this->assertCount(0, $leon->invitedUsers);
    }

    /** @test */
    public function user_can_not_invite_of_another_user_twice () {
        [$emma, $leon] = factory(User::class, 2)->create();

        $leon->invite($emma);
        $leon->invite($emma);

        $this->assertCount(1, $emma->inviters);
        $this->assertTrue($leon->hasSentRequestTo($emma));
    }

    /** @test */
    public function it_can_count_number_of_inviters () {
        [$emma, $leon, $john] = factory(User::class, 3)->create();

        $leon->invite($emma);
        $john->invite($emma);

        $this->assertEquals(2, $emma->selectInvitersCount());
        $this->assertEquals(0, $leon->selectInvitersCount());
        $this->assertEquals(0, $john->selectInvitersCount());
    }

    /** @test */
    public function it_can_count_number_of_invited_users () {
        [$leon, $emma] = factory(User::class, 2)->create();

        $leon->invite($emma);
        $emma->invite($leon);

        $this->assertEquals(1, $leon->selectInvitedUsersCount());
        $this->assertEquals(0, $emma->selectInvitedUsersCount());
    }

    /** @test */
    public function it_can_count_number_of_all_invitation_partners () {
        [$leon, $emma, $john] = factory(User::class, 3)->create();

        $leon->invite($emma);
        $emma->invite($leon);
        $emma->invite($john);

        $this->assertEquals(1, $leon->selectPendingPartnersCount());
        $this->assertEquals(2, $emma->selectPendingPartnersCount());
        $this->assertEquals(1, $john->selectPendingPartnersCount());
    }

    /** @test */
    public function invited_users_contain_user_that_has_received_a_friend_request () {
        [$leon, $emma] = factory(User::class, 2)->create();

        $leon->invite($emma);

        $this->assertTrue($leon->invitedUsers->contains($emma));
    }

    /** @test */
    public function inviters_contain_user_that_sent_a_friend_requests () {
        [$leon, $emma] = factory(User::class, 2)->create();

        $leon->invite($emma);

        $this->assertTrue($emma->inviters->contains($leon));
    }

    /** @test */
    public function invitation_partners_contain_both_inviters_and_invited_users () {
        [$leon, $emma, $john] = factory(User::class, 3)->create();

        $leon->invite($emma);
        $emma->invite($john);

        $this->assertCount(2, $partners = $emma->invitationPartners()->get());
        $this->assertTrue($partners->contains($leon));
        $this->assertTrue($partners->contains($john));
    }
}
