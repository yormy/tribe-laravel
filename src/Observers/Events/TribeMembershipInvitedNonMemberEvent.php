<?php

namespace Yormy\TribeLaravel\Observers\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TribeMembershipInvitedNonMemberEven
{
    use Dispatchable;
    use SerializesModels;
    public function __construct(private $project, private readonly string $email)
    {
        // ...
    }

    public function getProject()
    {
        return $this->project;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
