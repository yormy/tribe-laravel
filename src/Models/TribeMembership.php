<?php

namespace Yormy\TribeLaravel\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Yormy\CoreToolsLaravel\Traits\Factories\PackageFactoryTrait;
use Yormy\TribeLaravel\Models\Scopes\MembershipScopeTrait;

class TribeMembership extends BaseModel
{
    use SoftDeletes;
    use PackageFactoryTrait;
    use MembershipScopeTrait;

    protected $table = 'tribe_memberships';

    public $timestamps = false;

    protected $fillable = [
        'member_id',
        'project_id',
        'role_id',
        'expires_at',
        'invited_by',
        'joined_at',
    ];
}
