<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lista;

class ListaController extends Controller
{
    // Listar todos
    public function index()
    {
        $listas = Lista::with('provincia')->get();
        
        return response()->json([
            'total' => $listas->count(),
            'listas' => $listas
        ]);
    }

    // Crear
    public function store(Request $request)
    {
        $validated = $request->validate([
            'idLista' => 'required|integer|unique:Lista,idLista',
            'nombre' => 'required|string|max:100',
            'alianza' => 'nullable|string|max:100',
            'cargoDiputado' => 'required|boolean',
            'cargoSenador' => 'required|boolean',
            'idProvincia' => 'required|integer|exists:Provincia,idProvincia',
        ]);

        $lista = Lista::create($validated);

        return response()->json([
            'mensaje' => 'Lista creada exitosamente',
            'lista' => $lista->load('provincia')
        ], 201);
    }

    // Mostrar uno
    public function show($id)
    {
        $lista = Lista::with(['provincia', 'candidatos'])
            ->findOrFail($id);
            
        return response()->json($lista);
    }

    // Actualizar
    public function update(Request $request, $id)
    {
        $lista = Lista::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'sometimes|string|max:100',
            'alianza' => 'nullable|string|max:100',
            'cargoDiputado' => 'sometimes|boolean',
            'cargoSenador' => 'sometimes|boolean',
            'idProvincia' => 'sometimes|integer|exists:Provincia,idProvincia',
        ]);

        $lista->update($validated);

        return response()->json([
            'mensaje' => 'Lista actualizada exitosamente',
            'lista' => $lista->load('provincia')
        ]);
    }

    // Eliminar
    public function destroy($id)
    {
        $lista = Lista::findOrFail($id);
        
        // Verificar si tiene candidatos
        if ($lista->candidatos()->exists()) {
            return response()->json([
                'error' => 'No se puede eliminar la lista porque tiene candidatos asociados'
            ], 400);
        }

        $lista->delete();

        return response()->json([
            'mensaje' => 'Lista eliminada correctamente'
        ]);
    }
}
