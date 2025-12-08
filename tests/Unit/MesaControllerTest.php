<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Mesa;
use App\Models\Provincia;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MesaControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_listar_todas_las_mesas()
    {
        Mesa::factory()->count(3)->create();
        $response = $this->getJson('/api/mesas');
        $response->assertStatus(200);
        $response->assertJsonStructure(['total', 'mesas']);
    }

    /** @test */
    public function puede_crear_una_mesa()
    {
        $provincia = Provincia::factory()->create();
        $data = [
            'electores' => 300,
            'establecimiento' => 'Escuela Test',
            'circuito' => 'Circuito 1',
            'idProvincia' => $provincia->idProvincia,
        ];
        $response = $this->postJson('/api/mesas', $data);
        $response->assertStatus(201);
        $response->assertJson(['mensaje' => 'Mesa creada exitosamente']);
        $this->assertDatabaseHas('mesas', ['establecimiento' => 'Escuela Test']);
    }

    /** @test */
    public function puede_mostrar_una_mesa()
    {
        $mesa = Mesa::factory()->create();
        $response = $this->getJson("/api/mesas/{$mesa->idMesa}");
        $response->assertStatus(200);
        $response->assertJsonFragment(['establecimiento' => $mesa->establecimiento]);
    }

    /** @test */
    public function puede_actualizar_una_mesa()
    {
        $mesa = Mesa::factory()->create();
        $nuevosDatos = ['establecimiento' => 'Escuela Actualizada'];
        $response = $this->putJson("/api/mesas/{$mesa->idMesa}", $nuevosDatos);
        $response->assertStatus(200);
        $this->assertDatabaseHas('mesas', ['establecimiento' => 'Escuela Actualizada']);
    }

    /** @test */
    public function puede_eliminar_una_mesa()
    {
        $mesa = Mesa::factory()->create();
        $response = $this->deleteJson("/api/mesas/{$mesa->idMesa}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('mesas', ['idMesa' => $mesa->idMesa]);
    }

    /** @test */
    public function valida_campos_requeridos_al_crear()
    {
        $response = $this->postJson('/api/mesas', []);
        $response->assertStatus(422);
    }
}