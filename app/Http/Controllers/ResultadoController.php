<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resultado;

class ResultadoController extends Controller
{
    // Listar todos
    public function index()
    {
        $resultados = Resultado::with(['lista', 'telegrama.mesa'])->get();
        
        return response()->json([
            'total' => $resultados->count(),
            'resultados' => $resultados
        ]);
    }

    // Crear
    public function store(Request $request)
    {
        $validated = $request->validate([
            'idResultado' => 'required|integer|unique:Resultado,idResultado',
            'votos' => 'required|integer|min:0',
            'porcentaje' => 'nullable|numeric|min:0|max:100',
            'idLista' => 'required|integer|exists:Lista,idLista',
            'idTelegrama' => 'required|integer|exists:Telegrama,idTelegrama',
        ]);

        $resultado = Resultado::create($validated);

        return response()->json([
            'mensaje' => 'Resultado creado exitosamente',
            'resultado' => $resultado->load(['lista', 'telegrama'])
        ], 201);
    }

    // Mostrar uno
    public function show($id)
    {
        $resultado = Resultado::with(['lista.provincia', 'telegrama.mesa'])
            ->findOrFail($id);
            
        return response()->json($resultado);
    }

    // Actualizar
    public function update(Request $request, $id)
    {
        $resultado = Resultado::findOrFail($id);

        $validated = $request->validate([
            'votos' => 'sometimes|integer|min:0',
            'porcentaje' => 'nullable|numeric|min:0|max:100',
            'idLista' => 'sometimes|integer|exists:Lista,idLista',
            'idTelegrama' => 'sometimes|integer|exists:Telegrama,idTelegrama',
        ]);

        $resultado->update($validated);

        return response()->json([
            'mensaje' => 'Resultado actualizado exitosamente',
            'resultado' => $resultado->load(['lista', 'telegrama'])
        ]);
    }

    // Eliminar
    public function destroy($id)
    {
        $resultado = Resultado::findOrFail($id);
        $resultado->delete();

        return response()->json([
            'mensaje' => 'Resultado eliminado correctamente'
        ]);
    }
}