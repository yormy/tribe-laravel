<?php

namespace Yormy\TribeLaravel\Models;

use Yormy\CoreToolsLaravel\Traits\Factories\PackageFactoryTrait;

/**
 * Yormy\TribeLaravel\Models\TribePermission
 *
 * @method static \Yormy\TribeLaravel\Database\Factories\TribePermissionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|TribePermission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TribePermission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TribePermission query()
 * @mixin \Eloquent
 */
class TribePermission extends BaseModel
{
    use PackageFactoryTrait;

    protected $table = 'tribe_permissions';

    public $timestamps = false;

    protected $fillable = [
        'code',
        'name',
    ];
}
