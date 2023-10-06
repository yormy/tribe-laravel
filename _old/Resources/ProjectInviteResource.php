<?php

namespace Yormy\ProjectMembersLaravel\Http\Resources;

use Yormy\ProjectMembersLaravel\Helpers\DateHelper;

class ProjectInviteResource extends JsonResource
{
    public function toArray($request)
    {
        $fields = [
            'email' => $this->email,
            'deny_token' => $this->deny_token,
            'created_at' => Datehelper::formatDateTime($this->created_at),
        ];

        return array_merge($fields, $this->makeSearchable());
    }
}
