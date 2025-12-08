<?php

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
            'cargoDiputado' => $this->faker->boolean(),
            'cargoSenador' => $this->faker->boolean(),
            'idProvincia' => Provincia::factory(),
        ];
    }
}
