<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Resultado;
use App\Models\Lista;
use App\Models\Telegrama;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ResultadoControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_listar_todos_los_resultados()
    {
        Resultado::factory()->count(3)->create();
        $response = $this->getJson('/api/resultados');
        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    /** @test */
    public function puede_crear_un_resultado()
    {
        $lista = Lista::factory()->create();
        $telegrama = Telegrama::factory()->create();
        $data = [
            'votos' => 150,
            'porcentaje' => 30.5,
            'idLista' => $lista->idLista,
            'idTelegrama' => $telegrama->idTelegrama,
        ];
        $response = $this->postJson('/api/resultados', $data);
        $response->assertStatus(201);
        $response->assertJson(['mensaje' => 'Resultado creado exitosamente']);
        $this->assertDatabaseHas('resultados', ['votos' => 150]);
    }

    /** @test */
    public function puede_mostrar_un_resultado()
    {
        $resultado = Resultado::factory()->create();
        $response = $this->getJson("/api/resultados/{$resultado->idResultado}");
        $response->assertStatus(200);
        $response->assertJsonFragment(['votos' => $resultado->votos]);
    }

    /** @test */
    public function puede_actualizar_un_resultado()
    {
        $resultado = Resultado::factory()->create();
        $nuevosDatos = ['votos' => 250];
        $response = $this->putJson("/api/resultados/{$resultado->idResultado}", $nuevosDatos);
        $response->assertStatus(200);
        $this->assertDatabaseHas('resultados', ['votos' => 250]);
    }

    /** @test */
    public function puede_eliminar_un_resultado()
    {
        $resultado = Resultado::factory()->create();
        $response = $this->deleteJson("/api/resultados/{$resultado->idResultado}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('resultados', ['idResultado' => $resultado->idResultado]);
    }

    /** @test */
    public function valida_campos_requeridos_al_crear()
    {
        $response = $this->postJson('/api/resultados', []);
        $response->assertStatus(422);
    }
}