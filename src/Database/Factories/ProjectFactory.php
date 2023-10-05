<?php

namespace Yormy\TribeLaravel\Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Yormy\TribeLaravel\Models\Project;
use Yormy\Xid\Services\XidService;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition()
    {
        return [
            'xid' => XidService::generate(),
            'name' => $this->faker->firstName,
        ];
    }

    public function disabled(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'disabled_at' => Carbon::now()->subHours(2)
            ];
        });
    }

}
