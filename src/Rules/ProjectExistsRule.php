<?php

declare(strict_types=1);

namespace Yormy\TribeLaravel\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Yormy\TribeLaravel\Repositories\ProjectRepository;

class ProjectExistsRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $projectXid = $value;

        $projectRepository = new ProjectRepository();
        $project = $projectRepository->findOneByXid($projectXid);
        if (! $project) {
            $fail('xid.message.invalid');
        }
    }
}
