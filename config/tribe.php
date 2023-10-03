<?php

use Yormy\TribeLaravel\Models\Project;
use Yormy\TribeLaravel\Models\ProjectRole;
use Yormy\TribeLaravel\Services\Resolvers\IpResolver;
use Yormy\TribeLaravel\Services\Resolvers\UserAgentResolver;
use Yormy\TribeLaravel\Services\Resolvers\UserResolver;
use Yormy\TribeLaravel\Tests\Setup\Models\Member;

return [
    'resolvers' => [
        'ip' => IpResolver::class,
        'user' => UserResolver::class,
        'useragent' => UserAgentResolver::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    |
    */
    "models" => [
        'member' => Member::class,
        'project' => Project::class,
        'role' => ProjectRole::class,
    ],
];
