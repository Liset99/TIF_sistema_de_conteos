<?php

namespace Database\Factories;

use App\Models\Telegrama;
use App\Models\Mesa;
use Illuminate\Database\Eloquent\Factories\Factory;

class TelegramaFactory extends Factory
{
    protected $model = Telegrama::class;

    public function definition(): array
    {
        return [
            'votosDiputados' => $this->faker->numberBetween(0, 300),
            'votosSenadores' => $this->faker->numberBetween(0, 300),
            'blancos' => $this->faker->numberBetween(0, 50),
            'nulos' => $this->faker->numberBetween(0, 30),
            'impugnados' => $this->faker->numberBetween(0, 20),
            'fechaHora' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'idMesa' => Mesa::factory(),
        ];
    }
}