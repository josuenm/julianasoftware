<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('entrar');
    }


    public function entrar(Request $req)
    {
        $credenciais = $req->validate([
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'O email é obrigatório',
            'email.email' => 'O email não é válido!',
            'password.required' => 'A senha é obrigatória',
        ]);

        if (!User::where('email', $credenciais['email'])->exists()) {
            return back()->withErrors(['generalError' => 'Email ou senha não existe']);
        }

        if (Auth::attempt($credenciais)) {
            $req->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors(['generalError' => 'Algo deu errado, tente novamente']);
    }
}
