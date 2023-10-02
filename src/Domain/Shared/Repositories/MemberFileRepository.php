<?php

declare(strict_types=1);

namespace Yormy\TribeLaravel\Domain\Shared\Repositories;

use Yormy\TribeLaravel\Domain\Shared\Models\MemberFile;

class MemberFileRepository
{
    public function __construct(private ?MemberFile $model = null)
    {
        if (! $model) {
            $this->model = new MemberFile();
        }
    }

    public function create(array $defaults): MemberFile
    {
        return $this->model->create($defaults);
    }

    public function update(MemberFile $model, array $data): MemberFile
    {
        $model->update($data);

        return $model;
    }
}
