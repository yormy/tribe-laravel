<?php

namespace Yormy\ProjectMembersLaravel\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProjectInviteCollection extends ResourceCollection
{
    public $collects = ProjectInviteResource::class;

    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
