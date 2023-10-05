<?php declare(strict_types=1);

namespace Yormy\TribeLaravel\Models\Scopes;
use Carbon\Carbon;
use Yormy\TribeLaravel\Models\Project;

trait MembershipScopeTrait
{
    public function scopeProject($query, Project $project)
    {
        return $query
            ->where('project_id', '=', $project->id);
    }

    public function scopeMember($query, $member)
    {
        return $query
            ->where('member_id', '=', $member->id);
    }

    public function scopeNotExpired($query)
    {
        return $query
            ->where('expires_at', '>=', Carbon::now());
    }

    public function scopeJoined($query)
    {
        return $query
            ->whereNotNull('joined_at');
    }

    public function scopeInvited($query)
    {
        return $query
            ->whereNull('joined_at');
    }

    public function scopeNotDeleted($query)
    {
        return $query
            ->whereNull('deleted_at');
    }

    public function scopeActive($query)
    {
        $query = $this->scopeJoined($query);
        $query = $this->scopeNotExpired($query);
        $query = $this->scopeNotDeleted($query);

       return $query;
    }
}
