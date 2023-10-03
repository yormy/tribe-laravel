<?php declare(strict_types=1);

namespace Yormy\ProjectMembersLaravel\Rules;

use Exception;
use Yormy\LaravelValidation\Rules\Rule as BaseRule;

class ProjectValidKeyAndProjectIdRule extends BaseRule
{
    private $project;

    private ?string $projectApiKey;

    public function __construct(
        $project,
        ?string $projectApiKey = null,
        ?Exception $hackAttemptException = null
    ) {
        $this->project = $project;
        $this->projectApiKey = $projectApiKey;

        parent::__construct($hackAttemptException);
    }

    /** @psalm-suppress ParamNameMismatch */
    public function passes($attribute, $projectXid): bool
    {
        if (!$this->projectApiKey) {
            $this->hackAttemptException = null; // no exception in this case
            return $this->failed();
        }

        $this->setAttribute($attribute);

        $project = (new $this->project())
            ->where('xid', $projectXid)
            ->where('api_submit_key', $this->projectApiKey)
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

