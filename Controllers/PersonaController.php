<?php

namespace App\Http\Controllers;

use App\Models\Persona;

class PersonaController extends Controller
{
    public function mostrarDatos($dni)
    {
        return Persona::findOrFail($dni);
    }
}
