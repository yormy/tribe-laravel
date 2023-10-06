<?php

use Yormy\TribeLaravel\Models\Project;
use Yormy\TribeLaravel\Models\TribeRole;
use Yormy\TribeLaravel\Services\Resolvers\IpResolver;
use Yormy\TribeLaravel\Services\Resolvers\UserAgentResolver;
use Yormy\TribeLaravel\Services\Resolvers\UserResolver;
use Yormy\TribeLaravel\Tests\Setup\Models\Member;

return [
    /*
    |--------------------------------------------------------------------------
    | Api Encryption key
    |--------------------------------------------------------------------------
    | This key is only used to create a self-validating api project token
    | It allows us to verify the api token passed in without having to go to the database
    */
    'api_encryption_key' => 'base64:OsDjKLg2PRnIUqQQbN7Jw/KjzQ8AOmB5YQGR/F/5yTY=',

    'default_expire_membership_months' => 12,

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    |
    */
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
    'models' => [
        'member' => Member::class,
        'project' => Project::class,
        'role' => TribeRole::class,
    ],
];
