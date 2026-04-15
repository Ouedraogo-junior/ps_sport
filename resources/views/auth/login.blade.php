@extends('layouts.app')

@section('title', 'Connexion')
@section('meta_description', 'Connectez-vous pour accéder à vos pronostics sportifs du jour.')

@section('content')

<div style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 2rem 1rem;">

    <div style="width: 100%; max-width: 420px;">

        {{-- En-tête --}}
        <div style="text-align: center; margin-bottom: 2.5rem;">
            <div style="font-family: var(--font-display); font-size: 0.75rem; font-weight: 700; letter-spacing: 0.15em; text-transform: uppercase; color: var(--c-green); margin-bottom: 0.75rem;">
                ● Espace membre
            </div>
            <h1 style="font-family: var(--font-display); font-size: 2.4rem; font-weight: 800; letter-spacing: 0.03em; text-transform: uppercase; line-height: 1; margin-bottom: 0.75rem;">
                Connexion
            </h1>
            <p style="color: var(--c-muted); font-size: 0.9rem;">
                Accédez à vos coupons et pronostics du jour.
            </p>
        </div>

        {{-- Formulaire --}}
        <div style="background: var(--c-bg2); border: 1px solid var(--c-border); padding: 2rem;">

            <form method="POST" action="{{ route('login.store') }}">
                @csrf

                {{-- Téléphone --}}
                <div style="margin-bottom: 1.25rem;">
                    <label style="display: block; font-family: var(--font-display); font-size: 0.75rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--c-muted); margin-bottom: 0.5rem;">
                        Numéro de téléphone
                    </label>
                    <div style="position: relative;">
                        <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--c-muted); font-size: 0.9rem;">
                            🇧🇫
                        </span>
                        <input
                            type="tel"
                            name="telephone"
                            value="{{ old('telephone') }}"
                            placeholder="70 XX XX XX"
                            autocomplete="tel"
                            autofocus
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

                {{-- Mot de passe --}}
                <div style="margin-bottom: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                        <label style="font-family: var(--font-display); font-size: 0.75rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--c-muted);">
                            Mot de passe
                        </label>

                            <span style="font-size: 0.78rem; color: var(--c-muted);">
                                Oublié ?
                                @php $numeroWhatsApp = \App\Models\Parametre::get('whatsapp_numero', ''); @endphp
                                @if($numeroWhatsApp)
                                        <a href="https://wa.me/{{ $numeroWhatsApp }}" target="_blank"
                                        style="color:var(--c-green); text-decoration:none;">
                                            WhatsApp
                                        </a>
                                    </span>
                                @endif
                            {{-- <a href="https://wa.me/22600000000" target="_blank"
                               style="color: var(--c-green); text-decoration: none;">
                                WhatsApp
                            </a>
                        </span> --}}
                    </div>
                    <input
                        type="password"
                        name="password"
                        placeholder="Votre mot de passe"
                        autocomplete="current-password"
                        style="width: 100%; background: var(--c-bg3); border: 1px solid var(--c-border); color: var(--c-text); padding: 11px 12px; font-family: var(--font-body); font-size: 1rem; outline: none; transition: border-color 0.2s;"
                        onfocus="this.style.borderColor='var(--c-green)'"
                        onblur="this.style.borderColor='var(--c-border)'"
                    >
                </div>

                {{-- Se souvenir de moi --}}
                <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 1.75rem;">
                    <input type="checkbox" name="remember" id="remember"
                           style="accent-color: var(--c-green); width: 15px; height: 15px; cursor: pointer;">
                    <label for="remember"
                           style="font-size: 0.85rem; color: var(--c-muted); cursor: pointer; user-select: none;">
                        Rester connecté
                    </label>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; padding: 13px; font-size: 1rem;">
                    Se connecter
                </button>

            </form>

        </div>

        {{-- Lien inscription --}}
        <div style="text-align: center; margin-top: 1.5rem; color: var(--c-muted); font-size: 0.9rem;">
            Pas encore de compte ?
            <a href="{{ route('register') }}" style="color: var(--c-green); text-decoration: none; font-weight: 600;">
                S'inscrire
            </a>
        </div>

        {{-- Mot de passe oublié --}}
        <div style="margin-top: 1.5rem; border: 1px solid var(--c-border); padding: 1rem 1.25rem; display: flex; gap: 0.75rem; align-items: flex-start;">
            <span style="font-size: 1.1rem; flex-shrink: 0;">🔑</span>
            <div style="font-size: 0.82rem; color: var(--c-muted); line-height: 1.6;">
                Mot de passe oublié ? Contactez-nous via
               @php $numeroWhatsApp = \App\Models\Parametre::get('whatsapp_numero', ''); @endphp
                                @if($numeroWhatsApp)
                                        <a href="https://wa.me/{{ $numeroWhatsApp }}" target="_blank"
                                        style="color:var(--c-green); text-decoration:none; font-weight:600;">
                                            WhatsApp
                                        </a>
                                    </span>
                                @endif
                avec votre numéro de téléphone et nous vous enverrons un nouveau mot de passe.
            </div>
        </div>

    </div>
</div>

@endsection