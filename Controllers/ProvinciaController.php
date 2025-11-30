<?php

namespace App\Http\Controllers;

use App\Models\Provincia;
use App\Models\Mesa;
use App\Models\Lista;
use Illuminate\Http\Request;

class ProvinciaController extends Controller
{
    public function agregarMesa(Request $request, $provinciaId)
    {
        Mesa::create([
            'numeroMesa' => $request->numeroMesa,
            'idProvincia' => $provinciaId,
            'votos_totales' => 0
        ]);

        return "Mesa agregada correctamente";
    }

    public function agregarLista(Request $request, $provinciaId)
    {
        Lista::create([
            'nombreLista' => $request->nombreLista,
            'idProvincia' => $provinciaId
        ]);

        return "Lista agregada correctamente";
    }

    public function calcularTotales($provinciaId)
    {
        $total = Mesa::where('idProvincia', $provinciaId)
                      ->sum('votos_totales');

        return $total;
    }
}
