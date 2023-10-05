<?php

namespace Yormy\TribeLaravel\Tests\Unit;

use Illuminate\Support\Str;
use Yormy\TribeLaravel\Models\Project;
use Yormy\TribeLaravel\Models\TribeRole;
use Yormy\TribeLaravel\Models\TribePermission;
use Yormy\TribeLaravel\Repositories\ProjectRepository;
use Yormy\TribeLaravel\Tests\TestCase;
use Yormy\TribeLaravel\Tests\Traits\MemberTrait;

class ProjectMemberTest extends TestCase
{
    use MemberTrait;

    /**
     * @test
     *
     * @group tribe-add
     */
    public function Project_AddMember_IsMember(): void
    {
        $member = $this->createMember();
        $project = Project::factory()->create();

        $role = TribeRole::factory()->project($project)->create();

        $projectRepository = new ProjectRepository();
        $projectRepository->addMember($project, $member, $role);

        $isMember = $projectRepository->isMember($project, $member);
        $this->assertTrue($isMember);
    }

    /**
     * @test
     *
     * @group tribe-add
     */
    public function ProjectMember_AddNewMember_IsNotMember(): void
    {
        $member = $this->createMember();
        $project = Project::factory()->create();

        $role = TribeRole::factory()->project($project)->create();

        $projectRepository = new ProjectRepository();
        $projectRepository->addMember($project, $member, $role);

        $nonMember = $this->createMember();
        $isMember = $projectRepository->isMember($project, $nonMember);
        $this->assertFalse($isMember);
    }

    /**
     * @test
     *
     * @group tribe-add
     */
    public function Project_AddMemberRole_IsNotMemberWithOtherRole(): void
    {
        $member = $this->createMember();
        $project = Project::factory()->create();

        $role = TribeRole::factory()->project($project)->create();

        $projectRepository = new ProjectRepository();
        $projectRepository->addMember($project, $member, $role);

        $newRole = TribeRole::factory()->project($project)->create();
        $memberHasRole = $projectRepository->isMemberWithRole($project, $member, $newRole);
        $this->assertFalse($memberHasRole);
    }

    /**
     * @test
     *
     * @group tribe-add
     */
    public function Project_AddMemberRole_IsMemberWithRole(): void
    {
        $member = $this->createMember();
        $project = Project::factory()->create();

        $role = TribeRole::factory()->project($project)->create();

        $projectRepository = new ProjectRepository();
        $projectRepository->addMember($project, $member, $role);

        $memberHasRole = $projectRepository->isMemberWithRole($project, $member, $role);
        $this->assertTrue($memberHasRole);
    }


    /**
     * @test
     *
     * @group tribe-add
     */
    public function ProjectMember_HasPermission(): void
    {
        $member = $this->createMember();
        $project = Project::factory()->create();
        $permissionName = Str::random(5);

        $role = TribeRole::factory()->project($project)->create();
        TribePermission::factory()->role($role)->create(['name' => $permissionName]);

        $projectRepository = new ProjectRepository();
        $projectRepository->addMember($project, $member, $role);

        $memberHasRole = $projectRepository->memberHasPermission($project, $member, $permissionName);
        $this->assertTrue($memberHasRole);
    }

    /**
     * @test
     *
     * @group tribe-add
     */
    public function ProjectMember_HasNotPermission(): void
    {
        $member = $this->createMember();
        $project = Project::factory()->create();
        $permissionName = Str::random(5);

        $role = TribeRole::factory()->project($project)->create();
        TribePermission::factory()->role($role)->create(['name' => $permissionName]);

        $projectRepository = new ProjectRepository();
        $projectRepository->addMember($project, $member, $role);

        $memberHasRole = $projectRepository->memberHasPermission($project, $member, 'missing_permission_name');
        $this->assertFalse($memberHasRole);
    }

}
