<?php

namespace Database\Factories;

use App\Models\Resultado;
use App\Models\Lista;
use App\Models\Telegrama;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResultadoFactory extends Factory
{
    protected $model = Resultado::class;

    public function definition(): array
    {
        return [
            'votos' => $this->faker->numberBetween(0, 500),
            'porcentaje' => $this->faker->randomFloat(2, 0, 100), // Decimal entre 0.00 y 100.00
            'idLista' => Lista::factory(),
            'idTelegrama' => Telegrama::factory(),
        ];
    }
}