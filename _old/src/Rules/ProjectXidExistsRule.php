<?php declare(strict_types=1);

namespace Yormy\ProjectMembersLaravel\Rules;

use Exception;
use Yormy\LaravelValidation\Rules\Rule as BaseRule;

class ProjectXidExistsRule extends BaseRule
{
    private $project;

    public function __construct($project, ?Exception $hackAttemptException = null)
    {
        $this->project = $project;
        parent::__construct($hackAttemptException);
    }

    /** @psalm-suppress ParamNameMismatch */
    public function passes($attribute, $projectXid): bool
    {
        $this->setAttribute($attribute);

        $project = (new $this->project())->where('xid', $projectXid)
            ->withoutGlobalScopes()
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
        $key = 'xxx::validation.project';

        $message = (string)__(
            $key,
            [
                'attribute' => $this->attribute,
            ]
        );

        return $message;
    }
}

