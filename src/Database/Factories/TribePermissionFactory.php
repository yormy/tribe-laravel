<?php

namespace Yormy\TribeLaravel\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Yormy\TribeLaravel\Models\TribePermission;
use Yormy\TribeLaravel\Models\TribeRole;

class TribePermissionFactory extends Factory
{
    protected $model = TribePermission::class;

    public function definition()
    {
        return [
            'name' => $this->faker->firstName,
        ];
    }

    public function role(TribeRole $role): Factory
    {
        return $this->state(function (array $attributes) use ($role) {
            return [
                'role_id' => $role->id,
            ];
        });
    }
}
