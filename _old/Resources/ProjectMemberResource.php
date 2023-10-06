<?php

namespace Yormy\ProjectMembersLaravel\Http\Resources;

use Illuminate\Support\Carbon;
use Yormy\ProjectMembersLaravel\Helpers\DateHelper;

class ProjectMemberResource extends JsonResource
{
    public function toArray($request)
    {
        /**
         * @psalm-suppress NullArgument
         */
        $fields = [
            'xid' => $this->xid,
            'name' => $this->name,
            'email' => $this->email,
            'project_role' => $this->project_role,
            'is_owner' => $this->is_owner,
            'expires_at' => Datehelper::formatDateTimeFromString($this->expires_at),
            'is_expired' => $this->isExpired($this->expires_at),
        ];

        $dates = $this->getDates([
            'created_at',
        ]);

        return array_merge($fields, $dates, $this->makeSearchable());
    }

    private function isExpired($date): bool
    {
        if (! $date) {
            return false;
        }

        return Carbon::now()->gt(Carbon::parse($date));
    }
}
