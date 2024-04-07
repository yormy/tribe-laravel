<?php

declare(strict_types=1);

namespace Yormy\ProjectMembersLaravel\Http\Requests;

class BaseRequest extends FormRouteRequest
{
    protected $routeParamsToValidate = [
        'i18n' => 'i18n',
    ];

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules['i18n'] = [
            'required',
            'in:'.implode(',', array_keys(config('multilingual-admin.locales'))),
        ];

        $rules['publicId'] = 'string|min:1';

        $allRules = [];

        if ($this->getMethod() === 'POST') {
            $createRules = $this->createValidation();
            $allRules = array_merge($rules, $createRules);
        }

        if ($this->getMethod() === 'PUT') {
            $updateRules = $this->updateValidation();
            $allRules = array_merge($rules, $updateRules);
        }

        return $allRules;
    }

    private function createValidation()
    {
        return [];
    }

    private function updateValidation()
    {
        return [];
    }
}
