<?php

namespace Yormy\TribeLaravel\Tests\Traits;

use Facades\Yormy\TribeLaravel\Domain\Encryption\FileVault;
use Yormy\TribeLaravel\Tests\Setup\Models\User;

trait UserTrait
{
    private function createUser()
    {
        $user = User::create([
            'email' => 'test@exampel.com',
            'encryption_key' => 'base64:'.base64_encode(FileVault::generateKey()),
        ]);

        return $user;
    }
}
