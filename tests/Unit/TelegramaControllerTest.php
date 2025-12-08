<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Telegrama;
use App\Models\Mesa;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TelegramaControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_listar_todos_los_telegramas()
    {
        Telegrama::factory()->count(3)->create();
        $response = $this->getJson('/api/telegramas');
        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    /** @test */
    public function puede_crear_un_telegrama()
    {
        $mesa = Mesa::factory()->create(['electores' => 500]);
        $data = [
            'votosDiputados' => 100,
            'votosSenadores' => 100,
            'blancos' => 50,
            'nulos' => 20,
            'impugnados' => 10,
            'idMesa' => $mesa->idMesa,
            'idUsuario' => 1,
        ];
        $response = $this->postJson('/api/telegramas', $data);
        $response->assertStatus(201);
        $this->assertDatabaseHas('telegramas', ['votosDiputados' => 100]);
    }

    /** @test */
    public function puede_mostrar_un_telegrama()
    {
        $telegrama = Telegrama::factory()->create();
        $response = $this->getJson("/api/telegramas/{$telegrama->idTelegrama}");
        $response->assertStatus(200);
        $response->assertJsonFragment(['votosDiputados' => $telegrama->votosDiputados]);
    }

    /** @test */
    public function puede_actualizar_un_telegrama()
    {
        $telegrama = Telegrama::factory()->create();
        $nuevosDatos = ['votosDiputados' => 200];
        $response = $this->putJson("/api/telegramas/{$telegrama->idTelegrama}", $nuevosDatos);
        $response->assertStatus(200);
        $this->assertDatabaseHas('telegramas', ['votosDiputados' => 200]);
    }

    /** @test */
    public function puede_eliminar_un_telegrama()
    {
        $telegrama = Telegrama::factory()->create();
        $response = $this->deleteJson("/api/telegramas/{$telegrama->idTelegrama}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('telegramas', ['idTelegrama' => $telegrama->idTelegrama]);
    }

    /** @test */
    public function valida_campos_requeridos_al_crear()
    {
        $response = $this->postJson('/api/telegramas', []);
        $response->assertStatus(422);
    }
}