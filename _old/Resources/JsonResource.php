<?php

declare(strict_types=1);

namespace Yormy\ProjectMembersLaravel\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource as BaseJsonResource;

class JsonResource extends BaseJsonResource
{
    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->withoutWrapping();
    }

    /** @psalm-suppress UndefinedInterfaceMethod */
    protected function getDates(array $fields): array
    {
        $parent = parent::toArray(Request());

        foreach ($fields as $field) {
            $dates[$field] = $parent[$field];
            $dates[$field.'_local'] = $parent[$field.'_local'];
            $dates[$field.'_human'] = $parent[$field.'_human'];
        }

        return $dates;
    }

    public function makeSearchable()
    {
        return ['dummy' => '@'];
    }
}
