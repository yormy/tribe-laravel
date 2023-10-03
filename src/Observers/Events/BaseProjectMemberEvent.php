<?php

namespace Yormy\TribeLaravel\Observers\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

abstract class BaseProjectMemberEvent
{
    use Dispatchable;
    use SerializesModels;

    protected $project;

    protected $member;


    public function __construct($project, $member)
    {
        $this->project = $project;
        $this->member = $member;
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