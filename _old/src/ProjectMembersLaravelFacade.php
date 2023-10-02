<?php

namespace Yormy\ProjectMembersLaravel;

use Illuminate\Support\Facades\Facade;

class ProjectMembersLaravelFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ProjectMembersLaravel::class;
    }
}
