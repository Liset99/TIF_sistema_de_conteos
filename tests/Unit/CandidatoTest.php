<?php

namespace Tests\Unit;

use App\Models\Candidato;
use PHPUnit\Framework\TestCase;

class CandidatoTest extends TestCase
{
    public function test_nombre_completo()
    {
        $candidato = new Candidato([
            'nombre' => 'Ana',
            'apellido' => 'Pérez'
        ]);

        $this->assertEquals('Ana Pérez', $candidato->nombreCompleto());
    }
}
