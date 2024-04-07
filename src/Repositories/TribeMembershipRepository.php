<?php

declare(strict_types=1);

namespace Yormy\TribeLaravel\Repositories;

use Carbon\CarbonImmutable;
use Yormy\TribeLaravel\Models\TribeMembership;
use Yormy\TribeLaravel\Models\TribeRole;

class TribeMembershipRepository
{
    public function setRole(TribeMembership $membership, TribeRole $role, ?CarbonImmutable $expiresAt = null): void
    {
        $membership->role_id = $role->id;

        if ($expiresAt) {
            $membership->expires_at = $expiresAt;
        }

        $membership->save();
    }
}
