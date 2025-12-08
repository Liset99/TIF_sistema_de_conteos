<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Candidato;
use App\Models\Lista;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CandidatoControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_listar_todos_los_candidatos()
    {
        Candidato::factory()->count(3)->create();
        $response = $this->getJson('/api/candidatos');
        $response->assertStatus(200);
        $response->assertJsonStructure(['total', 'candidatos']);
    }

    /** @test */
    public function puede_crear_un_candidato()
    {
        $lista = Lista::factory()->create();
        $data = [
            'cargo' => 'DIPUTADOS',
            'ordenEnLista' => 1,
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'idLista' => $lista->idLista,
        ];
        $response = $this->postJson('/api/candidatos', $data);
        $response->assertStatus(201);
        $response->assertJson(['mensaje' => 'Candidato creado exitosamente']);
        $this->assertDatabaseHas('candidatos', ['nombre' => 'Juan']);
    }

    /** @test */
    public function puede_mostrar_un_candidato()
    {
        $candidato = Candidato::factory()->create();
        $response = $this->getJson("/api/candidatos/{$candidato->idCandidato}");
        $response->assertStatus(200);
        $response->assertJsonFragment(['nombre' => $candidato->nombre]);
    }

    /** @test */
    public function puede_actualizar_un_candidato()
    {
        $candidato = Candidato::factory()->create();
        $nuevosDatos = ['nombre' => 'Carlos'];
        $response = $this->putJson("/api/candidatos/{$candidato->idCandidato}", $nuevosDatos);
        $response->assertStatus(200);
        $this->assertDatabaseHas('candidatos', ['nombre' => 'Carlos']);
    }

    /** @test */
    public function puede_eliminar_un_candidato()
    {
        $candidato = Candidato::factory()->create();
        $response = $this->deleteJson("/api/candidatos/{$candidato->idCandidato}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('candidatos', ['idCandidato' => $candidato->idCandidato]);
    }

    /** @test */
    public function valida_campos_requeridos_al_crear()
    {
        $response = $this->postJson('/api/candidatos', []);
        $response->assertStatus(422);
    }
}