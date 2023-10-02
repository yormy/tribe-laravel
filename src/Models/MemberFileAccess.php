<?php

namespace Yormy\TribeLaravel\Models;

class MemberFileAccess extends BaseModel
{
    protected $table = 'member_files_access';

    protected $fillable = [
        'member_file_id',
        'user_id',
        'user_type',
        'as_download',
        'as_view',
        'ip',
        'useragent',
        'as_download',
        'as_view',
    ];

    protected $casts = [
        'as_download' => 'boolean',
        'as_view' => 'boolean',
    ];
}
