<?php

namespace Yormy\TribeLaravel\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Yormy\CoreToolsLaravel\Traits\Factories\PackageFactoryTrait;
use Yormy\TribeLaravel\Domain\Upload\DataObjects\Enums\MimeTypeEnum;
use Yormy\Xid\Models\Traits\Xid;

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
