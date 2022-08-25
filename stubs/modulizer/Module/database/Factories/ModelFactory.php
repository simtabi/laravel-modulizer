<?php

namespace Simtabi\Modules\{Module}\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Simtabi\Modules\{Module}\Models\{Model};

class {Model}Factory extends Factory
{
    protected $model = {Model}::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name()
        ];
    }
}