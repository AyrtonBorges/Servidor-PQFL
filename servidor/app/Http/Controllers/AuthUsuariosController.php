<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuarios;
use Illuminate\Support\Facades\Hash;

class AuthUsuariosController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'usuario' => 'required',
            'senha' => 'required',
        ]);

        $usuarios = Usuarios::where('usuario', $request->usuario)->first();

        if (!$usuarios || !Hash::check($request->senha, $usuarios->senha)) {
            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }

        $token = $usuarios->createToken('token-usuarios')->plainTextToken;

        return response()->json([
            'token' => $token,
            'usuarios' => $usuarios,
        ]);
    }

    public function perfil(Request $request)
    {
        return $request->user();
    }
}
