<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Lista;
use App\Models\Provincia;
use App\Models\Candidato;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListaModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_listar_todas_las_listas()
    {
        Lista::factory()->count(3)->create();
        $response = $this->getJson('/api/listas');
        $response->assertStatus(200);
        $response->assertJsonStructure(['total', 'listas']);
    }

    /** @test */
    public function puede_crear_una_lista()
    {
        $provincia = Provincia::factory()->create();
        $data = [
            'nombre' => 'Lista Test',
            'alianza' => 'Alianza Test',
            'cargoDiputado' => 'true',
            'cargoSenador' => 'false',
            'idProvincia' => $provincia->idProvincia,
        ];
        $response = $this->postJson('/api/listas', $data);
        $response->assertStatus(201);
        $response->assertJson(['mensaje' => 'Lista creada exitosamente']);
        $this->assertDatabaseHas('listas', ['nombre' => 'Lista Test']);
    }

    /** @test */
    public function puede_mostrar_una_lista()
    {
        $lista = Lista::factory()->create();
        $response = $this->getJson("/api/listas/{$lista->idLista}");
        $response->assertStatus(200);
        $response->assertJsonFragment(['nombre' => $lista->nombre]);
    }

    /** @test */
    public function puede_actualizar_una_lista()
    {
        $lista = Lista::factory()->create();
        $nuevosDatos = ['nombre' => 'Lista Actualizada'];
        $response = $this->putJson("/api/listas/{$lista->idLista}", $nuevosDatos);
        $response->assertStatus(200);
        $this->assertDatabaseHas('listas', ['nombre' => 'Lista Actualizada']);
    }

    /** @test */
    public function puede_eliminar_lista_sin_candidatos()
    {
        $lista = Lista::factory()->create();
        $response = $this->deleteJson("/api/listas/{$lista->idLista}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('listas', ['idLista' => $lista->idLista]);
    }

    /** @test */
    public function no_puede_eliminar_lista_con_candidatos()
    {
        $lista = Lista::factory()->create();
        Candidato::factory()->create(['idLista' => $lista->idLista]);
        $response = $this->deleteJson("/api/listas/{$lista->idLista}");
        $response->assertStatus(400);
        $response->assertJsonFragment(['error' => 'No se puede eliminar la lista porque tiene candidatos asociados']);
    }

    /** @test */
    public function valida_campos_requeridos_al_crear()
    {
        $response = $this->postJson('/api/listas', []);
        $response->assertStatus(422);
    }
}
