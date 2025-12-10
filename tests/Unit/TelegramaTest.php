<?php

namespace Tests\Unit;

use App\Models\Telegrama;
use App\Models\Mesa;
use PHPUnit\Framework\TestCase;

class TelegramaTest extends TestCase
{
    public function test_calcula_total_de_votos()
    {
        $telegrama = new Telegrama([
            'votosDiputados' => 120,
            'votosSenadores' => 90,
            'blancos' => 8,
            'nulos' => 5,
            'impugnados' => 1,
        ]);

        $this->assertEquals(224, $telegrama->totalVotos());
    }

    public function test_detecta_si_supera_electores()
    {
        $mesa = new Mesa(['electores' => 200]);

        $telegrama = new Telegrama([
            'votosDiputados' => 150,
            'votosSenadores' => 100,
            'blancos' => 0,
            'nulos' => 0,
            'impugnados' => 0,
        ]);

        $telegrama->setRelation('mesa', $mesa);

        $this->assertTrue($telegrama->votosExcedenElectores());
    }

    public function test_valida_y_lanza_error_si_excede()
    {
        $this->expectException(\DomainException::class);

        $mesa = new Mesa(['electores' => 100]);

        $telegrama = new Telegrama([
            'votosDiputados' => 120,
            'votosSenadores' => 0,
            'blancos' => 0,
            'nulos' => 0,
            'impugnados' => 0,
        ]);

        $telegrama->setRelation('mesa', $mesa);

        $telegrama->validarQueNoSupereElectores();
    }
}
