<?php

namespace Yormy\TribeLaravel\Tests\Setup\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Yormy\TribeLaravel\Models\BaseModel;
use Yormy\TribeLaravel\Models\Project;
use Yormy\TribeLaravel\Models\ProjectMember;
use Yormy\TribeLaravel\Models\ProjectRole;
use Yormy\TribeLaravel\Models\TribePermission;
use Yormy\Xid\Models\Traits\Xid;

class Member extends Authenticatable
{
    use SoftDeletes;

    protected $table = 'test_members';

    protected $fillable = [
        'email',
    ];

//    public function projects(): BelongsToMany
//    {
//        $memberClass = config('tribe.models.member');
//
//        return $this->belongsToMany(Project::class, (new ProjectMember())->getTable());
//    }
//
//    public function projectRoles()
//    {
//        return $this->hasManyThrough(ProjectRole::class, Project::class, 'id');
//    }

//
//    public function tribePermissions()
//    {
//        $memberClass = config('tribe.models.member');
//
//        return $this->hasManyThrough(TribePermission::class);
//    }
}
