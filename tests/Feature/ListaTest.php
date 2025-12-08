<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Lista;
use App\Models\Provincia;
use App\Models\Candidato;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_listar_listas()
    {
        $provincia = Provincia::factory()->create();
        Lista::factory()->count(3)->create(['idProvincia' => $provincia->idProvincia]);

        $response = $this->getJson('/api/listas');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'total',
                     'listas' => [
                         '*' => ['idLista', 'nombre', 'alianza', 'provincia']
                     ]
                 ])
                 ->assertJsonCount(3, 'listas');
    }

    /** @test */
    public function puede_crear_lista()
    {
        $provincia = Provincia::factory()->create();

        $datos = [
            'idLista' => 1,
            'nombre' => 'Frente Renovador',
            'alianza' => 'Alianza Nacional',
            'cargoDiputado' => true,
            'cargoSenador' => false,
            'idProvincia' => $provincia->idProvincia
        ];

        $response = $this->postJson('/api/listas', $datos);

        $response->assertStatus(201)
                 ->assertJson([
                     'mensaje' => 'Lista creada exitosamente'
                 ]);

        $this->assertDatabaseHas('Lista', [
            'nombre' => 'Frente Renovador',
            'idProvincia' => $provincia->idProvincia
        ]);
    }

    /** @test */
    public function puede_mostrar_una_lista_con_candidatos()
    {
        $provincia = Provincia::factory()->create();
        $lista = Lista::factory()->create(['idProvincia' => $provincia->idProvincia]);
        Candidato::factory()->count(2)->create(['idLista' => $lista->idLista]);

        $response = $this->getJson("/api/listas/{$lista->idLista}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'idLista',
                     'nombre',
                     'provincia',
                     'candidatos' => [
                         '*' => ['idCandidato', 'cargo', 'dni']
                     ]
                 ]);
    }

    /** @test */
    public function puede_actualizar_lista()
    {
        $provincia = Provincia::factory()->create();
        $lista = Lista::factory()->create(['idProvincia' => $provincia->idProvincia]);

        $response = $this->putJson("/api/listas/{$lista->idLista}", [
            'nombre' => 'Lista Actualizada',
            'cargoSenador' => true
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'mensaje' => 'Lista actualizada exitosamente'
                 ]);

        $this->assertDatabaseHas('Lista', [
            'idLista' => $lista->idLista,
            'nombre' => 'Lista Actualizada',
            'cargoSenador' => true
        ]);
    }

    /** @test */
    public function puede_eliminar_lista_sin_candidatos()
    {
        $provincia = Provincia::factory()->create();
        $lista = Lista::factory()->create(['idProvincia' => $provincia->idProvincia]);

        $response = $this->deleteJson("/api/listas/{$lista->idLista}");

        $response->assertStatus(200)
                 ->assertJson([
                     'mensaje' => 'Lista eliminada correctamente'
                 ]);

        $this->assertDatabaseMissing('Lista', [
            'idLista' => $lista->idLista
        ]);
    }

    /** @test */
    public function no_puede_eliminar_lista_con_candidatos()
    {
        $provincia = Provincia::factory()->create();
        $lista = Lista::factory()->create(['idProvincia' => $provincia->idProvincia]);
        Candidato::factory()->create(['idLista' => $lista->idLista]);

        $response = $this->deleteJson("/api/listas/{$lista->idLista}");

        $response->assertStatus(400)
                 ->assertJson([
                     'error' => 'No se puede eliminar la lista porque tiene candidatos asociados'
                 ]);

        $this->assertDatabaseHas('Lista', [
            'idLista' => $lista->idLista
        ]);
    }

    /** @test */
    public function requiere_provincia_valida()
    {
        $response = $this->postJson('/api/listas', [
            'idLista' => 1,
            'nombre' => 'Lista Test',
            'cargoDiputado' => true,
            'cargoSenador' => false,
            'idProvincia' => 999
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['idProvincia']);
    }
}
