<?php

declare(strict_types=1);

namespace Yormy\TribeLaravel\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Yormy\TribeLaravel\Repositories\ProjectRepository;
use Yormy\TribeLaravel\Services\TokenService;

class ProjectApiKeyRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $apiKey = $value;

        if (! TokenService::validate($apiKey)) {
            $fail('xid.message.invalid');

            return;
        }

        $projectRepository = new ProjectRepository();
        $project = $projectRepository->findOneActiveByApiKey($apiKey);
        if (! $project) {
            $fail('xid.message.invalid');

            return;
        }
    }
}
