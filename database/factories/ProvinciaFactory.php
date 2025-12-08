<?php

namespace Database\Factories;

use App\Models\Provincia;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProvinciaFactory extends Factory
{
    protected $model = Provincia::class;

    public function definition(): array
    {
        return [
            'nombre' => $this->faker->unique()->state() . ' ' . $this->faker->numberBetween(1, 100),
        ];
    }
}