<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Provincia;

class ProvinciaTest extends TestCase
{
    public function test_puede_calcular_total_de_mesas()
    {
        $provincia = new Provincia();
        $provincia->cantidad_mesas = 120;

        $this->assertEquals(120, $provincia->totalMesas());
    }

    public function test_puede_validar_cantidad_de_mesas()
    {
        $provincia = new Provincia();
        $provincia->cantidad_mesas = 150;

        $this->assertTrue($provincia->cantidadDeMesasEsValida());
    }

    public function test_la_cantidad_de_mesas_debe_ser_positiva()
    {
        $provincia = new Provincia();
        $provincia->cantidad_mesas = -3;

        $this->assertFalse($provincia->cantidadDeMesasEsValida());
    }

    public function test_la_cantidad_de_mesas_debe_ser_un_numero_entero()
    {
        $provincia = new Provincia();
        $provincia->cantidad_mesas = 12.5;

        $this->assertFalse($provincia->cantidadDeMesasEsValida());
    }
}
