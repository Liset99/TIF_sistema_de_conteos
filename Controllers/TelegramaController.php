<?php

namespace App\Http\Controllers;

use App\Models\Telegrama;

class TelegramaController extends Controller
{
    public function validarSumaVotos($telegramaId)
    {
        $telegrama = Telegrama::findOrFail($telegramaId);

        $suma = array_sum($telegrama->votos);

        return $suma > 0;
    }

    public function obtenerVotosTotales($telegramaId)
    {
        $telegrama = Telegrama::findOrFail($telegramaId);

        return array_sum($telegrama->votos);
    }
}
