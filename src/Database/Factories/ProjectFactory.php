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
//
//    public function marketing(): Factory
//    {
//        return $this->state(function (array $attributes) {
//            return [
//                'type' => UserTermType::MARKETING,
//            ];
//        });
//    }
//
//    public function general(): Factory
//    {
//        return $this->state(function (array $attributes) {
//            return [
//                'type' => UserTermType::GENERAL,
//            ];
//        });
//    }
//
//    public function active(): Factory
//    {
//        return $this->state(function (array $attributes) {
//            return [
//                'activated_at' => Carbon::now(),
//            ];
//        });
//    }
}
