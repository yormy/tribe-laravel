<?php

namespace Yormy\ProjectMembersLaravel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectMember extends Model
{
    use SoftDeletes;

    protected $table = 'project_members';

    protected $fillable = [
        'project_id',
        'user_id',
        'project_role',
        'expires_at'
    ];

    protected $dates = [
        'expires_at'
    ];

    public function isOwner()
    {
        return $this->project_role === config('project-members-laravel.role_owner');
    }

}
