<?php

// Configuro ListaFactory para tests unitarios

namespace Database\Factories;

use App\Models\Lista;
use App\Models\Provincia;
use Illuminate\Database\Eloquent\Factories\Factory;

class ListaFactory extends Factory
{
    protected $model = Lista::class;

    public function definition(): array
    {
        return [
            'nombre' => $this->faker->company() . ' - Lista ' . $this->faker->numberBetween(1, 100),
            'alianza' => $this->faker->optional()->company(),
            'cargoDiputado' => $this->faker->randomElement(['true', 'false', '1', '0']),
            'cargoSenador' => $this->faker->randomElement(['true', 'false', '1', '0']),
            'idProvincia' => Provincia::factory(),
        ];
    }
}
