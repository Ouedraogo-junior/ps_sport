@extends('layouts.app')

@section('title', 'Accueil')

@section('content')

<style>
    /* ── Grilles responsive ───────────────────────────── */
    .grid-3 {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    .grid-plans {
        display: grid;
        grid-template-columns: repeat({{ count($plans) }}, 1fr);
        gap: 1rem;
    }

    .hero-stats {
        display: flex;
        justify-content: center;
        gap: 3rem;
        margin-top: 3.5rem;
        flex-wrap: wrap;
    }

    .hero-stat-divider {
        width: 1px;
        background: var(--c-border);
    }

    .hero-ctas {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    @media (max-width: 640px) {
        .grid-3 {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }

        .grid-plans {
            grid-template-columns: 1fr;
        }

        .hero-stats {
            gap: 1.5rem;
            margin-top: 2.5rem;
        }

        .hero-stat-divider {
            display: none;
        }

        .hero-ctas .btn-outline {
            width: 100%;
            justify-content: center;
        }

        .hero-ctas .btn-primary {
            width: 100%;
            justify-content: center;
        }

        .hero-section {
            padding: 3rem 1.25rem 2.5rem !important;
        }

        .section-inner {
            padding: 2.5rem 1.25rem !important;
        }
    }
</style>

{{-- ── HERO ─────────────────────────────────────────────── --}}
<section class="hero-section" style="position:relative; padding:5rem 1.5rem 4rem; text-align:center; overflow:hidden;">

    {{-- Fond grille --}}
    <div style="position:absolute; inset:0; background-image:linear-gradient(rgba(0,230,118,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(0,230,118,0.03) 1px, transparent 1px); background-size:40px 40px; pointer-events:none;"></div>

    {{-- Fond radial --}}
    <div style="position:absolute; inset:0; background:radial-gradient(ellipse 80% 50% at 50% 0%, rgba(0,230,118,0.06), transparent); pointer-events:none;"></div>

    <div style="position:relative; max-width:760px; margin:0 auto;">

        {{-- Badge --}}
        <div style="display:inline-flex; align-items:center; gap:0.5rem; background:rgba(0,230,118,0.08); border:1px solid rgba(0,230,118,0.2); padding:5px 14px; margin-bottom:1.5rem;">
            <span style="width:6px; height:6px; background:var(--c-green); border-radius:50%; display:inline-block; animation:pulse-dot 2s infinite;"></span>
            <span style="font-family:var(--font-display); font-size:0.75rem; font-weight:700; letter-spacing:0.12em; text-transform:uppercase; color:var(--c-green);">
                Pronostics mis à jour quotidiennement
            </span>
        </div>

        {{-- Titre --}}
        <h1 style="font-family:var(--font-display); font-size:clamp(2rem, 6vw, 4.5rem); font-weight:800; letter-spacing:0.02em; text-transform:uppercase; line-height:1.05; margin-bottom:1.5rem;">
            Les meilleurs pronostics<br>
            <span style="color:var(--c-green);">sportifs</span> au Burkina
        </h1>

        <p style="font-size:1rem; color:var(--c-muted); max-width:520px; margin:0 auto 2.5rem; line-height:1.8;">
            Coupons analysés quotidiennement. Paiement simple via Orange Money ou Moov Money. Accès immédiat après validation.
        </p>

        {{-- CTAs --}}
        <div class="hero-ctas">
            <a href="{{ route('register') }}" class="btn-primary" style="padding:13px 32px; font-size:1rem;">
                Démarrer maintenant →
            </a>
            <a href="{{ route('performances') }}" class="btn-outline" style="padding:13px 32px; font-size:1rem;">
                Voir les performances
            </a>
        </div>

        {{-- Stats rapides --}}
        <div class="hero-stats">
            <div>
                <div style="font-family:var(--font-display); font-size:2rem; font-weight:800; color:var(--c-green);">{{ $tauxReussite }}%</div>
                <div style="font-size:0.78rem; color:var(--c-muted); font-family:var(--font-display); letter-spacing:0.08em; text-transform:uppercase; margin-top:2px;">Taux de réussite</div>
            </div>
            <div class="hero-stat-divider"></div>
            <div>
                <div style="font-family:var(--font-display); font-size:2rem; font-weight:800; color:var(--c-gold);">{{ $totalCoupons }}+</div>
                <div style="font-size:0.78rem; color:var(--c-muted); font-family:var(--font-display); letter-spacing:0.08em; text-transform:uppercase; margin-top:2px;">Coupons publiés</div>
            </div>
            <div class="hero-stat-divider"></div>
            <div>
                <div style="font-family:var(--font-display); font-size:2rem; font-weight:800; color:var(--c-text);">{{ $totalAbonnes }}+</div>
                <div style="font-size:0.78rem; color:var(--c-muted); font-family:var(--font-display); letter-spacing:0.08em; text-transform:uppercase; margin-top:2px;">Abonnés actifs</div>
            </div>
        </div>

    </div>
</section>

{{-- ── SÉPARATEUR --}}
<div style="height:1px; background:linear-gradient(90deg, transparent, var(--c-border), transparent);"></div>

{{-- ── TAUX DE RÉUSSITE ────────────────────────────────── --}}
<section class="section-inner" style="padding:4rem 1.5rem; max-width:960px; margin:0 auto;">

    <div style="text-align:center; margin-bottom:2.5rem;">
        <div style="font-family:var(--font-display); font-size:0.75rem; font-weight:700; letter-spacing:0.15em; text-transform:uppercase; color:var(--c-green); margin-bottom:0.5rem;">● Performances</div>
        <h2 style="font-family:var(--font-display); font-size:clamp(1.5rem, 4vw, 2rem); font-weight:800; letter-spacing:0.03em; text-transform:uppercase;">
            Nos résultats parlent
        </h2>
    </div>

    <div class="grid-3">

        <div style="background:var(--c-bg2); border:1px solid var(--c-border); padding:1.75rem; text-align:center;">
            <div style="font-family:var(--font-display); font-size:3rem; font-weight:800; color:var(--c-green); line-height:1;">{{ $tauxReussite }}%</div>
            <div style="font-family:var(--font-display); font-size:0.75rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-top:0.75rem;">Taux de réussite</div>
            <div style="font-size:0.78rem; color:var(--c-muted); margin-top:0.4rem;">Sur les 30 derniers jours</div>
            <div style="margin-top:1rem; height:4px; background:var(--c-bg3); border-radius:2px; overflow:hidden;">
                <div style="height:100%; width:{{ $tauxReussite }}%; background:var(--c-green);"></div>
            </div>
        </div>

        <div style="background:var(--c-bg2); border:1px solid var(--c-border); padding:1.75rem; text-align:center;">
            <div style="font-family:var(--font-display); font-size:3rem; font-weight:800; color:var(--c-gold); line-height:1;">{{ $couponsGagnes }}</div>
            <div style="font-family:var(--font-display); font-size:0.75rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-top:0.75rem;">Coupons gagnés</div>
            <div style="font-size:0.78rem; color:var(--c-muted); margin-top:0.4rem;">Sur les 30 derniers jours</div>
        </div>

        <div style="background:var(--c-bg2); border:1px solid var(--c-border); padding:1.75rem; text-align:center;">
            <div style="font-family:var(--font-display); font-size:3rem; font-weight:800; color:var(--c-text); line-height:1;">{{ $couponsTermines }}</div>
            <div style="font-family:var(--font-display); font-size:0.75rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-top:0.75rem;">Coupons terminés</div>
            <div style="font-size:0.78rem; color:var(--c-muted); margin-top:0.4rem;">Sur les 30 derniers jours</div>
        </div>

    </div>

    {{-- Captures coupons gagnants --}}
    @if($captures->isNotEmpty())
        <div style="margin-top:2rem;">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem;">
                <div style="font-family:var(--font-display); font-size:0.78rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-muted);">
                    📷 Derniers coupons gagnants
                </div>
                @if($totalCaptures > 5)
                    <a href="{{ route('performances') }}" style="font-family:var(--font-display); font-size:0.75rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-green); text-decoration:none;">
                        Voir toutes →
                    </a>
                @endif
            </div>

            <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(160px, 1fr)); gap:0.75rem;">
                @foreach($captures as $coupon)
                    <a href="{{ Storage::url($coupon->capture_gagnant) }}" target="_blank"
                    style="display:block; border:1px solid var(--c-border); overflow:hidden; text-decoration:none;">
                        <img src="{{ Storage::url($coupon->capture_gagnant) }}"
                            style="width:100%; height:120px; object-fit:cover; display:block;">
                        <div style="padding:6px 8px; background:var(--c-bg2);">
                            <div style="font-family:var(--font-display); font-size:0.68rem; font-weight:700; letter-spacing:0.06em; text-transform:uppercase; color:var(--c-green);">✓ Gagné</div>
                            <div style="font-family:var(--font-body); font-size:0.75rem; color:var(--c-muted); margin-top:2px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                {{ Str::limit($coupon->titre, 30) }}
                            </div>
                            <div style="font-family:var(--font-display); font-size:0.68rem; color:var(--c-muted); margin-top:2px;">
                                {{ $coupon->publie_le?->format('d/m/Y') ?? $coupon->updated_at->format('d/m/Y') }}
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

</section>

{{-- ── SÉPARATEUR --}}
<div style="height:1px; background:linear-gradient(90deg, transparent, var(--c-border), transparent);"></div>

{{-- ── COMMENT ÇA MARCHE ───────────────────────────────── --}}
<section class="section-inner" style="padding:4rem 1.5rem; max-width:960px; margin:0 auto;">

    <div style="text-align:center; margin-bottom:2.5rem;">
        <div style="font-family:var(--font-display); font-size:0.75rem; font-weight:700; letter-spacing:0.15em; text-transform:uppercase; color:var(--c-green); margin-bottom:0.5rem;">● Simple & rapide</div>
        <h2 style="font-family:var(--font-display); font-size:clamp(1.5rem, 4vw, 2rem); font-weight:800; letter-spacing:0.03em; text-transform:uppercase;">
            Comment ça marche
        </h2>
    </div>

    <div class="grid-3">

        <div style="position:relative; background:var(--c-bg2); border:1px solid var(--c-border); padding:2rem 1.5rem;">
            <div style="font-family:var(--font-display); font-size:3rem; font-weight:800; color:rgba(0,230,118,0.1); line-height:1; margin-bottom:1rem;">01</div>
            <div style="font-size:1.75rem; margin-bottom:1rem;">📱</div>
            <div style="font-family:var(--font-display); font-weight:700; font-size:1rem; letter-spacing:0.05em; text-transform:uppercase; margin-bottom:0.75rem;">
                Choisissez un plan
            </div>
            <p style="font-size:0.85rem; color:var(--c-muted); line-height:1.7;">
                Sélectionnez le plan qui vous convient — hebdomadaire, mensuel ou premium.
            </p>
        </div>

        <div style="position:relative; background:var(--c-bg2); border:1px solid var(--c-border-g); padding:2rem 1.5rem;">
            <div style="font-family:var(--font-display); font-size:3rem; font-weight:800; color:rgba(0,230,118,0.1); line-height:1; margin-bottom:1rem;">02</div>
            <div style="font-size:1.75rem; margin-bottom:1rem;">💳</div>
            <div style="font-family:var(--font-display); font-weight:700; font-size:1rem; letter-spacing:0.05em; text-transform:uppercase; margin-bottom:0.75rem;">
                Payez via Mobile Money
            </div>
            <p style="font-size:0.85rem; color:var(--c-muted); line-height:1.7;">
                Payez via Orange Money ou Moov Money. Envoyez la capture sur WhatsApp ou uploadez-la directement.
            </p>
        </div>

        <div style="position:relative; background:var(--c-bg2); border:1px solid var(--c-border); padding:2rem 1.5rem;">
            <div style="font-family:var(--font-display); font-size:3rem; font-weight:800; color:rgba(0,230,118,0.1); line-height:1; margin-bottom:1rem;">03</div>
            <div style="font-size:1.75rem; margin-bottom:1rem;">🎯</div>
            <div style="font-family:var(--font-display); font-weight:700; font-size:1rem; letter-spacing:0.05em; text-transform:uppercase; margin-bottom:0.75rem;">
                Accédez aux coupons
            </div>
            <p style="font-size:0.85rem; color:var(--c-muted); line-height:1.7;">
                Recevez votre code d'accès et consultez les pronostics du jour depuis votre espace personnel.
            </p>
        </div>

    </div>

</section>

{{-- ── SÉPARATEUR --}}
<div style="height:1px; background:linear-gradient(90deg, transparent, var(--c-border), transparent);"></div>

{{-- ── PLANS & TARIFS ──────────────────────────────────── --}}
<section class="section-inner" style="padding:4rem 1.5rem; max-width:960px; margin:0 auto;">

    <div style="text-align:center; margin-bottom:2.5rem;">
        <div style="font-family:var(--font-display); font-size:0.75rem; font-weight:700; letter-spacing:0.15em; text-transform:uppercase; color:var(--c-green); margin-bottom:0.5rem;">● Tarifs</div>
        <h2 style="font-family:var(--font-display); font-size:clamp(1.5rem, 4vw, 2rem); font-weight:800; letter-spacing:0.03em; text-transform:uppercase;">
            Choisissez votre plan
        </h2>
    </div>

    <div class="grid-plans">
        @foreach($plans as $index => $plan)
            @php $isPopular = $index === 1; @endphp
            <div style="background:var(--c-bg2); border:1px solid {{ $isPopular ? 'var(--c-green)' : 'var(--c-border)' }}; padding:2rem 1.5rem; text-align:center; position:relative;">

                @if($isPopular)
                    <div style="position:absolute; top:-1px; left:50%; transform:translateX(-50%); background:var(--c-green); color:#000; font-family:var(--font-display); font-size:0.65rem; font-weight:800; letter-spacing:0.1em; text-transform:uppercase; padding:3px 12px; white-space:nowrap;">
                        Populaire
                    </div>
                @endif

                <div style="font-family:var(--font-display); font-weight:700; font-size:1rem; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-muted); margin-bottom:1rem;">
                    {{ $plan->nom }}
                </div>

                <div style="font-family:var(--font-display); font-size:2.5rem; font-weight:800; color:{{ $isPopular ? 'var(--c-green)' : 'var(--c-text)' }}; line-height:1; margin-bottom:0.25rem;">
                    {{ $plan->prixFormate() }}
                </div>

                <div style="font-size:0.78rem; color:var(--c-muted); margin-bottom:1.5rem;">
                    {{ $plan->duree_jours }} jours d'accès
                </div>

                <a href="{{ route('register') }}" class="btn-primary" style="width:100%; justify-content:center; padding:10px; {{ !$isPopular ? 'background:var(--c-bg3); color:var(--c-text); border:1px solid var(--c-border);' : '' }}">
                    S'abonner →
                </a>

            </div>
        @endforeach
    </div>

    <div style="text-align:center; margin-top:1.5rem; font-size:0.82rem; color:var(--c-muted);">
        Paiement via 🟠 Orange Money &nbsp;•&nbsp; 🔵 Moov Money &nbsp;•&nbsp; Activation sous 24h
    </div>

</section>

{{-- ── INVESTISSEMENT --}}
@include('partials.home-investissement')

{{-- ── CTA FINAL ───────────────────────────────────────── --}}
<section style="padding:4rem 1.5rem; text-align:center; background:var(--c-bg2); border-top:1px solid var(--c-border);">
    <h2 style="font-family:var(--font-display); font-size:clamp(1.5rem, 4vw, 2rem); font-weight:800; letter-spacing:0.03em; text-transform:uppercase; margin-bottom:1rem;">
        Prêt à gagner avec nous ?
    </h2>
    <p style="color:var(--c-muted); margin-bottom:2rem; font-size:0.95rem;">
        Rejoignez nos abonnés et accédez aux meilleurs pronostics du Burkina.
    </p>
    <a href="{{ route('register') }}" class="btn-primary" style="padding:14px 40px; font-size:1rem;">
        Créer mon compte →
    </a>
</section>

@endsection