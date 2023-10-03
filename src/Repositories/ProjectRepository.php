<?php

declare(strict_types=1);

namespace Yormy\TribeLaravel\Repositories;

use Yormy\TribeLaravel\Models\Project;
use Yormy\TribeLaravel\Models\ProjectMember;
use Yormy\TribeLaravel\Models\ProjectRole;
use Yormy\TribeLaravel\Models\TribePermission;
use Yormy\TribeLaravel\Observers\Events\ProjectMemberAddedEvent;
use Yormy\TribeLaravel\Observers\Events\ProjectMemberInvitedEvent;
use Yormy\TribeLaravel\Observers\Events\ProjectMemberRemovedEvent;

class ProjectRepository
{
    public function __construct(private ?Project $model = null)
    {
        if (! $model) {
            $this->model = new Project();
        }
    }

    public function inviteMember(Project $project, $member, $role): void
    {
        $userResolverClass = config('tribe.resolvers.user');
        $data = [
            'role_id' => $role->id,
            'invited_by' => $userResolverClass::get(),
        ];
        $project->members()->attach($member, $data);

        ProjectMemberInvitedEvent::dispatch($project, $member);
    }

    public function addMember(Project $project, $member, $role): void
    {
        $userResolverClass = config('tribe.resolvers.user');
        $data = [
            'role_id' => $role->id,
            'invited_by' => 1,//$userResolverClass::get(),
        ];
        $project->members()->attach($member, $data);

        ProjectMemberAddedEvent::dispatch($project, $member);
    }

    public function removeMember(Project $project, $member): void
    {
        $project->members()->detach($member);

        ProjectMemberRemovedEvent::dispatch($project, $member);
    }

    public function isMember(Project $project, $member): bool
    {
        $member = $project
            ->members()
            ->where('member_id', $member->id)
            ->get()
            ->first();

        return (bool)$member;
    }

    public function isMemberWithRole(Project $project, $member, ProjectRole $role): bool
    {
        $member = $project->members()
            ->withPivot('role_id')
            ->where('member_id', $member->id)
            ->where('role_id', $role->id)
            ->get()
            ->first();

        return (bool)$member;
    }

    public function memberHasPermission(Project $project, $member, $permission): bool
    {
        $permsCollection = TribePermission::whereIn('role_id', function ($query) use ($member, $project) {
            $query->select('role_id')
                ->from('tribe_members')
                ->where('member_id',  $member->id)
                ->where('project_id',  $project->id);
        })->pluck('name','id');

        return $permsCollection->contains($permission);
    }

    public function isOwner(Project $project, $member): bool
    {
        $member = $project->members()
            ->withPivot('role_id')
            ->where('member_id', $member->id)
            ->get()
            ->first();
        dd($member);
        return true;
    }

}
