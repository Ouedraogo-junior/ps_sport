@extends('layouts.app')

@section('title', 'Bienvenue')
@section('meta_robots', 'noindex, nofollow')

@section('content')

<meta http-equiv="refresh" content="3;url={{ $telegramUrl }}">

<div style="min-height: 65vh; display: flex; align-items: center; justify-content: center; padding: 2rem 1rem;">

    <div style="width: 100%; max-width: 440px; text-align: center;">

        <div style="font-family: var(--font-display); font-size: 0.75rem; font-weight: 700; letter-spacing: 0.15em; text-transform: uppercase; color: var(--c-green); margin-bottom: 0.75rem;">
            ● Compte créé avec succès
        </div>

        <div style="width: 64px; height: 64px; background: rgba(34,158,217,0.12); border: 1px solid rgba(34,158,217,0.35); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem;">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="#229ED9"><path d="M21.9 3.6c-.3-.25-.75-.3-1.2-.13L2.7 10.6c-.5.2-.85.55-.85.95 0 .3.2.65.85.9l4.6 1.6 1.8 5.75c.15.5.5.6.75.6.2 0 .35-.07.5-.2l2.6-2.4 4.6 3.5c.3.25.6.35.85.35.55 0 .95-.45 1.05-1.05L22.3 4.75c.1-.5-.05-.95-.4-1.15zM8.9 13.35l9.35-6.9c.2-.15.4.1.2.25l-8 7.65-.3 3.1-1.25-4.1z"/></svg>
        </div>

        <h1 style="font-family: var(--font-display); font-size: 1.6rem; font-weight: 800; letter-spacing: 0.03em; text-transform: uppercase; line-height: 1.15; margin-bottom: 0.9rem;">
            Abonnez-vous à notre canal Telegram
        </h1>

        <p style="color: var(--c-text); font-size: 0.95rem; line-height: 1.7; max-width: 380px; margin: 0 auto 0.5rem;">
            Rejoignez le canal pour recevoir les <strong style="color: var(--c-text);">pronostics des matchs en live</strong> et ne rater aucune opportunité.
        </p>

        <p style="color: var(--c-muted); font-size: 0.82rem; margin-bottom: 1.75rem;">
            Vous allez être redirigé automatiquement dans quelques secondes…
        </p>

        <a href="{{ $telegramUrl }}"
           style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; width: 100%; max-width: 340px; background: #229ED9; color: #fff; padding: 13px 20px; font-family: var(--font-display); font-weight: 700; font-size: 0.9rem; letter-spacing: 0.04em; text-transform: uppercase; text-decoration: none; transition: opacity 0.2s;"
           onmouseover="this.style.opacity='0.88'" onmouseout="this.style.opacity='1'">
            Rejoindre le canal maintenant →
        </a>

    </div>
</div>

@push('scripts')
<script>
    setTimeout(function () {
        window.location.replace(@json($telegramUrl));
    }, 3000);
</script>
@endpush

@endsection