<?php

namespace Yormy\TribeLaravel\Observers\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

abstract class BaseProjectMemberEvent
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(private $project, private $member)
    {
        // ...
    }

    public function getProject()
    {
        return $this->project;
    }

    public function getMember()
    {
        return $this->member;
    }
}
