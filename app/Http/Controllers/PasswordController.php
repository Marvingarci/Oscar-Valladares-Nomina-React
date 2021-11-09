<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PasswordController extends Controller
{
    public function showUpdatePassword(Request $request)
    {
        return Inertia::render('Users/CreateUserPassword', ['id' => $request->id]);

    }

    public function updatePassword(Request $request, User $user)
    {
        // dd($user);
        $request->validate([
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ], [
            'password.required' => "La contraseña es requerida.",
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password_confirmation.required' => 'La confirmación de la contraseña es requerida.',
        ]);

        $password = bcrypt($request->password);

        $user->password = $password;
        $user->temporal_password = null;
        $user->save();

        Auth::login($user);

        return redirect()->route('inicio')->with(["message" => "Contraseña Creada con Éxito"]);
    }

    public function showForgotPassword()
    {
        return Inertia::render('Auth/ForgotPassword');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
        ? redirect()->route('login.show')->with('status', __($status))
        : back()->withErrors(['email' => __($status)]);

    }

    public function showResetPassword($token)
    {
        return Inertia::render('Auth/ResetPassword', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {

        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener como mínino 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password_confirmation.required' => 'La confirmación de la contraseña es requerida.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status == Password::PASSWORD_RESET
        ? redirect()->route('login.show')->with('status', __($status))
        : back()->withErrors(['email' => [__($status)]]);

    }
}
