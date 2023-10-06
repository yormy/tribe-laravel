<?php

namespace Yormy\TribeLaravel\Tests\Unit;

use Illuminate\Support\Facades\Event;
use Yormy\TribeLaravel\Models\Project;
use Yormy\TribeLaravel\Models\TribeRole;
use Yormy\TribeLaravel\Observers\Events\TribeMembershipAcceptedEvent;
use Yormy\TribeLaravel\Observers\Events\TribeMembershipDeniedEvent;
use Yormy\TribeLaravel\Observers\Events\TribeMembershipInvitedEvent;
use Yormy\TribeLaravel\Observers\Events\TribeMembershipLeftEvent;
use Yormy\TribeLaravel\Repositories\ProjectRepository;
use Yormy\TribeLaravel\Tests\TestCase;
use Yormy\TribeLaravel\Tests\Traits\MemberTrait;
use Yormy\TribeLaravel\Tests\Unit\Traits\AssertInviteTrait;
use Yormy\TribeLaravel\Tests\Unit\Traits\AssertRuleTrait;

class EventsTest extends TestCase
{
    use AssertInviteTrait;
    use AssertRuleTrait;
    use MemberTrait;

    /**
     * @test
     *
     * @group tribe-events
     */
    public function TribeMembership_Invite_Event(): void
    {
        $member = $this->createMember();
        $project = Project::factory()->create();
        $role = TribeRole::factory()->project($project)->create();

        Event::fake([
            TribeMembershipInvitedEvent::class,
        ]);

        $projectRepository = new ProjectRepository();
        $this->actingAs($member);
        $projectRepository->inviteMember($project, $member, $role);

        Event::assertDispatched(TribeMembershipInvitedEvent::class);
    }

    /**
     * @test
     *
     * @group tribe-events
     */
    public function TribeMembership_InviteAccepted_Event(): void
    {
        $member = $this->createMember();
        $project = Project::factory()->create();
        $role = TribeRole::factory()->project($project)->create();

        Event::fake([
            TribeMembershipAcceptedEvent::class,
        ]);

        $this->InviteAndAccept($project, $member, $role);

        Event::assertDispatched(TribeMembershipAcceptedEvent::class);
    }

    /**
     * @test
     *
     * @group tribe-events
     */
    public function TribeMembership_InviteDenied_Event(): void
    {
        $member = $this->createMember();
        $project = Project::factory()->create();
        $role = TribeRole::factory()->project($project)->create();

        Event::fake([
            TribeMembershipDeniedEvent::class,
        ]);

        $projectRepository = new ProjectRepository();
        $this->actingAs($member);
        $projectRepository->inviteMember($project, $member, $role);
        $projectRepository->denyInvite($project, $member);

        Event::assertDispatched(TribeMembershipDeniedEvent::class);
    }

    /**
     * @test
     *
     * @group tribe-events
     */
    public function TribeMembership_Left_Event(): void
    {
        $member = $this->createMember();
        $project = Project::factory()->create();
        $this->inviteAndAccept($project, $member);

        Event::fake([
            TribeMembershipLeftEvent::class,
        ]);

        $projectRepository = new ProjectRepository();
        $projectRepository->leave($project, $member);

        Event::assertDispatched(TribeMembershipLeftEvent::class);
    }
}
