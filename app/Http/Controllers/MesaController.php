<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mesa;

class MesaController extends Controller
{
    // Listar todos
    public function index()
    {
        $mesas = Mesa::with('provincia')->get();
        
        return response()->json([
            'total' => $mesas->count(),
            'mesas' => $mesas
        ]);
    }

    // Crear
    public function store(Request $request)
    {
        $validated = $request->validate([
            'idMesa' => 'required|integer|unique:Mesa,idMesa',
            'electores' => 'required|integer|min:0',
            'establecimiento' => 'required|string|max:255',
            'circuito' => 'required|string|max:100',
            'idProvincia' => 'required|integer|exists:Provincia,idProvincia',
        ]);

        $mesa = Mesa::create($validated);

        return response()->json([
            'mensaje' => 'Mesa creada exitosamente',
            'mesa' => $mesa->load('provincia')
        ], 201);
    }

    // Mostrar uno
    public function show($id)
    {
        $mesa = Mesa::with(['provincia', 'telegramas'])
            ->findOrFail($id);
            
        return response()->json($mesa);
    }

    // Actualizar
    public function update(Request $request, $id)
    {
        $mesa = Mesa::findOrFail($id);

        $validated = $request->validate([
            'electores' => 'sometimes|integer|min:0',
            'establecimiento' => 'sometimes|string|max:255',
            'circuito' => 'sometimes|string|max:100',
            'idProvincia' => 'sometimes|integer|exists:Provincia,idProvincia',
        ]);

        $mesa->update($validated);

        return response()->json([
            'mensaje' => 'Mesa actualizada exitosamente',
            'mesa' => $mesa->load('provincia')
        ]);
    }

    // Eliminar
    public function destroy($id)
    {
        $mesa = Mesa::findOrFail($id);
        
        // Verificar si tiene telegramas
        if ($mesa->telegramas()->exists()) {
            return response()->json([
                'error' => 'No se puede eliminar la mesa porque tiene telegramas asociados'
            ], 400);
        }

        $mesa->delete();

        return response()->json([
            'mensaje' => 'Mesa eliminada correctamente'
        ]);
    }
}