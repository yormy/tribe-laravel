<?php

declare(strict_types=1);

namespace Yormy\TribeLaravel\Tests\Unit\Traits;

use Yormy\TribeLaravel\Models\Project;
use Yormy\TribeLaravel\Models\TribeRole;
use Yormy\TribeLaravel\Repositories\ProjectRepository;

trait AssertInviteTrait
{
    protected function inviteAndAccept($project, $member, $role = null)
    {
        if (! $role) {
            $role = TribeRole::factory()->project($project)->create();
        }

        $projectRepository = new ProjectRepository();
        $this->actingAs($member);
        $projectRepository->inviteMember($project, $member, $role);
        $member->refresh();
        $projectRepository->acceptInvite($project, $member);
        $member->refresh();
    }

    protected function assertIsNotMember(Project $project, $member)
    {
        $projectRepository = new ProjectRepository();

        $isMember = $projectRepository->isMember($project, $member);
        $this->assertFalse($isMember);
    }

    protected function assertIsMember(Project $project, $member)
    {
        $projectRepository = new ProjectRepository();

        $isMember = $projectRepository->isMember($project, $member);
        $this->assertTrue($isMember);
    }

    protected function assertActiveProjects($member, int $count)
    {
        $projectRepository = new ProjectRepository();
        $allActiveProjects = $projectRepository->allActiveProjects($member);
        $this->assertCount($count, $allActiveProjects);
    }
}
