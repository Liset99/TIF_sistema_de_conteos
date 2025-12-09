<?php

namespace Database\Factories;

use App\Models\Lista;
use App\Models\Provincia;
use App\Models\Telegrama;
use App\Models\Resultado;
use Illuminate\Database\Eloquent\Factories\Factory;

class ListaFactory extends Factory
{
    protected $model = Lista::class;

    public function definition(): array
    {
        return [
            'nombre' => $this->faker->word() . ' ' . $this->faker->word(),
            'idProvincia' => Provincia::factory(),
        ];
    }
}