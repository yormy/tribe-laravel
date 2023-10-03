<?php

namespace Yormy\TribeLaravel\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Yormy\CoreToolsLaravel\Traits\Factories\PackageFactoryTrait;
use Yormy\TribeLaravel\Domain\Upload\DataObjects\Enums\MimeTypeEnum;
use Yormy\Xid\Models\Traits\Xid;

class ProjectRole extends BaseModel
{
    use PackageFactoryTrait;

    protected $table = 'tribe_roles';

    public $timestamps = false;

    protected $fillable = [
        'code',
        'name',
    ];

    public function permissions()
    {
        return $this->hasMany(TribePermission::class, 'role_id');
    }
}
