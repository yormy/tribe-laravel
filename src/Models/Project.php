<?php

namespace Yormy\TribeLaravel\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Yormy\TribeLaravel\Domain\Upload\DataObjects\Enums\MimeTypeEnum;
use Yormy\Xid\Models\Traits\Xid;

class Project extends BaseModel
{
    use SoftDeletes;
    use Xid;

    protected $table = 'tribe_projects';

    protected $fillable = [
        'xid',
        'email',
    ];
}
