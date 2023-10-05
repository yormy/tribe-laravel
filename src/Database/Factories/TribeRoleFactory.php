<?php

namespace Yormy\TribeLaravel\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Yormy\TribeLaravel\Models\Project;
use Yormy\TribeLaravel\Models\TribeRole;

class TribeRoleFactory extends Factory
{
    protected $model = TribeRole::class;

    public function definition()
    {
        return [
            'name' => 'default',
        ];
    }


    public function project(Project $project): Factory
    {
        return $this->state(function (array $attributes) use ($project) {
            return [
                'project_id' => $project->id,
            ];
        });
    }

}
