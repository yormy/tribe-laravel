<?php declare(strict_types=1);

namespace Yormy\ProjectMembersLaravel\Rules;

use Yormy\LaravelValidation\Rules\Rule as BaseRule;
use Yormy\ProjectMembersLaravel\Services\ProjectService;

class ProjectApiKeyRule extends BaseRule
{
    public function passes($attribute, $value): bool
    {
        $this->setAttribute($attribute);

        $valid = ProjectService::validateProjectApiKey(
            config('project-members-laravel.api_encryption_key'),
            $value);

        return $valid;
    }

    /**
     * @return string
     */
    public function message(): string
    {
        $key = 'project-members-laravel::invalid-project-api-key';

        $message = (string)__(
            $key,
            [
                'attribute' => $this->attribute,
            ]
        );

        return $message;
    }
}

