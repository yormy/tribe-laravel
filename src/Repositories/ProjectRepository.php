<?php

declare(strict_types=1);

namespace Yormy\TribeLaravel\Repositories;

use Carbon\CarbonImmutable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Yormy\TribeLaravel\Models\Project;
use Yormy\TribeLaravel\Models\Scopes\MembershipScopeTrait;
use Yormy\TribeLaravel\Models\TribeMembership;
use Yormy\TribeLaravel\Models\TribePermission;
use Yormy\TribeLaravel\Models\TribeRole;
use Yormy\TribeLaravel\Observers\Events\TribeMembershipAcceptedEvent;
use Yormy\TribeLaravel\Observers\Events\TribeMembershipDeniedEvent;
use Yormy\TribeLaravel\Observers\Events\TribeMembershipInvitedEvent;
use Yormy\TribeLaravel\Observers\Events\TribeMembershipLeftEvent;

class ProjectRepository
{
    use MembershipScopeTrait;

    public function __construct(private ?Project $model = null)
    {
        if (! $model) {
            $this->model = new Project();
        }
    }

    public function findOneByXid(string $xid): ?Project
    {
        return $this->model->where('xid', $xid)->first();
    }

    public function findOneActiveByXid(string $xid): ?Project
    {
        return $this->model
            ->where('xid', $xid)
            ->notDisabled()
            ->first();
    }

    public function findOneActiveByApiKey(string $apiKey): ?Project
    {
        return $this->model
            ->where('api_submit_key', $apiKey)
            ->notDisabled()
            ->first();
    }

    public function denyInvite(Project $project, $member): void
    {
        $allMemberships = $member->tribeMemberships;

        foreach ($allMemberships as $membership) {
            if ($membership->project_id === $project->id) {
                $membership->delete();
                TribeMembershipDeniedEvent::dispatch($project, $member);
            }
        }
    }

    public function acceptInvite(Project $project, $member): void
    {
        $allMemberships = $member->tribeMemberships;

        foreach ($allMemberships as $membership) {
            if ($membership->project_id === $project->id) {
                $membership->joined_at = Carbon::now();
                $membership->save();
                TribeMembershipAcceptedEvent::dispatch($project, $member);
            }
        }
    }

    public function leave(Project $project, $member): void
    {
        $allMemberships = $member->tribeMemberships;

        foreach ($allMemberships as $membership) {
            if ($membership->project_id === $project->id) {
                $membership->delete();
                TribeMembershipLeftEvent::dispatch($project, $member);
            }
        }
    }

    public function inviteMember(Project $project, $member, $role, ?CarbonImmutable $expiresAt = null): void
    {
        if (! $expiresAt) {
            $months = config('tribe.default_expire_membership_months', 12);
            $expiresAt = CarbonImmutable::now()->addMonth($months);
        }

        $userResolverClass = config('tribe.resolvers.user');
        $data = [
            'role_id' => $role->id,
            'invited_by' => $userResolverClass::get()->id,
            'expires_at' => $expiresAt,
        ];
        $project->tribeMemberships()->attach($member, $data);

        TribeMembershipInvitedEvent::dispatch($project, $member);
    }

    public function pendingInvite(Project $project, $member): bool
    {
        $query = $project->tribeMemberships();
        $query = $this->scopeMember($query, $member);
        $query = $this->scopeNotJoined($query);
        $query = $this->scopeNotExpired($query);

        $table = (new TribeMembership())->getTable();
        $query->whereNull("$table.deleted_at");

        $found = $query->first();

        return (bool) $found;
    }

    public function isMember(Project $project, $member): bool
    {
        $query = $project->tribeMemberships();
        $query = $this->scopeMember($query, $member);
        $query = $this->scopeActive($query);

        $memberCount = $query->count();

        return (bool) $memberCount;
    }

    public function allActiveMembers(Project $project): Collection
    {
        $query = $project->tribeMemberships();
        $query = $this->scopeActive($query);

        return $query->get();
    }

    public function allActiveProjects($member): Collection
    {
        $query = TribeMembership::where('member_id', $member->id);
        $query = $this->scopeActive($query);

        return $query->get();
    }

    public function isMemberWithRole(Project $project, $member, TribeRole $role): bool
    {
        $query = $project->tribeMemberships();
        $query = $this->scopeActive($query);
        $query = $this->scopeMember($query, $member);

        $query->withPivot('role_id');
        $query = $this->scopeRole($query, $role);

        $found = $query->first();

        return (bool) $found;
    }

    public function memberHasPermission(Project $project, $member, $permission): bool
    {
        $permsCollection = TribePermission::whereIn('role_id', function ($query) use ($member, $project) {
            $query->select('role_id')
                ->from('tribe_memberships');
            $query = $this->scopeMember($query, $member);
            $query = $this->scopeProject($query, $project);
        })->pluck('name', 'id');

        return $permsCollection->contains($permission);
    }
}
