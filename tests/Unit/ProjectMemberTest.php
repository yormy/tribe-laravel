<?php

namespace Yormy\TribeLaravel\Tests\Unit;

use Facades\Yormy\TribeLaravel\Domain\Encryption\FileVault;
use Illuminate\Support\Facades\Storage;
use Yormy\TribeLaravel\Domain\Encryption\Exceptions\DecryptionFailedException;
use Yormy\TribeLaravel\Models\Project;
use Yormy\TribeLaravel\Models\ProjectRole;
use Yormy\TribeLaravel\Models\TribePermission;
use Yormy\TribeLaravel\Repositories\ProjectRepository;
use Yormy\TribeLaravel\Tests\TestCase;
use Yormy\TribeLaravel\Tests\Traits\AssertEncryptionTrait;
use Yormy\TribeLaravel\Tests\Traits\CleanupTrait;
use Yormy\TribeLaravel\Tests\Traits\EncryptionTrait;
use Yormy\TribeLaravel\Tests\Traits\FileTrait;
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

        $role = ProjectRole::factory()->project($project)->create(['code' => 'owner']);

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

        $role = ProjectRole::factory()->project($project)->create(['code' => 'owner']);

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

        $role = ProjectRole::factory()->project($project)->create(['code' => 'owner']);

        $projectRepository = new ProjectRepository();
        $projectRepository->addMember($project, $member, $role);

        $newRole = ProjectRole::factory()->project($project)->create(['code' => 'owner']);
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

        $role = ProjectRole::factory()->project($project)->create(['code' => 'owner']);

        $projectRepository = new ProjectRepository();
        $projectRepository->addMember($project, $member, $role);

        $memberHasRole = $projectRepository->isMemberWithRole($project, $member, $role);
        $this->assertTrue($memberHasRole);
    }


    /**
     * @test
     *
     * @group tribe-add
     * @group xxx
     */
    public function ProjectMember_HasPermission(): void
    {
        $member = $this->createMember();
        $project = Project::factory()->create();

        $role = ProjectRole::factory()->project($project)->create(['code' => 'owner']);
        TribePermission::factory()->role($role)->create(['name' => 'member_add']);

        $projectRepository = new ProjectRepository();
        $projectRepository->addMember($project, $member, $role);

        $memberHasRole = $projectRepository->memberHasPermission($project, $member, 'member_add');
        $this->assertTrue($memberHasRole);
    }

    /**
     * @test
     *
     * @group xxx1
     */
    public function Dummy(): void
    {
        $member = $this->createMember();
        $project = Project::factory()->create();

        $role = ProjectRole::factory()->project($project)->create(['code' => 'owner']);
        $role3 = ProjectRole::factory()->project($project)->create(['code' => '22']);

        $projectRepository = new ProjectRepository();
        $projectRepository->addMember($project, $member, $role);

        $isOwner = $projectRepository->isMemberWithRole($project, $member, $role3);

        TribePermission::factory()->role($role)->create(['name' => 'member_add']);
        dd($isOwner);
$this->assertTrue(true);

dd('fff');
    }
}
