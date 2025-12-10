<?php

namespace Tests\Unit;

use App\Models\Resultado;
use PHPUnit\Framework\TestCase;

class ResultadoTest extends TestCase
{
    public function test_calcula_porcentaje()
    {
        $resultado = new Resultado(['votos' => 50]);

        $this->assertEquals(25.00, $resultado->calcularPorcentaje(200));
    }

    public function test_porcentaje_total_cero_devuelve_cero()
    {
        $resultado = new Resultado(['votos' => 50]);

        $this->assertEquals(0, $resultado->calcularPorcentaje(0));
    }
}
