<?php

namespace Yormy\TribeLaravel\Tests\Unit;

use Carbon\CarbonImmutable;
use Illuminate\Support\Str;
use Yormy\TribeLaravel\Models\Project;
use Yormy\TribeLaravel\Models\TribePermission;
use Yormy\TribeLaravel\Models\TribeRole;
use Yormy\TribeLaravel\Repositories\ProjectRepository;
use Yormy\TribeLaravel\Repositories\TribeMembershipRepository;
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
    public function TribeMembershipRole_IsMemberWithRole(): void
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
    public function TribeMembershipRole_IsNotMemberWithOtherRole(): void
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
    public function TribeMembership_HasPermission(): void
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
    public function TribeMembership_HasNotPermission(): void
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

    /**
     * @test
     *
     * @group tribe-rolepermission
     * @group xxx
     */
    public function TribeMembership_ChangeRole_OnlyNewPermission(): void
    {
        $member = $this->createMember();
        $project = Project::factory()->create();

        $role = TribeRole::factory()->project($project)->create();
        TribePermission::factory()->role($role)->create(['name' => 'old_role_permission']);

        $this->inviteAndAccept($project, $member, $role);

        $membership = $member->tribeMemberships->first();
        $tibeMembershipRepository = new TribeMembershipRepository();
        $newRole = TribeRole::factory()->project($project)->create();
        TribePermission::factory()->role($newRole)->create(['name' => 'new_role_permission']);
        $tibeMembershipRepository->setRole($membership, $newRole , CarbonImmutable::now()->addDay());

        $projectRepository = new ProjectRepository();
        $member->refresh();
        $this->assertFalse($projectRepository->memberHasPermission($project, $member, 'old_role_permission'));
        $this->assertTrue($projectRepository->memberHasPermission($project, $member, 'new_role_permission'));
    }
}
