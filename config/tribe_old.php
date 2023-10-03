<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Table definitions
    |--------------------------------------------------------------------------
    |
    */
    "tables" => [
        'projects' => 'tc_projects',
        'project_members' => 'project_members',
        'members' => 'members',
        'project_invites' => 'project_invites',
        'project_whitelisted_ips' => 'project_whitelisted_ips',
    ],

    /*
    |--------------------------------------------------------------------------
    | User Foreign key on project_members Table (Pivot)
    |--------------------------------------------------------------------------
    */
    'user_foreign_key' => 'id',

    /*
    |--------------------------------------------------------------------------
    | Role name for the owner.
    | This is the key that is written to the database and used to determine
    | ownership and ownership rights (as in member administration)
    |--------------------------------------------------------------------------
    */
    'role_owner' => 'OWNER',

    /*
    |--------------------------------------------------------------------------
    | Role names and their visible value
    | The key is written to the database and used to determine the actual role
    | the value is used to display the translatable text to the user
    |--------------------------------------------------------------------------
    */
    'role_names' => [
        'OWNER' => 'Ownie',
        'TRANSLATOR' => 'Translatie',
        'HACKER' => 'hackie',
    ],



    /*
    |--------------------------------------------------------------------------
    | Api Encryption key
    |--------------------------------------------------------------------------
    | This key is only used to create a self-validating api project token
    | It allows us to verify the api token passed in without having to go to the database
    */
    "api_encryption_key" => "base64:OsDjKLg2PRnIUqQQbN7Jw/KjzQ8AOmB5YQGR/F/5yTY="

];

