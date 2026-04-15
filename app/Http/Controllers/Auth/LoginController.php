<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function showForm()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'telephone' => ['required', 'string'],
            'password'  => ['required', 'string'],
        ], [
            'telephone.required' => 'Le numéro de téléphone est obligatoire.',
            'password.required'  => 'Le mot de passe est obligatoire.',
        ]);

        // Rate limiting : max 5 tentatives par IP par minute
        $key = 'login.' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'telephone' => "Trop de tentatives. Réessayez dans {$seconds} secondes.",
            ]);
        }

        // Normaliser le numéro
        $telephone = preg_replace('/^(\+226|00226|226)/', '', $request->telephone);

        $credentials = [
            'telephone' => $telephone,
            'password'  => $request->password,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::clear($key);
            $request->session()->regenerate();

            // Vérifier que le compte n'est pas bloqué
            if (Auth::user()->isBloque()) {
                Auth::logout();
                return back()->withErrors([
                    'telephone' => 'Votre compte a été suspendu. Contactez-nous via WhatsApp.',
                ]);
            }

            // Rediriger admin vers le back-office
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->intended(route('dashboard'));
        }

        RateLimiter::hit($key, 60);

        return back()->withErrors([
            'telephone' => 'Numéro de téléphone ou mot de passe incorrect.',
        ])->withInput($request->except('password'));
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Vous avez été déconnecté.');
    }
}