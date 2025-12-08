<?php

namespace Database\Factories;

use App\Models\Mesa;
use App\Models\Provincia;
use Illuminate\Database\Eloquent\Factories\Factory;

class MesaFactory extends Factory
{
    protected $model = Mesa::class;

    public function definition(): array
    {
        return [
            'electores' => $this->faker->numberBetween(1, 500),
            'establecimiento' => 'Escuela ' . $this->faker->company(),
            'circuito' => 'Circuito ' . $this->faker->numberBetween(1, 100),
            'idProvincia' => Provincia::factory(),
        ];
    }
}