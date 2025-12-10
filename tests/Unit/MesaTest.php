<?php

namespace Tests\Unit;

use App\Models\Mesa;
use App\Models\Telegrama;
use PHPUnit\Framework\TestCase;

class MesaTest extends TestCase
{
    public function test_mesa_tiene_telegramas_cargados()
    {
        $mesa = new Mesa();
        $mesa->setRelation('telegramas', collect([new Telegrama()]));

        $this->assertTrue($mesa->tieneTelegramasCargados());
    }

    public function test_mesa_sin_telegramas()
    {
        $mesa = new Mesa();
        $mesa->setRelation('telegramas', collect([]));

        $this->assertFalse($mesa->tieneTelegramasCargados());
    }

    public function test_electores_validos()
    {
        $mesa = new Mesa(['electores' => 350]);

        $this->assertTrue($mesa->electoresValidos());
    }
}
