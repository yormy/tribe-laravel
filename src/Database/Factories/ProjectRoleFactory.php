<?php

namespace Yormy\TribeLaravel\Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Yormy\TribeLaravel\Models\Project;
use Yormy\TribeLaravel\Models\ProjectRole;
use Yormy\Xid\Services\XidService;

class ProjectRoleFactory extends Factory
{
    protected $model = ProjectRole::class;

    public function definition()
    {
        return [
            'name' => $this->faker->firstName,
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
