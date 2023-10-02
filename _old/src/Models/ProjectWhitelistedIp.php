<?php

namespace Yormy\ProjectMembersLaravel\Models;

use Mexion\BedrockCore\Models\Model;
use Yormy\Dateformatter\Models\Traits\DateFormatter;
use Yormy\Xid\Models\Traits\Xid;

class ProjectWhitelistedIp extends Model
{
    use Xid;
    use DateFormatter;

    protected $table = '';

    protected $fillable = [
        'xid',
        'name',
        'comment',
        'project_id',
        'ip_address'
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = config('project-members-laravel.project_whitelisted_ip');
        parent::__construct($attributes);
    }

    public function getRouteKeyName()
    {
        return 'xid';
    }
}
