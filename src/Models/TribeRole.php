<?php

declare(strict_types=1);

namespace Yormy\TribeLaravel\Models;

use Yormy\CoreToolsLaravel\Traits\Factories\PackageFactoryTrait;

/**
 * Yormy\TribeLaravel\Models\TribeRole
 *
 * @method static \Yormy\TribeLaravel\Database\Factories\TribeRoleFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|TribeRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TribeRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TribeRole query()
 *
 * @mixin \Eloquent
 */
class TribeRole extends BaseModel
{
    use PackageFactoryTrait;

    public $timestamps = false;

    protected $table = 'tribe_roles';

    protected $fillable = [
        'code',
        'name',
    ];
    //
    //    public function permissions()
    //    {
    //        return $this->hasMany(TribePermission::class, 'role_id');
    //    }
}
