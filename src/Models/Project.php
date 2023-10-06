<?php

namespace Yormy\TribeLaravel\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Yormy\CoreToolsLaravel\Traits\Factories\PackageFactoryTrait;
use Yormy\TribeLaravel\Models\Scopes\MembershipScopeTrait;
use Yormy\Xid\Models\Traits\Xid;

class Project extends BaseModel
{
    use SoftDeletes;
    use Xid;
    use PackageFactoryTrait;
    use MembershipScopeTrait;

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
