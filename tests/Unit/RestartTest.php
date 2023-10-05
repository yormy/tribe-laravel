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
use Yormy\TribeLaravel\Tests\Setup\Models\Member;
use Yormy\TribeLaravel\Tests\TestCase;
use Yormy\TribeLaravel\Tests\Traits\MemberTrait;

class RestartTest extends TestCase
{
    use MemberTrait;

    /**
     * @test
     *
     * @group tribe-invite
     * @group xxx
     */
    public function Project_InviteMember_NotMemberYet(): void
    {
        $member = $this->createMember();
        $project = Project::factory()->create();
        $role = TribeRole::factory()->project($project)->create();

        $projectRepository = new ProjectRepository();
        $this->actingAs($member);
        $projectRepository->inviteMember($project, $member, $role);

        $isMember = $projectRepository->isMember($project, $member);
        $this->assertFalse($isMember);

        $allActiveMembers = $projectRepository->allActiveMembers($project);
        $this->assertCount(0, $allActiveMembers);
    }

    /**
     * @test
     *
     * @group tribe-invite
     * @group xxx
     */
    public function ProjectInvitedMember_AcceptMembership_MemberOfProject(): void
    {
        $member = $this->createMember();
        $project = Project::factory()->create();
        $role = TribeRole::factory()->project($project)->create();

        $this->inviteAndAccept($project, $member);

        $projectRepository = new ProjectRepository();
        $isMember = $projectRepository->isMember($project, $member);
        $this->assertTrue($isMember);

        $allActiveMembers = $projectRepository->allActiveMembers($project);
        $this->assertCount(1, $allActiveMembers);
    }

    /**
     * @test
     *
     * @group tribe-invite
     * @group xxx
     */
    public function ProjectInvitedMember_AcceptMembership_NotMemberOfOtherProject(): void
    {
        $member = $this->createMember();
        $project = Project::factory()->create();
        $role = TribeRole::factory()->project($project)->create();
        $this->inviteAndAccept($project, $member);

        $projectRepository = new ProjectRepository();
        $project2 = Project::factory()->create();
        $isMember = $projectRepository->isMember($project2, $member);
        $this->assertFalse($isMember);
    }

    /**
     * @test
     *
     * @group tribe-invite
     * @group xxx
     */
    public function ProjectInvitedMember_AcceptMembership_OtherMemberNotMemberOfProject(): void
    {
        $member = $this->createMember();
        $project = Project::factory()->create();
        $role = TribeRole::factory()->project($project)->create();
        $this->inviteAndAccept($project, $member);

        $projectRepository = new ProjectRepository();

        $member2 = $this->createMember();
        $isMember = $projectRepository->isMember($project, $member2);
        $this->assertFalse($isMember);
    }

// has 1 project
// has 1 member



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

     */
    public function ProjectInvitedMember_Expire_NotMember(): void
    {
        $member = $this->createMember();
        $project = Project::factory()->create();
        $role = TribeRole::factory()->project($project)->create();
        $this->inviteAndAccept($project, $member);

        $projectRepository = new ProjectRepository();

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

     */
    public function ProjectInvitedMember_Leave_NotMember(): void
    {
        $member = $this->createMember();
        $project = Project::factory()->create();
        $role = TribeRole::factory()->project($project)->create();
        $this->inviteAndAccept($project, $member);

        $projectRepository = new ProjectRepository();
        $this->assertFalse($projectRepository->pendingInvite($project, $member));

        $projectRepository->leave($project, $member);

        $isMember = $projectRepository->isMember($project, $member);
        $this->assertFalse($isMember);
    }

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
    public function testing2(): void
    {
        $member = $this->createMember();
        $project = Project::factory()->create();
        $role = TribeRole::factory()->project($project)->create();
        $this->inviteAndAccept($project, $member);

        $projectRepository = new ProjectRepository();
        $isMember = $projectRepository->isMember($project, $member);
        $this->assertTrue($isMember);
    }

    /**
     * @test
     *
     * @group tribe-invite

     */
    public function ProjectMember_NewMember_OrginalIsMember(): void
    {
        $memberOriginal = $this->createMember();
        $project = Project::factory()->create();
        $this->inviteAndAccept($project, $memberOriginal);

        $memberNew = $this->createMember();
        $projectRepository = new ProjectRepository();
        $isMember = $projectRepository->isMember($project, $memberOriginal);
        $this->assertTrue($isMember);
    }

    /**
     * @test
     *
     * @group tribe-invite

     */
    public function ProjectMember_NewMember_IsNotMember(): void
    {
        $memberOriginal = $this->createMember();
        $project = Project::factory()->create();
        $this->inviteAndAccept($project, $memberOriginal);

        $memberNew = $this->createMember();

        $projectRepository = new ProjectRepository();
        $isMember = $projectRepository->isMember($project, $memberNew);
        $this->assertFalse($isMember);
    }

    // ---------- HELPERS ----------

    private function inviteAndAccept($project, $member)
    {
        $role = TribeRole::factory()->project($project)->create();

        $projectRepository = new ProjectRepository();
        $this->actingAs($member);
        $projectRepository->inviteMember($project, $member, $role);
        $projectRepository->acceptInvite($project, $member);
    }
}
