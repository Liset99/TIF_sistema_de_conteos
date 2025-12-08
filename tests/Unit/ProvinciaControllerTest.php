<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Provincia;
use App\Models\Lista;
use App\Models\Resultado;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProvinciaControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_listar_todas_las_provincias()
    {
        Provincia::factory()->count(3)->create();
        $response = $this->getJson('/api/provincias');
        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    /** @test */
    public function puede_crear_una_provincia()
    {
        $data = ['nombre' => 'Buenos Aires Test'];
        $response = $this->postJson('/api/provincias', $data);
        $response->assertStatus(201);
        $response->assertJson(['mensaje' => 'Provincia creada con éxito']);
        $this->assertDatabaseHas('provincias', ['nombre' => 'Buenos Aires Test']);
    }

    /** @test */
    public function puede_mostrar_una_provincia()
    {
        $provincia = Provincia::factory()->create();
        $response = $this->getJson("/api/provincias/{$provincia->idProvincia}");
        $response->assertStatus(200);
        $response->assertJsonFragment(['nombre' => $provincia->nombre]);
    }

    /** @test */
    public function puede_actualizar_una_provincia()
    {
        $provincia = Provincia::factory()->create();
        $nuevosDatos = ['nombre' => 'Córdoba Actualizada'];
        $response = $this->putJson("/api/provincias/{$provincia->idProvincia}", $nuevosDatos);
        $response->assertStatus(200);
        $this->assertDatabaseHas('provincias', ['nombre' => 'Córdoba Actualizada']);
    }

    /** @test */
    public function puede_eliminar_provincia_sin_listas()
    {
        $provincia = Provincia::factory()->create();
        $response = $this->deleteJson("/api/provincias/{$provincia->idProvincia}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('provincias', ['idProvincia' => $provincia->idProvincia]);
    }

    /** @test */
    public function no_puede_eliminar_provincia_con_listas()
    {
        $provincia = Provincia::factory()->create();
        Lista::factory()->create(['idProvincia' => $provincia->idProvincia]);
        $response = $this->deleteJson("/api/provincias/{$provincia->idProvincia}");
        $response->assertStatus(400);
        $response->assertJsonFragment(['error' => 'No se puede eliminar la provincia porque tiene listas asociadas']);
    }

    /** @test */
    public function valida_campos_requeridos_al_crear()
    {
        $response = $this->postJson('/api/provincias', []);
        $response->assertStatus(422);
    }
}
