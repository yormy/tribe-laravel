<?php declare(strict_types=1);

namespace Yormy\ProjectMembersLaravel\Rules;

use Exception;
use Mexion\BedrockUsers\Models\Member;
use Yormy\LaravelValidation\Rules\Rule as BaseRule;

class ProjectHasMemberRule extends BaseRule
{
    private Member $member;

    public function __construct(Member $member, ?Exception $hackAttemptException = null)
    {
        $this->member = $member;

        parent::__construct($hackAttemptException);

    }

    /** @psalm-suppress ParamNameMismatch */
    public function passes($attribute, $project): bool
    {
        $this->setAttribute($attribute);

        if (!$project->hasMember($this->member)) {
            return $this->failed();
        }

        return true;
    }
}

