<?php declare(strict_types=1);

namespace Yormy\ProjectMembersLaravel\Rules;

use Illuminate\Support\Facades\Auth;
use Mexion\BedrockCore\Exceptions\HackAttemptException;
use Yormy\LaravelValidation\Rules\Rule as BaseRule;
use Exception;

class ProjectOwnerRule extends BaseRule
{
    public function __construct(?Exception $hackAttemptException = null)
    {
        parent::__construct($hackAttemptException);
    }

    /** @psalm-suppress ParamNameMismatch */
    public function passes($attribute, $project): bool
    {
        $this->setAttribute($attribute);

        if (!$project || !$project->isOwner(Auth::user())) {
            return $this->fail($project);
        }

        return true;
    }
}
