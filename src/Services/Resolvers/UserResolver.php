<?php

declare(strict_types=1);

namespace Yormy\TribeLaravel\Services\Resolvers;

use Illuminate\Support\Facades\Auth;

class UserResolver
{
    public static function get()
    {
        return Auth::user();
    }
}
