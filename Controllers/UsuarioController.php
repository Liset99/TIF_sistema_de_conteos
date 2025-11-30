<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function iniciarSesion($usuario, $password)
    {
        $user = Usuario::where('nombreDeUsuario', $usuario)->first();

        if (!$user) return "Usuario no encontrado";

        if ($user->contraseña !== $password)
            return "Contraseña incorrecta";

        return "Inicio de sesión exitoso";
    }
}
