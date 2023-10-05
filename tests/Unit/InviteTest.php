<?php

namespace Yormy\TribeLaravel\Tests\Unit;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Str;
use Yormy\TribeLaravel\Models\Project;
use Yormy\TribeLaravel\Models\TribeMembership;
use Yormy\TribeLaravel\Models\TribeRole;
use Yormy\TribeLaravel\Models\TribePermission;
use Yormy\TribeLaravel\Repositories\ProjectRepository;
use Yormy\TribeLaravel\Repositories\TribeMembershipRepository;
use Yormy\TribeLaravel\Tests\Setup\Models\Member;
use Yormy\TribeLaravel\Tests\TestCase;
use Yormy\TribeLaravel\Tests\Traits\MemberTrait;
use Yormy\TribeLaravel\Tests\Unit\Traits\AssertInviteTrait;

class InviteTest extends TestCase
{
    use MemberTrait;
    use AssertInviteTrait;

    /**
     * @test
     *
     * @group tribe-invite
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
     */
    public function Project_InviteMember_NotMemberYet(): void
    {
        $member = $this->createMember();
        $project = Project::factory()->create();
        $role = TribeRole::factory()->project($project)->create();

        $projectRepository = new ProjectRepository();
        $this->actingAs($member);
        $projectRepository->inviteMember($project, $member, $role);

        $this->assertTrue($projectRepository->pendingInvite($project, $member));

        $this->assertIsNotMember($project, $member);

        $this->assertActiveProjects($member, 0);
    }

    /**
     * @test
     *
     * @group tribe-invite
     */
    public function ProjectInvited_Accept_MemberOfProject(): void
    {
        $member = $this->createMember();
        $project = Project::factory()->create();
        $this->inviteAndAccept($project, $member);

        $this->assertIsMember($project, $member);

        $projectRepository = new ProjectRepository();
        $this->assertFalse($projectRepository->pendingInvite($project, $member));

        $this->assertActiveProjects($member, 1);
    }

    /**
     * @test
     *
     * @group tribe-invite
     */
    public function ProjectInvited2Projects_Accept_MemberOfProject(): void
    {
        $member = $this->createMember();
        $project = Project::factory()->create();
        $role = TribeRole::factory()->project($project)->create();

        $projectRepository = new ProjectRepository();
        $this->actingAs($member);
        $projectRepository->inviteMember($project, $member, $role);

        $project = Project::factory()->create();
        $projectRepository->inviteMember($project, $member, $role);

        $startPendingInvites = TribeMembership::whereNull('joined_at')->count();
        $projectRepository->acceptInvite($project, $member);

        $newPendingInvites = TribeMembership::whereNull('joined_at')->count();
        $this->assertEquals($startPendingInvites-1, $newPendingInvites);

        $this->assertIsMember($project, $member);

        $projectRepository = new ProjectRepository();
        $this->assertFalse($projectRepository->pendingInvite($project, $member));

        $this->assertActiveProjects($member, 1);
    }

    /**
     * @test
     *
     * @group tribe-invite
     */
    public function ProjectInvited_Deny_NotMember(): void
    {
        $member = $this->createMember();
        $project = Project::factory()->create();
        $role = TribeRole::factory()->project($project)->create();

        $projectRepository = new ProjectRepository();
        $this->actingAs($member);
        $projectRepository->inviteMember($project, $member, $role);

        $projectRepository->denyInvite($project, $member);

        $this->assertFalse($projectRepository->pendingInvite($project, $member));

        $this->assertIsNotMember($project, $member);
    }

    /**
     * @test
     *
     * @group tribe-invite
     * @group xxx
     */
    public function ProjectInvited2Projects_DenyOnlyThis_InvitesPresent(): void
    {
        $member = $this->createMember();
        $this->actingAs($member);

        $projectRepository = new ProjectRepository();

        $project = Project::factory()->create();
        $role = TribeRole::factory()->project($project)->create();
        $projectRepository->inviteMember($project, $member, $role);

        $project = Project::factory()->create();
        $projectRepository->inviteMember($project, $member, $role);

        $startPendingInvites = TribeMembership::whereNull('joined_at')->count();

        $projectRepository->denyInvite($project, $member);

        $newPendingInvites = TribeMembership::whereNull('joined_at')->count();
        $this->assertEquals($startPendingInvites-1, $newPendingInvites);



        $this->assertFalse($projectRepository->pendingInvite($project, $member));

        $this->assertIsNotMember($project, $member);
    }

    /**
     * @test
     *
     * @group tribe-invite
     */
    public function ProjectMembership_NotMemberOfOtherProject(): void
    {
        $member = $this->createMember();
        $project = Project::factory()->create();
        $this->inviteAndAccept($project, $member);

        $projectRepository = new ProjectRepository();
        $project2 = Project::factory()->create();
        $isMember = $projectRepository->isMember($project2, $member);
        $this->assertFalse($isMember);

        $this->assertActiveProjects($member, 1);
    }

    /**
     * @test
     *
     * @group tribe-invite
     */
    public function ProjectMembership_OtherMemberNotMemberOfProject(): void
    {
        $member = $this->createMember();
        $project = Project::factory()->create();
        $this->inviteAndAccept($project, $member);

        $member2 = $this->createMember();

        $this->assertIsNotMember($project, $member2);
        $this->assertActiveProjects($member, 1);
    }

    /**
     * @test
     *
     * @group tribe-invite
    */
    public function ProjectMemberhsip_Expired_NotMember(): void
    {
        $member = $this->createMember();
        $project = Project::factory()->create();
        $this->inviteAndAccept($project, $member);

        Carbon::setTestNow(CarbonImmutable::now()->addYears(2));
        $this->assertIsNotMember($project, $member);
    }

    /**
     * @test
     *
     * @group tribe-invite
     */
    public function ProjectMembership_Leave_NotMember(): void
    {
        $member = $this->createMember();
        $project = Project::factory()->create();
        $this->inviteAndAccept($project, $member);

        $projectRepository = new ProjectRepository();
        $projectRepository->leave($project, $member);

        $this->assertIsNotMember($project, $member);
    }

}
