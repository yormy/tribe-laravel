<?php

namespace Yormy\TribeLaravel\Tests\Unit;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Str;
use Yormy\TribeLaravel\Models\Project;
use Yormy\TribeLaravel\Models\TribeRole;
use Yormy\TribeLaravel\Models\TribePermission;
use Yormy\TribeLaravel\Repositories\ProjectRepository;
use Yormy\TribeLaravel\Repositories\TribeMembershipRepository;
use Yormy\TribeLaravel\Tests\TestCase;
use Yormy\TribeLaravel\Tests\Traits\MemberTrait;

class InviteTest extends TestCase
{
    use MemberTrait;

    /**
     * @test
     *
     * @group tribe-invite
     */
    public function Project_InviteMember_NotMemberYes(): void
    {
        $member = $this->createMember();
        $project = Project::factory()->create();
        $role = TribeRole::factory()->project($project)->create();

        $projectRepository = new ProjectRepository();
        $this->actingAs($member);
        $projectRepository->inviteMember($project, $member, $role);

        $isMember = $projectRepository->isMember($project, $member);
        $this->assertFalse($isMember);
    }

    /**
     * @test
     *
     * @group tribe-invite
     */
    public function ProjectInvitedMember_Accept_Member(): void
    {
        $member = $this->createMember();
        $project = Project::factory()->create();
        $role = TribeRole::factory()->project($project)->create();

        $projectRepository = new ProjectRepository();
        $this->actingAs($member);
        $projectRepository->inviteMember($project, $member, $role);

        $isMember = $projectRepository->isMember($project, $member);
        $this->assertFalse($isMember);

        $projectRepository->acceptInvite($project, $member);

        $isMember = $projectRepository->isMember($project, $member);
        $this->assertTrue($isMember);
    }

    /**
     * @test
     *
     * @group tribe-invite
     */
    public function ProjectInvitedMember_Deny_NotMember(): void
    {
        $member = $this->createMember();
        $project = Project::factory()->create();
        $role = TribeRole::factory()->project($project)->create();

        $projectRepository = new ProjectRepository();
        $this->actingAs($member);
        $projectRepository->inviteMember($project, $member, $role);


        $projectRepository->denyInvite($project, $member);

        $isMember = $projectRepository->isMember($project, $member);
        $this->assertFalse($isMember);
    }

    /**
     * @test
     *
     * @group tribe-invite
     * @group xxx
     */
    public function ProjectInvitedMember_Expire_NotMember(): void
    {
        $member = $this->createMember();
        $project = Project::factory()->create();
        $role = TribeRole::factory()->project($project)->create();

        $projectRepository = new ProjectRepository();
        $this->actingAs($member);
        $projectRepository->inviteMember($project, $member, $role);
        $projectRepository->acceptInvite($project, $member);


        $isMember = $projectRepository->isMember($project, $member);
        $this->assertTrue($isMember);

        Carbon::setTestNow(CarbonImmutable::now()->addYears(2));
        $isMember = $projectRepository->isMember($project, $member);
        $this->assertFalse($isMember);
    }

    /**
     * @test
     *
     * @group tribe-invite
     * @group xxx
     */
    public function ProjectInvitedMember_Leave_NotMember(): void
    {
        $member = $this->createMember();
        $project = Project::factory()->create();
        $role = TribeRole::factory()->project($project)->create();

        $projectRepository = new ProjectRepository();
        $this->actingAs($member);
        $projectRepository->inviteMember($project, $member, $role);
        $projectRepository->acceptInvite($project, $member);
        $this->assertFalse($projectRepository->pendingInvite($project, $member));

        $projectRepository->leave($project, $member);

        $isMember = $projectRepository->isMember($project, $member);
        $this->assertFalse($isMember);
    }

    /**
     * @test
     *
     * @group tribe-invite
     * @group xxx
     */
    public function Project_InvitedMember_Pending(): void
    {
        $member = $this->createMember();
        $project = Project::factory()->create();
        $role = TribeRole::factory()->project($project)->create();

        $projectRepository = new ProjectRepository();
        $this->actingAs($member);
        $projectRepository->inviteMember($project, $member, $role);

        $this->assertTrue($projectRepository->pendingInvite($project, $member));
    }

    /**
     * @test
     *
     * @group tribe-invite
     * @group xxx
     */
    public function testing(): void
    {
        $member = $this->createMember();
        $project = Project::factory()->create();
        $role = TribeRole::factory()->project($project)->create();

        $projectRepository = new ProjectRepository();
        $this->actingAs($member);
        $projectRepository->inviteMember($project, $member, $role);
        $projectRepository->acceptInvite($project, $member);

        $tribeMembershipRepository = new TribeMembershipRepository();
        $isMember = $tribeMembershipRepository->isMember($project, $member);
        $this->assertTrue($isMember);
    }
}
