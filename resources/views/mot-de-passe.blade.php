@extends('layouts.app')

@section('title', 'Modifier mon mot de passe')
@section('meta_robots', 'noindex, nofollow')

@section('content')

<div style="min-height: 70vh; display: flex; align-items: center; justify-content: center; padding: 2rem 1rem;">

    <div style="width: 100%; max-width: 420px;">

        {{-- En-tête --}}
        <div style="text-align: center; margin-bottom: 2rem;">
            <div style="font-family: var(--font-display); font-size: 0.75rem; font-weight: 700; letter-spacing: 0.15em; text-transform: uppercase; color: var(--c-green); margin-bottom: 0.75rem;">
                ● Sécurité du compte
            </div>
            <h1 style="font-family: var(--font-display); font-size: 2rem; font-weight: 800; letter-spacing: 0.03em; text-transform: uppercase; line-height: 1; margin-bottom: 0.75rem;">
                Mon mot de passe
            </h1>
            <p style="color: var(--c-muted); font-size: 0.9rem;">
                Modifiez votre mot de passe à tout moment depuis votre espace.
            </p>
        </div>

        {{-- Formulaire --}}
        <div style="background: var(--c-bg2); border: 1px solid var(--c-border); padding: 2rem;">

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('PUT')

                {{-- Mot de passe actuel --}}
                <div style="margin-bottom: 1.25rem;">
                    <label style="display: block; font-family: var(--font-display); font-size: 0.75rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--c-muted); margin-bottom: 0.5rem;">
                        Mot de passe actuel
                    </label>
                    <input
                        type="password"
                        name="mot_de_passe_actuel"
                        placeholder="Votre mot de passe actuel"
                        autocomplete="current-password"
                        autofocus
                        style="width: 100%; background: var(--c-bg3); border: 1px solid {{ $errors->has('mot_de_passe_actuel') ? 'var(--c-danger)' : 'var(--c-border)' }}; color: var(--c-text); padding: 11px 12px; font-family: var(--font-body); font-size: 1rem; outline: none; transition: border-color 0.2s;"
                        onfocus="this.style.borderColor='var(--c-green)'"
                        onblur="this.style.borderColor='{{ $errors->has('mot_de_passe_actuel') ? 'var(--c-danger)' : 'var(--c-border)' }}'"
                    >
                    @error('mot_de_passe_actuel')
                        <div style="color: var(--c-danger); font-size: 0.8rem; margin-top: 0.4rem; font-family: var(--font-display); font-weight: 600; letter-spacing: 0.04em;">
                            ✕ {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Nouveau mot de passe --}}
                <div style="margin-bottom: 1.25rem;">
                    <label style="display: block; font-family: var(--font-display); font-size: 0.75rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--c-muted); margin-bottom: 0.5rem;">
                        Nouveau mot de passe
                    </label>
                    <input
                        type="password"
                        name="password"
                        placeholder="Au moins 6 caractères"
                        autocomplete="new-password"
                        style="width: 100%; background: var(--c-bg3); border: 1px solid {{ $errors->has('password') ? 'var(--c-danger)' : 'var(--c-border)' }}; color: var(--c-text); padding: 11px 12px; font-family: var(--font-body); font-size: 1rem; outline: none; transition: border-color 0.2s;"
                        onfocus="this.style.borderColor='var(--c-green)'"
                        onblur="this.style.borderColor='{{ $errors->has('password') ? 'var(--c-danger)' : 'var(--c-border)' }}'"
                    >
                    @error('password')
                        <div style="color: var(--c-danger); font-size: 0.8rem; margin-top: 0.4rem; font-family: var(--font-display); font-weight: 600; letter-spacing: 0.04em;">
                            ✕ {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Confirmation --}}
                <div style="margin-bottom: 1.75rem;">
                    <label style="display: block; font-family: var(--font-display); font-size: 0.75rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--c-muted); margin-bottom: 0.5rem;">
                        Confirmer le nouveau mot de passe
                    </label>
                    <input
                        type="password"
                        name="password_confirmation"
                        placeholder="Ressaisissez le nouveau mot de passe"
                        autocomplete="new-password"
                        style="width: 100%; background: var(--c-bg3); border: 1px solid var(--c-border); color: var(--c-text); padding: 11px 12px; font-family: var(--font-body); font-size: 1rem; outline: none; transition: border-color 0.2s;"
                        onfocus="this.style.borderColor='var(--c-green)'"
                        onblur="this.style.borderColor='var(--c-border)'"
                    >
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; padding: 13px; font-size: 1rem;">
                    Enregistrer le nouveau mot de passe
                </button>

            </form>

        </div>

        {{-- Retour --}}
        <div style="text-align: center; margin-top: 1.5rem;">
            <a href="{{ route('dashboard') }}" style="color: var(--c-muted); text-decoration: none; font-size: 0.85rem;">
                ← Retour à mon espace
            </a>
        </div>

    </div>
</div>

@endsection