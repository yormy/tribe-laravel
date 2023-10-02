<?php

namespace Yormy\ProjectMembersLaravel\Http\Resources;

class ProjectWhitelistedIpResource extends JsonResource
{
    public function toArray($request)
    {
        $fields = [
            'xid' => $this->xid,
            'comment' => $this->comment,
            'ip_address' => $this->ip_address,
        ];

        $dates = $this->getDates([
            'created_at',
        ]);

        return array_merge($fields, $dates, $this->makeSearchable());
    }

}
