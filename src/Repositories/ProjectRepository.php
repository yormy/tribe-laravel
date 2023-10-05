<?php

declare(strict_types=1);

namespace Yormy\TribeLaravel\Repositories;

use Carbon\CarbonImmutable;
use Illuminate\Support\Carbon;
use Yormy\TribeLaravel\Models\Project;
use Yormy\TribeLaravel\Models\TribeMembership;
use Yormy\TribeLaravel\Models\TribeRole;
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

    public function denyInvite(Project $project, $member): void
    {
        $allMemberships = $member->tribeMemberships;

        foreach ($allMemberships as $membership) {
            $membership->delete(); // select proiject to delete
        }
    }

    public function acceptInvite(Project $project, $member): void
    {
        $allMemberships = $member->tribeMemberships;

        foreach ($allMemberships as $membership) {
            $membership->joined_at = Carbon::now();
            $membership->save(); // todo select proiject
        }
    }

    public function leave(Project $project, $member): void
    {
        $allMemberships = $member->tribeMemberships;

        foreach ($allMemberships as $membership) {
            $membership->delete(); // todo select proiject
        }
    }

    public function inviteMember(Project $project, $member, $role, CarbonImmutable $expiresAt = null): void
    {
        if (!$expiresAt) {
            $months = config('tribe.default_expire_membership_months', 12);
            $expiresAt = CarbonImmutable::now()->addMonth($months);
        }

        $userResolverClass = config('tribe.resolvers.user');
        $data = [
            'role_id' => $role->id,
            'invited_by' => $userResolverClass::get()->id,
            'expires_at' => $expiresAt,
        ];
        $project->memberships()->attach($member, $data);

        ProjectMemberInvitedEvent::dispatch($project, $member);
    }

    public function addMember(Project $project, $member, $role): void
    {
        $userResolverClass = config('tribe.resolvers.user');
        $data = [
            'role_id' => $role->id,
            'invited_by' => 1,//$userResolverClass::get(),
        ];
        $project->memberships()->attach($member, $data);

        ProjectMemberAddedEvent::dispatch($project, $member);
    }

    public function removeMember(Project $project, $member): void
    {
        $project->memberships()->detach($member);

        ProjectMemberRemovedEvent::dispatch($project, $member);
    }

    public function pendingInvite(Project $project, $member): bool
    {
        $member = $project
            ->memberships()
            ->where('member_id', $member->id)
            ->whereNull('tribe_memberships.deleted_at') // todo table name
            ->whereNull('joined_at')
            ->whereDate('expires_at', '>=', Carbon::now()) // to scope ?
            ->get()
            ->first();

        return (bool)$member;
    }


    public function isMember(Project $project, $member): bool
    {
        $member = $project
            ->memberships()
            ->where('member_id', $member->id)
            ->whereNull('tribe_memberships.deleted_at') // todo table name
            ->whereNotNull('joined_at')
            ->whereDate('expires_at', '>=', Carbon::now()) // to scope ?
            ->get()
            ->first();

        return (bool)$member;
    }

    public function isMemberWithRole(Project $project, $member, TribeRole $role): bool
    {
        $member = $project->memberships()
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
                ->from('tribe_memberships')
                ->where('member_id',  $member->id)
                ->where('project_id',  $project->id);
        })->pluck('name','id');

        return $permsCollection->contains($permission);
    }

    public function isOwner(Project $project, $member): bool
    {
        $member = $project->memberships()
            ->withPivot('role_id')
            ->where('member_id', $member->id)
            ->get()
            ->first();
        dd($member);
        return true;
    }

}
