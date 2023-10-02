<?php

use Yormy\TribeLaravel\Domain\Shared\Services\Resolvers\IpResolver;
use Yormy\TribeLaravel\Domain\Shared\Services\Resolvers\UserAgentResolver;
use Yormy\TribeLaravel\Domain\Shared\Services\Resolvers\UserResolver;
use Yormy\TribeLaravel\Domain\Upload\DataObjects\Enums\MimeTypeEnum;
use Yormy\TribeLaravel\Models\Member;
use Yormy\TribeLaravel\Models\Project;

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
    ],
];
