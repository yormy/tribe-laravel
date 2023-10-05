<?php

declare(strict_types=1);

namespace Yormy\TribeLaravel\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;
use Yormy\TribeLaravel\Repositories\ProjectRepository;
use Yormy\Xid\Observers\Events\XidInvalidEvent;
use Yormy\Xid\Services\XidService;

class ProjectActiveRule implements ValidationRule
{

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $projectXid = $value;

        $projectRepository = new ProjectRepository();
        $project = $projectRepository->findOneActiveByXid($projectXid);

        if (!$project) {
            $fail('xid.message.invalid');
        }
    }
}
