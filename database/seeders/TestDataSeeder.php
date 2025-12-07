<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Provincia;
use App\Models\Lista;
use App\Models\Candidato;
use App\Models\Mesa;
use App\Models\Telegrama;
use App\Models\Resultado;


class TestDataSeeder extends Seeder{
    public function run()
    {
        // ============================
        // 1. PROVINCIAS (3)
        // ============================
        Provincia::insert([
            [
                'idProvincia' => 1,
                'nombre'      => 'Buenos Aires',
                'codigo'      => 'BA',
                'region'      => 'Pampeana'
            ],
            [
                'idProvincia' => 2,
                'nombre'      => 'Córdoba',
                'codigo'      => 'CB',
                'region'      => 'Centro'
            ],
            [
                'idProvincia' => 3,
                'nombre'      => 'Santa Fe',
                'codigo'      => 'SF',
                'region'      => 'Pampeana'
            ],
        ]);

        // ============================
        // 2. LISTAS (3)
        // ============================
        Lista::insert([
            [
                'idLista'       => 100,
                'nombre'        => 'Unión Federal',
                'alianza'       => 'Frente Federal',
                'cargoDiputado' => 'Diputado Nacional',
                'cargoSenador'  => 'Senador Nacional',
                'idProvincia'   => 1
            ],
            [
                'idLista'       => 101,
                'nombre'        => 'Renovación Justa',
                'alianza'       => 'Alianza Nacional',
                'cargoDiputado' => 'Diputado Nacional',
                'cargoSenador'  => 'Senador Nacional',
                'idProvincia'   => 2
            ],
            [
                'idLista'       => 102,
                'nombre'        => 'Progreso Unido',
                'alianza'       => 'Coalición Federal',
                'cargoDiputado' => 'Diputado Nacional',
                'cargoSenador'  => 'Senador Nacional',
                'idProvincia'   => 3
            ],
        ]);

        // ============================
        // 3. CANDIDATOS (3)
        // ============================
        Candidato::insert([
            [
                'idCandidato' => 1,
                'cargo' => 'Diputado',
                'ordenEnLista' => 1,
                'nombre' => 'María',
                'apellido' => 'Fernández',
                'idLista' => 100
            ],
            [
                'idCandidato' => 2,
                'cargo' => 'Senador',
                'ordenEnLista' => 1,
                'nombre' => 'Jorge',
                'apellido' => 'Pérez',
                'idLista' => 101
            ],
            [
                'idCandidato' => 3,
                'cargo' => 'Diputado',
                'ordenEnLista' => 1,
                'nombre' => 'Lucía',
                'apellido' => 'Martín',
                'idLista' => 102
            ],
        ]);

        // ============================
        // 4. MESAS (3)
        // ============================
        Mesa::insert([
            [
                'idMesa'        => 500,
                'electores'     => 320,
                'establecimiento'=> 'Escuela N°12',
                'circuito'      => 'Circuito 14B',
                'idProvincia'   => 1
            ],
            [
                'idMesa'        => 501,
                'electores'     => 295,
                'establecimiento'=> 'Escuela Técnica 3',
                'circuito'      => 'Circuito 15A',
                'idProvincia'   => 2
            ],
            [
                'idMesa'        => 502,
                'electores'     => 310,
                'establecimiento'=> 'Colegio San Martín',
                'circuito'      => 'Circuito 12C',
                'idProvincia'   => 3
            ],
        ]);

        // ============================
        // 5. TELEGRAMAS (3)
        // ============================
        Telegrama::insert([
            [
                'idTelegrama'   => 900,
                'votosDiputados'=> 250,
                'votosSenadores'=> 240,
                'blancos'       => 5,
                'nulos'         => 3,
                'impugnados'    => 1,
                'fechaHora'     => now(),
                'idMesa'        => 500
            ],
            [
                'idTelegrama'   => 901,
                'votosDiputados'=> 270,
                'votosSenadores'=> 265,
                'blancos'       => 4,
                'nulos'         => 2,
                'impugnados'    => 0,
                'fechaHora'     => now(),
                'idMesa'        => 501
            ],
            [
                'idTelegrama'   => 902,
                'votosDiputados'=> 260,
                'votosSenadores'=> 255,
                'blancos'       => 3,
                'nulos'         => 1,
                'impugnados'    => 0,
                'fechaHora'     => now(),
                'idMesa'        => 502
            ],
        ]);

        // ============================
        // 6. RESULTADOS (3)
        // ============================
        Resultado::insert([
            [
                'idResultado' => 700,
                'votos'       => 130,
                'porcentaje'  => 52.0,
                'idLista'     => 100,
                'idTelegrama' => 900
            ],
            [
                'idResultado' => 701,
                'votos'       => 145,
                'porcentaje'  => 54.0,
                'idLista'     => 101,
                'idTelegrama' => 901
            ],
            [
                'idResultado' => 702,
                'votos'       => 125,
                'porcentaje'  => 46.0,
                'idLista'     => 102,
                'idTelegrama' => 902
            ],
        ]);
    }
}
