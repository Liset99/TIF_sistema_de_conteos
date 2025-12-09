<?php

namespace Database\Factories;

use App\Models\Resultado; 
use App\Models\Telegrama;
use App\Models\Lista;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResultadoFactory extends Factory
{
    protected $model = Resultado::class;

    public function definition(): array
    {
        return [
            'votos' => $this->faker->numberBetween(0, 500),
            'porcentaje' => $this->faker->randomFloat(2, 0, 100),
            'idTelegrama' => Telegrama::factory(),
            'idLista' => function () {
                return Lista::factory()->create()->idLista;
            },
        ];
    }
}
