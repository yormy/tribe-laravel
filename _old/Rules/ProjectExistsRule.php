<?php declare(strict_types=1);

namespace Yormy\ProjectMembersLaravel\Rules;

use Mexion\BedrockCore\Exceptions\HackAttemptException;
use Yormy\LaravelValidation\Rules\Rule as BaseRule;

class ProjectExistsRule extends BaseRule
{
    /** @psalm-suppress ParamNameMismatch */
    public function passes($attribute, $project): bool
    {
        $this->setAttribute($attribute);

        $projectModel = config('project-members-laravel.models.project');
        $project = (new $projectModel)->where('xid', $project->xid)
            ->first();

        if (!$project) {
            return $this->failed();
        }

        return true;
    }

    /**
     * @return string
     */
    public function message(): string
    {
        $key = 'multilingual-admin::validation.project';

        $message = (string)__(
            $key,
            [
                'attribute' => $this->attribute,
            ]
        );

        return $message;
    }
}

