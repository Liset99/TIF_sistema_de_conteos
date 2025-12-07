<?php

namespace Database\Factories;

use App\Models\Candidato;
use App\Models\Lista;
use Illuminate\Database\Eloquent\Factories\Factory;

class CandidatoFactory extends Factory
{
    protected $model = Candidato::class;

    public function definition(): array
    {
        return [
            'cargo' => $this->faker->randomElement(['DIPUTADOS', 'SENADORES']),
            'ordenEnLista' => $this->faker->numberBetween(1, 50),
            'nombre' => $this->faker->firstName(),
            'apellido' => $this->faker->lastName(),
            'idLista' => Lista::factory(), // Crea una lista automáticamente
        ];
    }
}