<?php

declare(strict_types=1);

namespace Yormy\TribeLaravel\Observers\Events;

class TribeMembershipInvitedNonMemberEvent extends BaseProjectMemberEvent
{
    public function __construct(private $project, private $member, private $inviter)
    {
        parent::__construct($project, $member);
    }

    public function getInviter()
    {
        return $this->inviter;
    }
}
