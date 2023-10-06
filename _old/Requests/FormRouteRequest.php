<?php

namespace Yormy\ProjectMembersLaravel\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class FormRouteRequest extends FormRequest
{
    protected $routeParamsToValidate = [];

    protected $queryParamsToValidate = [];

    public function rules()
    {
        if ($this->method() == 'GET') {
            return $this->getRequest();
        }

        if ($this->method() == 'POST') {
            return $this->postRequest();
        }

        if ($this->method() == 'PUT') {
            return $this->putRequest();
        }

        if ($this->method() == 'DELETE') {
            return $this->deleteRequest();
        }
    }

    public function getRequest(): array
    {
        return [];
    }

    public function postRequest(): array
    {
        return [];
    }

    public function putRequest(): array
    {
        return [];
    }

    public function deleteRequest(): array
    {
        return [];
    }

    public function all($keys = null)
    {
        $data = parent::all();

        foreach ($this->routeParamsToValidate as $validationDataKey => $routeParameter) {
            if ($this->route($routeParameter)) {
                $data[$validationDataKey] = $this->route($routeParameter);
            }
        }

        foreach ($this->queryParamsToValidate as $validationDataKey => $queryParameter) {
            if ($this->query($queryParameter)) {
                $data[$validationDataKey] = $this->query($queryParameter);
            }
        }

        return $data;
    }
}
