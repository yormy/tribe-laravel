<?php

namespace Yormy\ProjectMembersLaravel\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class MemberOfProjectScope implements Scope
{
    private Model $project;
    public function __construct(Model $project)
    {
        $this->project = $project;
    }

    public function apply(Builder $builder, Model $model) : Builder
    {
        $projectTable = $this->project->getTable();

        return $builder->whereIn("$projectTable.id", function ($query) {
            $user = Auth::user();

            $projectMembersTable = config('project-members-laravel.tables.project_members');

            // silent fail when not logged in
            if (!$user) {
                $query
                    ->select('project_id')
                    ->from($projectMembersTable)
                    ->where("user_id", '<', 0);
            } else {
                $query
                    ->select('project_id')
                    ->from($projectMembersTable)
                    ->whereNull('deleted_at')
                    ->where("user_id", '=', $user->id)
                    ->where(function($query) {
                        $query->whereNull('expires_at')
                            ->orWhere('expires_at', '>', Carbon::now());
                    });
            }
        });
    }
}
