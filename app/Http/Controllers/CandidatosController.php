<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidato;

class CandidatosController extends Controller
{
    // Listar todos
    public function index()
    {
        $candidatos = Candidato::with(['lista.provincia'])->get();
        
        return response()->json([
            'total' => $candidatos->count(),
            'candidatos' => $candidatos
        ]);
    }

    // Crear
    public function store(Request $request)
    {
        $validated = $request->validate([
            'idCandidato' => 'required|integer|unique:Candidato,idCandidato',
            'cargo' => 'required|in:DIPUTADOS,SENADORES',
            'dni' => 'required|integer',
            'idLista' => 'required|integer|exists:Lista,idLista',
        ]);

        $candidato = Candidato::create($validated);

        return response()->json([
            'mensaje' => 'Candidato creado exitosamente',
            'candidato' => $candidato->load('lista')
        ], 201);
    }

    // Mostrar uno
    public function show($id)
    {
        $candidato = Candidato::with(['lista.provincia'])
            ->findOrFail($id);
            
        return response()->json($candidato);
    }

    // Actualizar
    public function update(Request $request, $id)
    {
        $candidato = Candidato::findOrFail($id);

        $validated = $request->validate([
            'cargo' => 'sometimes|in:DIPUTADOS,SENADORES',
            'dni' => 'sometimes|integer',
            'idLista' => 'sometimes|integer|exists:Lista,idLista',
        ]);

        $candidato->update($validated);

        return response()->json([
            'mensaje' => 'Candidato actualizado exitosamente',
            'candidato' => $candidato->load('lista')
        ]);
    }

    // Eliminar
    public function destroy($id)
    {
        $candidato = Candidato::findOrFail($id);
        $candidato->delete();

        return response()->json([
            'mensaje' => 'Candidato eliminado correctamente'
        ]);
    }
}
