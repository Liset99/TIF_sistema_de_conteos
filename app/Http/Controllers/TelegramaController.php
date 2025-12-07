<?php

namespace App\Http\Controllers;

use App\Models\Telegrama;
use App\Models\Mesa;
use Illuminate\Http\Request;

class TelegramaController extends Controller
{
    // Listar todos
    public function index()
    {
        $telegramas = Telegrama::with('mesa')->get();
        
        return response()->json([
            'total' => $telegramas->count(),
            'telegramas' => $telegramas
        ]);
    }

    // Crear
    public function store(Request $request)
    {
        $validated = $request->validate([
            'idTelegrama' => 'required|integer|unique:Telegrama,idTelegrama',
            'idMesa' => 'required|integer|exists:Mesa,idMesa',
            'votosDiputados' => 'required|integer|min:0',
            'votosSenadores' => 'required|integer|min:0',
            'blancos' => 'required|integer|min:0',
            'nulos' => 'required|integer|min:0',
            'impugnados' => 'required|integer|min:0',
        ]);

        // Verificar que no exceda electores
        $mesa = Mesa::findOrFail($validated['idMesa']);
        $totalVotos = $validated['votosDiputados'] + $validated['votosSenadores'] + 
                      $validated['blancos'] + $validated['nulos'] + $validated['impugnados'];

        if ($totalVotos > $mesa->electores) {
            return response()->json([
                'error' => 'El total de votos no puede superar la cantidad de electores',
                'electores_disponibles' => $mesa->electores,
                'total_votos_ingresados' => $totalVotos
            ], 400);
        }

        // Agregar fecha y hora automáticamente
        $validated['fechaHora'] = now();
        
        $telegrama = Telegrama::create($validated);

        return response()->json([
            'mensaje' => 'Telegrama creado exitosamente',
            'telegrama' => $telegrama->load('mesa')
        ], 201);
    }

    // Mostrar uno
    public function show($id)
    {
        $telegrama = Telegrama::with(['mesa', 'resultados.lista'])
            ->findOrFail($id);
            
        return response()->json($telegrama);
    }

    // Actualizar
    public function update(Request $request, $id)
    {
        $telegrama = Telegrama::findOrFail($id);

        $validated = $request->validate([
            'votosDiputados' => 'sometimes|integer|min:0',
            'votosSenadores' => 'sometimes|integer|min:0',
            'blancos' => 'sometimes|integer|min:0',
            'nulos' => 'sometimes|integer|min:0',
            'impugnados' => 'sometimes|integer|min:0',
        ]);

        // Verificar electores con valores actualizados
        $mesa = Mesa::findOrFail($telegrama->idMesa);
        $totalVotos = 
            ($validated['votosDiputados'] ?? $telegrama->votosDiputados) +
            ($validated['votosSenadores'] ?? $telegrama->votosSenadores) +
            ($validated['blancos'] ?? $telegrama->blancos) +
            ($validated['nulos'] ?? $telegrama->nulos) +
            ($validated['impugnados'] ?? $telegrama->impugnados);

        if ($totalVotos > $mesa->electores) {
            return response()->json([
                'error' => 'El total de votos no puede superar la cantidad de electores',
                'electores_disponibles' => $mesa->electores,
                'total_votos_ingresados' => $totalVotos
            ], 400);
        }

        $telegrama->update($validated);

        return response()->json([
            'mensaje' => 'Telegrama actualizado exitosamente',
            'telegrama' => $telegrama->load('mesa')
        ]);
    }

    // Eliminar
    public function destroy($id)
    {
        $telegrama = Telegrama::findOrFail($id);
        
        // Verificar si tiene resultados
        if ($telegrama->resultados()->exists()) {
            return response()->json([
                'error' => 'No se puede eliminar el telegrama porque tiene resultados asociados'
            ], 400);
        }

        $telegrama->delete();

        return response()->json([
            'mensaje' => 'Telegrama eliminado correctamente'
        ]);
    }
}