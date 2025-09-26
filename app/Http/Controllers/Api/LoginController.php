<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Colaboradore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Importe a facade de Autenticação
use Illuminate\Support\Facades\Hash; // Hash ainda é usado, mas indiretamente pelo Auth

class LoginController extends Controller
{
    /**
     * Lida com a tentativa de login e retorna um token e os dados do usuário se for bem-sucedido.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email_colaborador' => 'required|email',
            'senha_colaborador' => 'required',
        ]);

        // Tenta autenticar o usuário. O Auth::attempt já faz a busca pelo email e a verificação do hash da senha.
        if (!Auth::attempt(['email_colaborador' => $credentials['email_colaborador'], 'password' => $credentials['senha_colaborador']])) {
            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }

        // Se a autenticação foi bem-sucedida, pega o usuário
        $colaborador = Colaboradore::where('email_colaborador', $request->email_colaborador)->firstOrFail();

        // Gera um novo token para o colaborador
        $token = $colaborador->createToken('auth-token')->plainTextToken;

        // ---> AJUSTE PRINCIPAL: Adicionado o objeto 'user' à resposta <---
        return response()->json([
            'access_token'   => $token,
            'token_type'     => 'Bearer',
            'user'           => [
                'id' => $colaborador->id,
                'nome_colaborador' => $colaborador->nome_colaborador,
                'email_colaborador' => $colaborador->email_colaborador,
            ]
        ]);
    }

    /**
     * Faz o logout do usuário revogando o token atual.
     */
    public function logout(Request $request)
    {
        // Seu método de logout já estava perfeito!
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout realizado com sucesso!']);
    }
}
