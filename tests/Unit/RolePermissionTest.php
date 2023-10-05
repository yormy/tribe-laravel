<?php

namespace Yormy\TribeLaravel\Tests\Unit;

use Illuminate\Support\Str;
use Yormy\TribeLaravel\Models\Project;
use Yormy\TribeLaravel\Models\TribeRole;
use Yormy\TribeLaravel\Models\TribePermission;
use Yormy\TribeLaravel\Repositories\ProjectRepository;
use Yormy\TribeLaravel\Tests\TestCase;
use Yormy\TribeLaravel\Tests\Traits\MemberTrait;
use Yormy\TribeLaravel\Tests\Unit\Traits\AssertInviteTrait;

class RolePermissionTest extends TestCase
{
    use MemberTrait;
    use AssertInviteTrait;


    /**
     * @test
     *
     * @group tribe-rolepermission
     */
    public function ProjectMembershipRole_IsMemberWithRole(): void
    {
        $member = $this->createMember();
        $project = Project::factory()->create();
        $role = TribeRole::factory()->project($project)->create();

        $this->inviteAndAccept($project, $member, $role);
        $this->assertIsMember($project, $member);

        $projectRepository = new ProjectRepository();
        $memberHasRole = $projectRepository->isMemberWithRole($project, $member, $role);
        $this->assertTrue($memberHasRole);
    }

    /**
     * @test
     *
     * @group tribe-rolepermission
     */
    public function ProjectMembershipRole_IsNotMemberWithOtherRole(): void
    {
        $member = $this->createMember();
        $project = Project::factory()->create();
        $role = TribeRole::factory()->project($project)->create();

        $this->inviteAndAccept($project, $member, $role);
        $this->assertIsMember($project, $member);

        $projectRepository = new ProjectRepository();
        $newRole = TribeRole::factory()->project($project)->create();
        $memberHasRole = $projectRepository->isMemberWithRole($project, $member, $newRole);
        $this->assertFalse($memberHasRole);
    }

    /**
     * @test
     *
     * @group tribe-rolepermission
     */
    public function ProjectMembership_HasPermission(): void
    {
        $member = $this->createMember();
        $project = Project::factory()->create();
        $permissionName = Str::random(5);

        $role = TribeRole::factory()->project($project)->create();
        TribePermission::factory()->role($role)->create(['name' => $permissionName]);

        $this->inviteAndAccept($project, $member, $role);

        $projectRepository = new ProjectRepository();
        $memberHasRole = $projectRepository->memberHasPermission($project, $member, $permissionName);
        $this->assertTrue($memberHasRole);
    }

    /**
     * @test
     *
     * @group tribe-rolepermission
     */
    public function ProjectMembership_HasNotPermission(): void
    {
        $member = $this->createMember();
        $project = Project::factory()->create();
        $permissionName = Str::random(5);

        $role = TribeRole::factory()->project($project)->create();
        TribePermission::factory()->role($role)->create(['name' => $permissionName]);

        $this->inviteAndAccept($project, $member, $role);

        $projectRepository = new ProjectRepository();
        $memberHasRole = $projectRepository->memberHasPermission($project, $member, 'missing_permission_name');
        $this->assertFalse($memberHasRole);
    }
}
