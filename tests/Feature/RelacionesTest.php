<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Provincia;
use App\Models\Lista;
use App\Models\Persona;
use App\Models\Usuario;
use App\Models\Mesa;
use App\Models\Telegrama;
use App\Models\Resultado;
use App\Models\Candidato;

class RelacionesTest extends TestCase
{
    /** @test */
    public function test_provincias_tienen_listas_y_mesas()
    {
        $prov = Provincia::find(1);
        $this->assertTrue($prov->listas->count() > 0, "La provincia debería tener listas");
        $this->assertTrue($prov->mesas->count() > 0, "La provincia debería tener mesas");
    }

    /** @test */
    public function test_lista_pertenece_a_provincia_y_tiene_candidatos()
    {
        $lista = Lista::find(1);
        $this->assertNotNull($lista->provincia, "La lista debería pertenecer a una provincia");
        $this->assertTrue($lista->candidatos->count() > 0, "La lista debería tener candidatos");
    }

    /** @test */
    public function test_telegrama_tiene_resultados_mesa_y_usuario()
    {
        $telegrama = Telegrama::find(1);
        $this->assertNotNull($telegrama->mesa, "El telegrama debería pertenecer a una mesa");
        $this->assertNotNull($telegrama->usuario, "El telegrama debería pertenecer a un usuario");
        $this->assertTrue($telegrama->resultados->count() > 0, "El telegrama debería tener resultados");
    }

    /** @test */
    public function test_resultado_pertenece_a_lista_y_telegrama()
    {
        $resultado = Resultado::find(1);
        $this->assertNotNull($resultado->lista, "El resultado debería pertenecer a una lista");
        $this->assertNotNull($resultado->telegrama, "El resultado debería pertenecer a un telegrama");
    }

    /** @test */
    public function test_candidato_pertenece_a_lista_y_persona()
    {
        $candidato = Candidato::find(1);
        $this->assertNotNull($candidato->lista, "El candidato debería pertenecer a una lista");
        $this->assertNotNull($candidato->persona, "El candidato debería estar asociado a una persona");
    }

    /** @test */
    public function test_usuario_esta_asociado_a_persona()
    {
        $usuario = Usuario::find(1);
        $this->assertNotNull($usuario->persona, "El usuario debería estar asociado a una persona");
    }
}
