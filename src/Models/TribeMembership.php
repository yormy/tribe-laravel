<?php

declare(strict_types=1);

namespace Yormy\TribeLaravel\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Yormy\TribeLaravel\Models\Scopes\MembershipScopeTrait;

/**
 * Yormy\TribeLaravel\Models\TribeMembership
 *
 * @method static \Illuminate\Database\Eloquent\Builder|TribeMembership active()
 * @method static \Illuminate\Database\Eloquent\Builder|TribeMembership invited()
 * @method static \Illuminate\Database\Eloquent\Builder|TribeMembership joined()
 * @method static \Illuminate\Database\Eloquent\Builder|TribeMembership member($member)
 * @method static \Illuminate\Database\Eloquent\Builder|TribeMembership newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TribeMembership newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TribeMembership notDeleted()
 * @method static \Illuminate\Database\Eloquent\Builder|TribeMembership notDisabled()
 * @method static \Illuminate\Database\Eloquent\Builder|TribeMembership notExpired()
 * @method static \Illuminate\Database\Eloquent\Builder|TribeMembership notJoined()
 * @method static \Illuminate\Database\Eloquent\Builder|TribeMembership onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TribeMembership project(\Yormy\TribeLaravel\Models\Project $project)
 * @method static \Illuminate\Database\Eloquent\Builder|TribeMembership query()
 * @method static \Illuminate\Database\Eloquent\Builder|TribeMembership role($role)
 * @method static \Illuminate\Database\Eloquent\Builder|TribeMembership withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TribeMembership withoutTrashed()
 *
 * @mixin \Eloquent
 */
class TribeMembership extends BaseModel
{
    use MembershipScopeTrait;
    use SoftDeletes;

    public $timestamps = false;

    protected $table = 'tribe_memberships';

    protected $fillable = [
        'member_id',
        'project_id',
        'role_id',
        'expires_at',
        'invited_by',
        'joined_at',
    ];
}
