<?php

namespace Yormy\TribeLaravel\Tests\Unit;

use Yormy\TribeLaravel\Models\Project;
use Yormy\TribeLaravel\Models\TribePermission;
use Yormy\TribeLaravel\Models\TribeRole;
use Yormy\TribeLaravel\Rules\MemberOfProjectRule;
use Yormy\TribeLaravel\Rules\ProjectActiveRule;
use Yormy\TribeLaravel\Rules\ProjectApiKeyRule;
use Yormy\TribeLaravel\Rules\ProjectExistsRule;
use Yormy\TribeLaravel\Rules\ProjectPermissionRule;
use Yormy\TribeLaravel\Tests\TestCase;
use Yormy\TribeLaravel\Tests\Traits\MemberTrait;
use Yormy\TribeLaravel\Tests\Unit\Traits\AssertInviteTrait;
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
     */
    public function TribeMembershipRule_MemberOfProject_Pass(): void
    {
        $member = $this->createMember();
        $project = Project::factory()->create();
        $this->inviteAndAccept($project, $member);

        $this->actingAs($member);
        $rule = new MemberOfProjectRule();
        $this->assertRulePasses($rule, $project->xid);
    }

    /**
     * @test
     *
     * @group tribe-rule
     */
    public function TribeMembershipRule_MemberOfProject_Fail(): void
    {
        $member = $this->createMember();
        $project = Project::factory()->create();
        $this->inviteAndAccept($project, $member);

        $member2 = $this->createMember();
        $this->actingAs($member2);
        $rule = new MemberOfProjectRule();
        $this->assertRuleFails($rule, $project->xid);
    }

    /**
     * @test
     *
     * @group tribe-rule
     */
    public function TribeMembershipRule_ProjectExists_Pass(): void
    {
        $project = Project::factory()->create();

        $rule = new ProjectExistsRule();
        $this->assertRulePasses($rule, $project->xid);
    }

    /**
     * @test
     *
     * @group tribe-rule
     */
    public function TribeMembershipRule_ProjectExists_Fail(): void
    {
        Project::factory()->create();

        $rule = new ProjectExistsRule();
        $this->assertRuleFails($rule, 'jjjjj');
    }

    /**
     * @test
     *
     * @group tribe-rule
     */
    public function TribeMembershipRule_ProjectActive_Pass(): void
    {
        $project = Project::factory()->create();

        $rule = new ProjectActiveRule();
        $this->assertRulePasses($rule, $project->xid);
    }

    /**
     * @test
     *
     * @group tribe-rule
     */
    public function TribeMembershipRule_ProjectActive_Fail(): void
    {
        $project = Project::factory()->disabled()->create();

        $rule = new ProjectActiveRule();
        $this->assertRuleFails($rule, $project->xid);
    }

    /**
     * @test
     *
     * @group tribe-rule
     */
    public function TribeMembershipRule_ProjectPermission_Pass(): void
    {
        $permissionName = 'add_member';
        $member = $this->createMember();
        $project = Project::factory()->create();
        $role = TribeRole::factory()->project($project)->create();
        TribePermission::factory()->role($role)->create(['name' => $permissionName]);

        $this->inviteAndAccept($project, $member, $role);

        $rule = new ProjectPermissionRule($permissionName);
        $this->assertRulePasses($rule, $project->xid);
    }

    /**
     * @test
     *
     * @group tribe-rule
     */
    public function TribeMembershipRule_ProjectPermission_Fail(): void
    {
        $permissionName = 'add_member';
        $member = $this->createMember();
        $project = Project::factory()->create();
        $role = TribeRole::factory()->project($project)->create();
        TribePermission::factory()->role($role)->create(['name' => $permissionName]);

        $this->inviteAndAccept($project, $member, $role);

        $rule = new ProjectPermissionRule('wrong permission');
        $this->assertRuleFails($rule, $project->xid);
    }

    /**
     * @test
     *
     * @group tribe-rule
     */
    public function TribeMembershipRule_ApiKey_Pass(): void
    {
        $project = Project::factory()->create();

        $rule = new ProjectApiKeyRule();
        $this->assertRulePasses($rule, $project->api_submit_key);
    }

    /**
     * @test
     *
     * @group tribe-rule
     */
    public function TribeMembershipRule_WrongApiKey_Fail(): void
    {
        $project = Project::factory()->create();

        $rule = new ProjectApiKeyRule();
        $this->assertRuleFails($rule, 'invalid_key');
    }

    /**
     * @test
     *
     * @group tribe-rule
     */
    public function TribeMembershipRule_DisabledProjectApiKey_Fail(): void
    {
        $project = Project::factory()->disabled()->create();

        $rule = new ProjectApiKeyRule();
        $this->assertRuleFails($rule, $project->api_submit_key);
    }
}
