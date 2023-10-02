<?php

namespace Yormy\TribeLaravel\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Yormy\Xid\Models\Traits\Xid;

class Member extends BaseModel
{
    use SoftDeletes;
    use Xid;

    protected $table = 'test_members';

    protected $fillable = [
        'xid',
        'email',
    ];
}
