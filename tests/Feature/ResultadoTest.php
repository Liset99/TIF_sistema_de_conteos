<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Resultado;
use App\Models\Lista;
use App\Models\Telegrama;
use App\Models\Mesa;
use App\Models\Provincia;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ResultadoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_listar_resultados()
    {
        $provincia = Provincia::factory()->create();
        $lista = Lista::factory()->create(['idProvincia' => $provincia->idProvincia]);
        $mesa = Mesa::factory()->create(['idProvincia' => $provincia->idProvincia]);
        $telegrama = Telegrama::factory()->create(['idMesa' => $mesa->idMesa]);
        
        Resultado::factory()->count(3)->create([
            'idLista' => $lista->idLista,
            'idTelegrama' => $telegrama->idTelegrama
        ]);

        $response = $this->getJson('/api/resultados');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'total',
                     'resultados' => [
                         '*' => ['idResultado', 'votos', 'porcentaje', 'lista', 'telegrama']
                     ]
                 ])
                 ->assertJsonCount(3, 'resultados');
    }

    /** @test */
    public function puede_crear_resultado()
    {
        $provincia = Provincia::factory()->create();
        $lista = Lista::factory()->create(['idProvincia' => $provincia->idProvincia]);
        $mesa = Mesa::factory()->create(['idProvincia' => $provincia->idProvincia]);
        $telegrama = Telegrama::factory()->create(['idMesa' => $mesa->idMesa]);

        $datos = [
            'idResultado' => 1,
            'votos' => 150,
            'porcentaje' => 35.5,
            'idLista' => $lista->idLista,
            'idTelegrama' => $telegrama->idTelegrama
        ];

        $response = $this->postJson('/api/resultados', $datos);

        $response->assertStatus(201)
                 ->assertJson([
                     'mensaje' => 'Resultado creado exitosamente'
                 ]);

        $this->assertDatabaseHas('Resultado', [
            'votos' => 150,
            'porcentaje' => 35.5
        ]);
    }

    /** @test */
    public function puede_mostrar_resultado_completo()
    {
        $provincia = Provincia::factory()->create();
        $lista = Lista::factory()->create(['idProvincia' => $provincia->idProvincia]);
        $mesa = Mesa::factory()->create(['idProvincia' => $provincia->idProvincia]);
        $telegrama = Telegrama::factory()->create(['idMesa' => $mesa->idMesa]);
        $resultado = Resultado::factory()->create([
            'idLista' => $lista->idLista,
            'idTelegrama' => $telegrama->idTelegrama
        ]);

        $response = $this->getJson("/api/resultados/{$resultado->idResultado}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'idResultado',
                     'votos',
                     'porcentaje',
                     'lista' => ['idLista', 'nombre', 'provincia'],
                     'telegrama' => ['idTelegrama', 'mesa']
                 ]);
    }

    /** @test */
    public function puede_actualizar_resultado()
    {
        $provincia = Provincia::factory()->create();
        $lista = Lista::factory()->create(['idProvincia' => $provincia->idProvincia]);
        $mesa = Mesa::factory()->create(['idProvincia' => $provincia->idProvincia]);
        $telegrama = Telegrama::factory()->create(['idMesa' => $mesa->idMesa]);
        $resultado = Resultado::factory()->create([
            'idLista' => $lista->idLista,
            'idTelegrama' => $telegrama->idTelegrama
        ]);

        $response = $this->putJson("/api/resultados/{$resultado->idResultado}", [
            'votos' => 250,
            'porcentaje' => 45.8
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'mensaje' => 'Resultado actualizado exitosamente'
                 ]);

        $this->assertDatabaseHas('Resultado', [
            'idResultado' => $resultado->idResultado,
            'votos' => 250,
            'porcentaje' => 45.8
        ]);
    }

    /** @test */
    public function puede_eliminar_resultado()
    {
        $provincia = Provincia::factory()->create();
        $lista = Lista::factory()->create(['idProvincia' => $provincia->idProvincia]);
        $mesa = Mesa::factory()->create(['idProvincia' => $provincia->idProvincia]);
        $telegrama = Telegrama::factory()->create(['idMesa' => $mesa->idMesa]);
        $resultado = Resultado::factory()->create([
            'idLista' => $lista->idLista,
            'idTelegrama' => $telegrama->idTelegrama
        ]);

        $response = $this->deleteJson("/api/resultados/{$resultado->idResultado}");

        $response->assertStatus(200)
                 ->assertJson([
                     'mensaje' => 'Resultado eliminado correctamente'
                 ]);

        $this->assertDatabaseMissing('Resultado', [
            'idResultado' => $resultado->idResultado
        ]);
    }

    /** @test */
    public function no_permite_votos_negativos()
    {
        $provincia = Provincia::factory()->create();
        $lista = Lista::factory()->create(['idProvincia' => $provincia->idProvincia]);
        $mesa = Mesa::factory()->create(['idProvincia' => $provincia->idProvincia]);
        $telegrama = Telegrama::factory()->create(['idMesa' => $mesa->idMesa]);

        $response = $this->postJson('/api/resultados', [
            'idResultado' => 1,
            'votos' => -50,
            'porcentaje' => 10,
            'idLista' => $lista->idLista,
            'idTelegrama' => $telegrama->idTelegrama
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['votos']);
    }

    /** @test */
    public function no_permite_porcentaje_mayor_a_100()
    {
        $provincia = Provincia::factory()->create();
        $lista = Lista::factory()->create(['idProvincia' => $provincia->idProvincia]);
        $mesa = Mesa::factory()->create(['idProvincia' => $provincia->idProvincia]);
        $telegrama = Telegrama::factory()->create(['idMesa' => $mesa->idMesa]);

        $response = $this->postJson('/api/resultados', [
            'idResultado' => 1,
            'votos' => 100,
            'porcentaje' => 150,
            'idLista' => $lista->idLista,
            'idTelegrama' => $telegrama->idTelegrama
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['porcentaje']);
    }

    /** @test */
    public function requiere_lista_y_telegrama_validos()
    {
        $response = $this->postJson('/api/resultados', [
            'idResultado' => 1,
            'votos' => 100,
            'porcentaje' => 25,
            'idLista' => 999,
            'idTelegrama' => 999
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['idLista', 'idTelegrama']);
    }

    /** @test */
    public function requiere_campos_obligatorios()
    {
        $response = $this->postJson('/api/resultados', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors([
                     'idResultado',
                     'votos',
                     'idLista',
                     'idTelegrama'
                 ]);
    }
}