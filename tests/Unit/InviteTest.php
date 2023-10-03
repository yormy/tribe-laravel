<?php

namespace Yormy\TribeLaravel\Tests\Unit;

use Illuminate\Support\Str;
use Yormy\TribeLaravel\Models\Project;
use Yormy\TribeLaravel\Models\ProjectRole;
use Yormy\TribeLaravel\Models\TribePermission;
use Yormy\TribeLaravel\Repositories\ProjectRepository;
use Yormy\TribeLaravel\Tests\TestCase;
use Yormy\TribeLaravel\Tests\Traits\MemberTrait;

class InviteTest extends TestCase
{
    use MemberTrait;

    /**
     * @test
     *
     * @group tribe-add
     */
    public function Project_InviteMember(): void
    {
//        $member = $this->createMember();
//        $project = Project::factory()->create();
//
//
//        $projectRepository = new ProjectRepository();
//        $projectRepository->addMember($project, $member, $role);
//
//        $isMember = $projectRepository->isMember($project, $member);
//        $this->assertTrue($isMember);
    }


}
