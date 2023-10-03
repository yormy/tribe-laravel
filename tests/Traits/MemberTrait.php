<?php

namespace Yormy\TribeLaravel\Tests\Traits;

use Facades\Yormy\TribeLaravel\Domain\Encryption\FileVault;
use Yormy\TribeLaravel\Tests\Setup\Models\Member;
use Yormy\TribeLaravel\Tests\Setup\Models\User;

trait MemberTrait
{
    private function createMember()
    {
        $user = Member::create([
            'email' => 'test@exampel.com',
        ]);

        return $user;
    }
}
