<?php declare(strict_types=1);

namespace Yormy\ProjectMembersLaravel\Rules;

use Yormy\LaravelValidation\Rules\Rule as BaseRule;

class ProjectActiveRule extends BaseRule
{
    public function passes($attribute, $value): bool
    {
        $this->setAttribute($attribute);

        if (!$value->isActive()) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function message(): string
    {
        $key = 'multilingual-admin::validation.project_inactive';

        $message = (string)__(
            $key,
            [
                'attribute' => $this->attribute,
            ]
        );

        return $message;
    }
}

