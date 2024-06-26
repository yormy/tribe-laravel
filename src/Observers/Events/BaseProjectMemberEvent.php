<?php

declare(strict_types=1);

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

    public function getProjectName()
    {
        return $this->project->name;
    }

    public function getMember()
    {
        return $this->member;
    }
}
