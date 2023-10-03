<?php

namespace Yormy\TribeLaravel\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Yormy\TribeLaravel\Models\Project;
use Yormy\TribeLaravel\Models\ProjectRole;

class ProjectRoleFactory extends Factory
{
    protected $model = ProjectRole::class;

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
