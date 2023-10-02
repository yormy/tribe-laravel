<?php

declare(strict_types=1);

namespace Yormy\TribeLaravel\Domain\Shared\Services\Resolvers;

use Illuminate\Support\Facades\Auth;

class UserResolver
{
    public static function get()
    {
        $user = Auth::user();

        return $user;
    }
}
