<?php

declare(strict_types=1);

namespace Yormy\TribeLaravel\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Yormy\CoreToolsLaravel\Traits\Factories\PackageFactoryTrait;
use Yormy\TribeLaravel\Models\Scopes\MembershipScopeTrait;
use Yormy\Xid\Models\Traits\Xid;

/**
 * Yormy\TribeLaravel\Models\Project
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Yormy\TribeLaravel\Tests\Setup\Models\Member> $tribeMemberships
 * @property-read int|null $tribe_memberships_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Project active()
 * @method static \Yormy\TribeLaravel\Database\Factories\ProjectFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Project invited()
 * @method static \Illuminate\Database\Eloquent\Builder|Project joined()
 * @method static \Illuminate\Database\Eloquent\Builder|Project member($member)
 * @method static \Illuminate\Database\Eloquent\Builder|Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project notDeleted()
 * @method static \Illuminate\Database\Eloquent\Builder|Project notDisabled()
 * @method static \Illuminate\Database\Eloquent\Builder|Project notExpired()
 * @method static \Illuminate\Database\Eloquent\Builder|Project notJoined()
 * @method static \Illuminate\Database\Eloquent\Builder|Project onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Project project(\Yormy\TribeLaravel\Models\Project $project)
 * @method static \Illuminate\Database\Eloquent\Builder|Project query()
 * @method static \Illuminate\Database\Eloquent\Builder|Project role($role)
 * @method static \Illuminate\Database\Eloquent\Builder|Project withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Project withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Project extends BaseModel
{
    use MembershipScopeTrait;
    use PackageFactoryTrait;
    use SoftDeletes;
    use Xid;

    protected $table = 'tribe_projects';

    protected $fillable = [
        'xid',
        'email',
    ];

    public function tribeMemberships(): BelongsToMany
    {
        $memberClass = config('tribe.models.member');

        return $this->belongsToMany($memberClass, (new TribeMembership())->getTable())->withTimestamps();
    }
}
