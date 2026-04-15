@extends('layouts.app')

@section('title', 'Créer un compte')
@section('meta_description', 'Créez votre compte et abonnez-vous pour accéder aux pronostics sportifs premium.')

@section('content')

<div style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 2rem 1rem;">

    <div style="width: 100%; max-width: 440px;">

        {{-- En-tête --}}
        <div style="text-align: center; margin-bottom: 2.5rem;">
            <div style="font-family: var(--font-display); font-size: 0.75rem; font-weight: 700; letter-spacing: 0.15em; text-transform: uppercase; color: var(--c-green); margin-bottom: 0.75rem;">
                ● Nouveau compte
            </div>
            <h1 style="font-family: var(--font-display); font-size: 2.4rem; font-weight: 800; letter-spacing: 0.03em; text-transform: uppercase; line-height: 1; margin-bottom: 0.75rem;">
                Rejoignez-nous
            </h1>
            <p style="color: var(--c-muted); font-size: 0.9rem;">
                Créez votre compte puis souscrivez un abonnement<br>pour accéder aux coupons du jour.
            </p>
        </div>

        {{-- Formulaire --}}
        <div style="background: var(--c-bg2); border: 1px solid var(--c-border); padding: 2rem;">

            <form method="POST" action="{{ route('register.store') }}">
                @csrf

                {{-- Téléphone --}}
                <div style="margin-bottom: 1.25rem;">
                    <label style="display: block; font-family: var(--font-display); font-size: 0.75rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--c-muted); margin-bottom: 0.5rem;">
                        Numéro de téléphone <span style="color: var(--c-green);">*</span>
                    </label>
                    <div style="position: relative;">
                        <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--c-muted); font-size: 0.9rem; font-family: var(--font-display); font-weight: 600;">
                            🇧🇫
                        </span>
                        <input
                            type="tel"
                            name="telephone"
                            value="{{ old('telephone') }}"
                            placeholder="70 XX XX XX"
                            autocomplete="tel"
                            style="width: 100%; background: var(--c-bg3); border: 1px solid {{ $errors->has('telephone') ? 'var(--c-danger)' : 'var(--c-border)' }}; color: var(--c-text); padding: 11px 12px 11px 40px; font-family: var(--font-body); font-size: 1rem; outline: none; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='var(--c-green)'"
                            onblur="this.style.borderColor='{{ $errors->has('telephone') ? 'var(--c-danger)' : 'var(--c-border)' }}'"
                        >
                    </div>
                    @error('telephone')
                        <div style="color: var(--c-danger); font-size: 0.8rem; margin-top: 0.4rem; font-family: var(--font-display); font-weight: 600; letter-spacing: 0.04em;">
                            ✕ {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Nom (optionnel) --}}
                <div style="margin-bottom: 1.25rem;">
                    <label style="display: block; font-family: var(--font-display); font-size: 0.75rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--c-muted); margin-bottom: 0.5rem;">
                        Nom / Pseudo <span style="color: var(--c-muted); font-weight: 400;">(optionnel)</span>
                    </label>
                    <input
                        type="text"
                        name="nom"
                        value="{{ old('nom') }}"
                        placeholder="Ex: Koné Moussa"
                        style="width: 100%; background: var(--c-bg3); border: 1px solid var(--c-border); color: var(--c-text); padding: 11px 12px; font-family: var(--font-body); font-size: 1rem; outline: none; transition: border-color 0.2s;"
                        onfocus="this.style.borderColor='var(--c-green)'"
                        onblur="this.style.borderColor='var(--c-border)'"
                    >
                </div>

                {{-- Mot de passe --}}
                <div style="margin-bottom: 1.25rem;">
                    <label style="display: block; font-family: var(--font-display); font-size: 0.75rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--c-muted); margin-bottom: 0.5rem;">
                        Mot de passe <span style="color: var(--c-green);">*</span>
                    </label>
                    <input
                        type="password"
                        name="password"
                        placeholder="Minimum 6 caractères"
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

                {{-- Confirmation mot de passe --}}
                <div style="margin-bottom: 1.75rem;">
                    <label style="display: block; font-family: var(--font-display); font-size: 0.75rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--c-muted); margin-bottom: 0.5rem;">
                        Confirmer le mot de passe <span style="color: var(--c-green);">*</span>
                    </label>
                    <input
                        type="password"
                        name="password_confirmation"
                        placeholder="Répétez le mot de passe"
                        autocomplete="new-password"
                        style="width: 100%; background: var(--c-bg3); border: 1px solid var(--c-border); color: var(--c-text); padding: 11px 12px; font-family: var(--font-body); font-size: 1rem; outline: none; transition: border-color 0.2s;"
                        onfocus="this.style.borderColor='var(--c-green)'"
                        onblur="this.style.borderColor='var(--c-border)'"
                    >
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; padding: 13px; font-size: 1rem;">
                    Créer mon compte
                </button>

            </form>

        </div>

        {{-- Lien connexion --}}
        <div style="text-align: center; margin-top: 1.5rem; color: var(--c-muted); font-size: 0.9rem;">
            Déjà un compte ?
            <a href="{{ route('login') }}" style="color: var(--c-green); text-decoration: none; font-weight: 600;">
                Se connecter
            </a>
        </div>

        {{-- Info paiement --}}
        <div style="margin-top: 2rem; border: 1px solid var(--c-border); padding: 1rem 1.25rem; display: flex; gap: 0.75rem; align-items: flex-start;">
            <span style="font-size: 1.1rem; flex-shrink: 0;">💬</span>
            <div style="font-size: 0.82rem; color: var(--c-muted); line-height: 1.6;">
                Après inscription, payez votre abonnement via <strong style="color: var(--c-text);">Orange Money ou Moov Money</strong>
                et envoyez la capture sur notre WhatsApp pour activer votre accès.
            </div>
        </div>

    </div>
</div>

@endsection