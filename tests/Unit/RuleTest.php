<?php

namespace Yormy\TribeLaravel\Tests\Unit;

use Illuminate\Contracts\Validation\Rule;
use Yormy\TribeLaravel\Models\Project;
use Yormy\TribeLaravel\Models\TribeRole;
use Yormy\TribeLaravel\Repositories\ProjectRepository;
use Yormy\TribeLaravel\Rules\DummyRule;
use Yormy\TribeLaravel\Tests\TestCase;
use Yormy\TribeLaravel\Tests\Traits\MemberTrait;
use Yormy\TribeLaravel\Tests\Unit\Traits\AssertInviteTrait;
use Illuminate\Contracts\Validation\ValidationRule;
use Yormy\TribeLaravel\Tests\Unit\Traits\AssertRuleTrait;

class RuleTest extends TestCase
{
    use MemberTrait;
    use AssertInviteTrait;
    use AssertRuleTrait;

    /**
     * @test
     *
     * @group tribe-rule
     * @group xxx
     */
    public function Rule(): void
    {
        $member = $this->createMember();
        $project = Project::factory()->create();
        $role = TribeRole::factory()->project($project)->create();

        $projectRepository = new ProjectRepository();
        $this->actingAs($member);
        $projectRepository->inviteMember($project, $member, $role);

        $this->assertTrue($projectRepository->pendingInvite($project, $member));

        $rule = new DummyRule();

        $this->assertRulePasses($rule, 's');
        $this->assertRuleFails($rule, 'fail');
    }
}
