<?php

namespace Yormy\TribeLaravel\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Yormy\CoreToolsLaravel\Traits\Factories\PackageFactoryTrait;
use Yormy\TribeLaravel\Domain\Upload\DataObjects\Enums\MimeTypeEnum;
use Yormy\TribeLaravel\Models\Scopes\DateScopeTrait;
use Yormy\TribeLaravel\Models\Scopes\MembershipScopeTrait;
use Yormy\Xid\Models\Traits\Xid;

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
