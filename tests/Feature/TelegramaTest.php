<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Telegrama;
use App\Models\Mesa;
use App\Models\Provincia;
use App\Models\Resultado;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TelegramaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_listar_telegramas()
    {
        $provincia = Provincia::factory()->create();
        $mesa = Mesa::factory()->create(['idProvincia' => $provincia->idProvincia]);
        Telegrama::factory()->count(3)->create(['idMesa' => $mesa->idMesa]);

        $response = $this->getJson('/api/telegramas');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'total',
                     'telegramas' => [
                         '*' => ['idTelegrama', 'votosDiputados', 'votosSenadores', 'mesa']
                     ]
                 ])
                 ->assertJsonCount(3, 'telegramas');
    }

    /** @test */
    public function puede_crear_telegrama_valido()
    {
        $provincia = Provincia::factory()->create();
        $mesa = Mesa::factory()->create([
            'idProvincia' => $provincia->idProvincia,
            'electores' => 500
        ]);

        $datos = [
            'idTelegrama' => 1,
            'idMesa' => $mesa->idMesa,
            'votosDiputados' => 200,
            'votosSenadores' => 180,
            'blancos' => 50,
            'nulos' => 30,
            'impugnados' => 10
        ];

        $response = $this->postJson('/api/telegramas', $datos);

        $response->assertStatus(201)
                 ->assertJson([
                     'mensaje' => 'Telegrama creado exitosamente'
                 ]);

        $this->assertDatabaseHas('telegramas', [
            'idTelegrama' => 1,
            'votosDiputados' => 200,
            'votosSenadores' => 180
        ]);
    }

    /** @test */
    public function no_puede_crear_telegrama_que_excede_electores()
    {
        $provincia = Provincia::factory()->create();
        $mesa = Mesa::factory()->create([
            'idProvincia' => $provincia->idProvincia,
            'electores' => 100
        ]);

        $datos = [
            'idTelegrama' => 1,
            'idMesa' => $mesa->idMesa,
            'votosDiputados' => 60,
            'votosSenadores' => 50,
            'blancos' => 20,
            'nulos' => 10,
            'impugnados' => 5
        ];

        $response = $this->postJson('/api/telegramas', $datos);

        $response->assertStatus(400)
                 ->assertJson([
                     'error' => 'El total de votos no puede superar la cantidad de electores'
                 ]);

        $this->assertDatabaseMissing('telegramas', [
            'idTelegrama' => 1
        ]);
    }

    /** @test */
    public function puede_mostrar_telegrama_con_resultados()
    {
        $provincia = Provincia::factory()->create();
        $mesa = Mesa::factory()->create(['idProvincia' => $provincia->idProvincia]);
        $telegrama = Telegrama::factory()->create(['idMesa' => $mesa->idMesa]);

        $response = $this->getJson("/api/telegramas/{$telegrama->idTelegrama}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'idTelegrama',
                     'votosDiputados',
                     'votosSenadores',
                     'blancos',
                     'nulos',
                     'impugnados',
                     'mesa',
                     'resultados'
                 ]);
    }

    /** @test */
    public function puede_actualizar_telegrama_respetando_limite()
    {
        $provincia = Provincia::factory()->create();
        $mesa = Mesa::factory()->create([
            'idProvincia' => $provincia->idProvincia,
            'electores' => 500
        ]);
        $telegrama = Telegrama::factory()->create([
            'idMesa' => $mesa->idMesa,
            'votosDiputados' => 100,
            'votosSenadores' => 100,
            'blancos' => 50,
            'nulos' => 30,
            'impugnados' => 10
        ]);

        $response = $this->putJson("/api/telegramas/{$telegrama->idTelegrama}", [
            'votosDiputados' => 150
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'mensaje' => 'Telegrama actualizado exitosamente'
                 ]);

        $this->assertDatabaseHas('telegramas', [
            'idTelegrama' => $telegrama->idTelegrama,
            'votosDiputados' => 150
        ]);
    }

    /** @test */
    public function no_puede_actualizar_telegrama_excediendo_electores()
    {
        $provincia = Provincia::factory()->create();
        $mesa = Mesa::factory()->create([
            'idProvincia' => $provincia->idProvincia,
            'electores' => 200
        ]);
        $telegrama = Telegrama::factory()->create([
            'idMesa' => $mesa->idMesa,
            'votosDiputados' => 50,
            'votosSenadores' => 50,
            'blancos' => 30,
            'nulos' => 20,
            'impugnados' => 10
        ]);

        $response = $this->putJson("/api/telegramas/{$telegrama->idTelegrama}", [
            'votosDiputados' => 150
        ]);

        $response->assertStatus(400)
                 ->assertJson([
                     'error' => 'El total de votos no puede superar la cantidad de electores'
                 ]);
    }

    /** @test */
    public function puede_eliminar_telegrama_sin_resultados()
    {
        $provincia = Provincia::factory()->create();
        $mesa = Mesa::factory()->create(['idProvincia' => $provincia->idProvincia]);
        $telegrama = Telegrama::factory()->create(['idMesa' => $mesa->idMesa]);

        $response = $this->deleteJson("/api/telegramas/{$telegrama->idTelegrama}");

        $response->assertStatus(200)
                 ->assertJson([
                     'mensaje' => 'Telegrama eliminado correctamente'
                 ]);

        $this->assertDatabaseMissing('telegramas', [
            'idTelegrama' => $telegrama->idTelegrama
        ]);
    }

    /** @test */
    public function no_puede_eliminar_telegrama_con_resultados()
    {
        $provincia = Provincia::factory()->create();
        $mesa = Mesa::factory()->create(['idProvincia' => $provincia->idProvincia]);
        $telegrama = Telegrama::factory()->create(['idMesa' => $mesa->idMesa]);
        
        Resultado::factory()->create(['idTelegrama' => $telegrama->idTelegrama]);

        $response = $this->deleteJson("/api/telegramas/{$telegrama->idTelegrama}");

        $response->assertStatus(400)
                 ->assertJson([
                     'error' => 'No se puede eliminar el telegrama porque tiene resultados asociados'
                 ]);
    }

    /** @test */
    public function no_permite_valores_negativos()
    {
        $provincia = Provincia::factory()->create();
        $mesa = Mesa::factory()->create(['idProvincia' => $provincia->idProvincia]);

        $response = $this->postJson('/api/telegramas', [
            'idTelegrama' => 1,
            'idMesa' => $mesa->idMesa,
            'votosDiputados' => -10,
            'votosSenadores' => 50,
            'blancos' => 0,
            'nulos' => 0,
            'impugnados' => 0
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['votosDiputados']);
    }

    /** @test */
    public function requiere_mesa_existente()
    {
        $response = $this->postJson('/api/telegramas', [
            'idTelegrama' => 1,
            'idMesa' => 999,
            'votosDiputados' => 100,
            'votosSenadores' => 80,
            'blancos' => 10,
            'nulos' => 5,
            'impugnados' => 2
        ]);

          $response->assertStatus(422);
    } 
} 
