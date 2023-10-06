<?php

namespace Yormy\TribeLaravel\Tests\Unit;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Event;
use Yormy\TribeLaravel\Models\Project;
use Yormy\TribeLaravel\Models\TribePermission;
use Yormy\TribeLaravel\Models\TribeRole;
use Yormy\TribeLaravel\Observers\Events\TribeMembershipAcceptedEvent;
use Yormy\TribeLaravel\Observers\Events\TribeMembershipDeniedEvent;
use Yormy\TribeLaravel\Observers\Events\TribeMembershipInvitedEvent;
use Yormy\TribeLaravel\Observers\Events\TribeMembershipLeftEvent;
use Yormy\TribeLaravel\Repositories\ProjectRepository;
use Yormy\TribeLaravel\Rules\ProjectApiKeyRule;
use Yormy\TribeLaravel\Rules\DummyRule;
use Yormy\TribeLaravel\Rules\MemberOfProjectRule;
use Yormy\TribeLaravel\Rules\ProjectActiveRule;
use Yormy\TribeLaravel\Rules\ProjectExistsRule;
use Yormy\TribeLaravel\Rules\ProjectPermissionRule;
use Yormy\TribeLaravel\Tests\TestCase;
use Yormy\TribeLaravel\Tests\Traits\MemberTrait;
use Yormy\TribeLaravel\Tests\Unit\Traits\AssertInviteTrait;
use Illuminate\Contracts\Validation\ValidationRule;
use Yormy\TribeLaravel\Tests\Unit\Traits\AssertRuleTrait;

class EventsTest extends TestCase
{
    use MemberTrait;
    use AssertInviteTrait;
    use AssertRuleTrait;

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
            TribeMembershipInvitedEvent::class
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
            TribeMembershipAcceptedEvent::class
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
            TribeMembershipDeniedEvent::class
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
            TribeMembershipLeftEvent::class
        ]);

        $projectRepository = new ProjectRepository();
        $projectRepository->leave($project, $member);

        Event::assertDispatched(TribeMembershipLeftEvent::class);
    }

}
