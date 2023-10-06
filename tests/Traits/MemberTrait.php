<?php

namespace Yormy\TribeLaravel\Tests\Traits;

use Yormy\TribeLaravel\Tests\Setup\Models\Member;

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
