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
     * @group xxx
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

    private function assertActiveProjects($member, int $count)
    {
        $projectRepository = new ProjectRepository();
        $allActiveProjects = $projectRepository->allActiveProjects($member);
        $this->assertCount($count, $allActiveProjects);
    }


    /**
     * @test
     *
     * @group tribe-invite
     * @group xxx
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
     * @group xxx
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
     * @group xxx
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
     * @group xxx
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

    // ---------- HELPERS ----------

    private function assertIsNotMember(Project $project, $member)
    {
        $projectRepository = new ProjectRepository();

        $isMember = $projectRepository->isMember($project, $member);
        $this->assertFalse($isMember);

        $this->assertActiveProjects($member, 0);
    }

    private function assertIsMember(Project $project, $member)
    {
        $projectRepository = new ProjectRepository();

        $isMember = $projectRepository->isMember($project, $member);
        $this->assertTrue($isMember);

        $this->assertActiveProjects($member, 1);
    }

    private function inviteAndAccept($project, $member)
    {
        $role = TribeRole::factory()->project($project)->create();

        $projectRepository = new ProjectRepository();
        $this->actingAs($member);
        $projectRepository->inviteMember($project, $member, $role);
        $projectRepository->acceptInvite($project, $member);
    }
}
