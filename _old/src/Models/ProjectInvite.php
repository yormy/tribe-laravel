<?php

namespace Yormy\ProjectMembersLaravel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ProjectInvite extends Model
{
    protected $table = 'project_invites';

    protected $fillable = [
        'project_id',
        'user_id',
        'email',
        'invited_by'
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->accept_token = md5(uniqid(microtime(), true));
            $model->deny_token = md5(uniqid(microtime(), true));
        });
    }

    public function project()
    {
        $projectModel = config('project-members-laravel.models.project');
        return $this
            ->belongsTo($projectModel)
            ->withoutGlobalScopes();
    }
}
