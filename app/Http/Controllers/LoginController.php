<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LoginController extends Controller
{

    public function showFormLogin()
    {
        return Inertia::render('Auth/Login');
    }

    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            if ($request->user()->temporal_password != null) {
                $id = $request->user()->id;
                Auth::logout();
                return redirect()->route('password_create.index', ['id' => $id]);
            } else {
                return redirect()->route('inicio');
            }
        } else {
            return back()->with([
                'message' => 'Datos invalidos',
            ]);
        }
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('login.show')->with(['message' => 'Sesión cerrada']);
    }
}
