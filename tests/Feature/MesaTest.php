<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Mesa;
use App\Models\Provincia;
use App\Models\Telegrama;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MesaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_listar_mesas()
    {
        $provincia = Provincia::factory()->create();
        Mesa::factory()->count(3)->create(['idProvincia' => $provincia->idProvincia]);

        $response = $this->getJson('/api/mesas');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'total',
                     'mesas' => [
                         '*' => ['idMesa', 'electores', 'establecimiento', 'circuito', 'provincia']
                     ]
                 ])
                 ->assertJsonCount(3, 'mesas');
    }

    /** @test */
    public function puede_crear_mesa()
    {
        $provincia = Provincia::factory()->create();

        $datos = [
            'idMesa' => 1,
            'electores' => 350,
            'establecimiento' => 'Escuela N° 123',
            'circuito' => 'Circuito A',
            'idProvincia' => $provincia->idProvincia
        ];

        $response = $this->postJson('/api/mesas', $datos);

        $response->assertStatus(201)
                 ->assertJson([
                     'mensaje' => 'Mesa creada exitosamente'
                 ]);

        $this->assertDatabaseHas('Mesa', [
            'electores' => 350,
            'establecimiento' => 'Escuela N° 123'
        ]);
    }

    /** @test */
    public function puede_mostrar_una_mesa_con_telegramas()
    {
        $provincia = Provincia::factory()->create();
        $mesa = Mesa::factory()->create(['idProvincia' => $provincia->idProvincia]);
        Telegrama::factory()->create(['idMesa' => $mesa->idMesa]);

        $response = $this->getJson("/api/mesas/{$mesa->idMesa}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'idMesa',
                     'electores',
                     'establecimiento',
                     'provincia',
                     'telegramas' => [
                         '*' => ['idTelegrama', 'votosDiputados', 'votosSenadores']
                     ]
                 ]);
    }

    /** @test */
    public function puede_actualizar_mesa()
    {
        $provincia = Provincia::factory()->create();
        $mesa = Mesa::factory()->create(['idProvincia' => $provincia->idProvincia]);

        $response = $this->putJson("/api/mesas/{$mesa->idMesa}", [
            'electores' => 500,
            'establecimiento' => 'Escuela Actualizada'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'mensaje' => 'Mesa actualizada exitosamente'
                 ]);

        $this->assertDatabaseHas('Mesa', [
            'idMesa' => $mesa->idMesa,
            'electores' => 500,
            'establecimiento' => 'Escuela Actualizada'
        ]);
    }

    /** @test */
    public function puede_eliminar_mesa_sin_telegramas()
    {
        $provincia = Provincia::factory()->create();
        $mesa = Mesa::factory()->create(['idProvincia' => $provincia->idProvincia]);

        $response = $this->deleteJson("/api/mesas/{$mesa->idMesa}");

        $response->assertStatus(200)
                 ->assertJson([
                     'mensaje' => 'Mesa eliminada correctamente'
                 ]);

        $this->assertDatabaseMissing('Mesa', [
            'idMesa' => $mesa->idMesa
        ]);
    }

    /** @test */
    public function no_puede_eliminar_mesa_con_telegramas()
    {
        $provincia = Provincia::factory()->create();
        $mesa = Mesa::factory()->create(['idProvincia' => $provincia->idProvincia]);
        Telegrama::factory()->create(['idMesa' => $mesa->idMesa]);

        $response = $this->deleteJson("/api/mesas/{$mesa->idMesa}");

        $response->assertStatus(400)
                 ->assertJson([
                     'error' => 'No se puede eliminar la mesa porque tiene telegramas asociados'
                 ]);

        $this->assertDatabaseHas('Mesa', [
            'idMesa' => $mesa->idMesa
        ]);
    }

    /** @test */
    public function no_permite_electores_negativos()
    {
        $provincia = Provincia::factory()->create();

        $response = $this->postJson('/api/mesas', [
            'idMesa' => 1,
            'electores' => -50,
            'establecimiento' => 'Escuela Test',
            'circuito' => 'A',
            'idProvincia' => $provincia->idProvincia
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['electores']);
    }

    /** @test */
    public function requiere_provincia_valida()
    {
        $response = $this->postJson('/api/mesas', [
            'idMesa' => 1,
            'electores' => 300,
            'establecimiento' => 'Escuela Test',
            'circuito' => 'A',
            'idProvincia' => 999
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['idProvincia']);
    }

    /** @test */
    public function requiere_campos_obligatorios()
    {
        $response = $this->postJson('/api/mesas', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors([
                     'idMesa',
                     'electores',
                     'establecimiento',
                     'circuito',
                     'idProvincia'
                 ]);
    }
}