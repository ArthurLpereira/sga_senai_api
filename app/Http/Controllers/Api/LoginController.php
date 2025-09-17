<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Colaboradore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Lida com a tentativa de login e retorna um token se for bem-sucedido.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email_colaborador' => 'required|email',
            'senha_colaborador' => 'required',
        ]);

        $colaborador = Colaboradore::where('email_colaborador', $request->email_colaborador)->first();

        if (! $colaborador || ! Hash::check($request->senha_colaborador, $colaborador->senha_colaborador)) {
            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }

        // Gera um novo token para o colaborador
        $token = $colaborador->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message'        => 'Login bem-sucedido!',
            'access_token'   => $token,
            'token_type'     => 'Bearer',
        ]);
    }

    /**
     * Faz o logout do usuário revogando o token atual.
     */
    public function logout(Request $request)
    {
        // Revoga (apaga) o token que foi usado para fazer esta requisição
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout realizado com sucesso!']);
    }
}
