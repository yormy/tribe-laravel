<?php

declare(strict_types=1);

namespace Yormy\ProjectMembersLaravel\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProjectWhitelistedIpCollection extends ResourceCollection
{
    public $collects = ProjectWhitelistedIpResource::class;

    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
