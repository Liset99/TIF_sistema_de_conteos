<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Provincia;
use App\Models\Mesa;
use App\Models\Telegrama;

class TestSeeder extends Seeder
{
    public function run(): void
    {
        // Crear una provincia
        $provincia = Provincia::factory()->create();

        // Crear 2 mesas para esa provincia
        $mesas = Mesa::factory()->count(2)->create([
            'idProvincia' => $provincia->idProvincia
        ]);

        // Crear telegramas asignando cada uno a una mesa existente
        foreach ($mesas as $mesa) {
            Telegrama::factory()->count(3)->create([
                'idMesa' => $mesa->idMesa
            ]);
        }
    }
}