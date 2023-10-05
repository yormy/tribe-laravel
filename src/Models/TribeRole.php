<?php

namespace Yormy\TribeLaravel\Models;

use Yormy\CoreToolsLaravel\Traits\Factories\PackageFactoryTrait;

class TribeRole extends BaseModel
{
    use PackageFactoryTrait;

    protected $table = 'tribe_roles';

    public $timestamps = false;

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
