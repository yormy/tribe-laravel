<?php

namespace Yormy\ProjectMembersLaravel\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Yormy\ProjectMembersLaravel\Models\ProjectInvite;
use Yormy\ProjectMembersLaravel\Models\ProjectMember;

trait HasMembersTrait
{
    public function members(): HasManyThrough
    {
        $roleOwner = config('project-members-laravel.role_owner');

        return $this->membersBase()
            ->select(['members.*', 'project_role', 'expires_at'])
            ->selectRaw("project_role = '$roleOwner' as is_owner")
            ->where(function($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', Carbon::now());
            });
    }

    public function membersWithExpired(): HasManyThrough
    {
        $roleOwner = config('project-members-laravel.role_owner');

        return $this->membersBase()
            ->select(['members.*', 'project_role', 'expires_at'])
            ->selectRaw("project_role = '$roleOwner' as is_owner");
    }

    private function membersBase()
    {
        $memberModel = config('project-members-laravel.models.member');
        $projectMemberModel = config('project-members-laravel.models.project_member');

        return $this->hasManyThrough(
            $memberModel,
            $projectMemberModel,
            null,
            'id',
            null,
            'user_id'
        );
    }

    public function isOwner($member): bool
    {
        $membersTable = config('project-members-laravel.tables.members');
        $ownerIds = $this->owners()->select("$membersTable.*")->pluck('id', 'id');

        return Arr::exists($ownerIds, $member->id);
    }

    public function owners(): HasManyThrough
    {
        return $this->members()->where('project_role', 'owner');
    }

    public function owner()
    {
        return $this->owners()->first();
    }

    public function membership()
    {
        $projectMemberModel = config('project-members-laravel.models.project_member');
        return $this->hasOne($projectMemberModel);
    }

    public function getRoleAttribute()
    {
        return $this->membership->project_role;
    }

    public function hasRole(string $role): bool
    {
        return !strcasecmp($this->role, $role); // 0 is equal
    }

    public function invites(): HasMany
    {
        return $this->hasMany(ProjectInvite::class);
    }

    public function hasMember($member): bool
    {
        return null !== $this->members()->where('user_id', $member->id)->first();
    }

    public function hasMemberWithRole($member, array $roles): bool
    {
        return null !== $this->members()
                ->where('user_id', $member->id)
                ->whereIn('project_role', $roles)
                ->first();
    }
}
