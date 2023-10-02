<?php declare(strict_types=1);

namespace Yormy\ProjectMembersLaravel\Rules;

use Yormy\LaravelValidation\Rules\Rule as BaseRule;
use Exception;

class AllowManageMembersRule extends BaseRule
{
    private $member;

    public function __construct($member, ?Exception $hackAttemptException = null)
    {
        $this->member = $member;

        parent::__construct($hackAttemptException);

    }

    /** @psalm-suppress ParamNameMismatch */
    public function passes($attribute, $project): bool
    {
        $this->setAttribute($attribute);

        if (!$project->hasMemberWithRole($this->member, ['owner'])) {
            return $this->failed();
        }

        return true;
    }
}

