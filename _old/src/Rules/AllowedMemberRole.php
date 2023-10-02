<?php declare(strict_types=1);

namespace Yormy\ProjectMembersLaravel\Rules;

use Mexion\BedrockCore\Exceptions\HackAttemptException;
use Yormy\LaravelValidation\Rules\Rule as BaseRule;
use Yormy\ProjectMembersLaravel\Services\ProjectService;
use Exception;

class AllowedMemberRole extends BaseRule
{
    public function __construct(?Exception $hackAttemptException = null)
    {
        parent::__construct($hackAttemptException);
    }

    public function passes($attribute, $value): bool
    {
        $this->setAttribute($attribute);

        $projectService = new ProjectService();
        $roleNames = $projectService->getSettableRoles();

        if (!array_key_exists($value, $roleNames)) {
            return $this->failed();
        }

        return true;
    }

    public function message(): string
    {
        $key = 'multilingual-admin::validation.role_invalid';

        $message = (string)__(
            $key,
            [
                'attribute' => $this->attribute,
            ]
        );

        return $message;
    }
}

