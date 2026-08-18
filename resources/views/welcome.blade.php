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
        grid-template-columns: repeat(4, 1fr);
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

        @media (max-width: 900px) {
            .grid-plans {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 640px) {
            .grid-plans {
                grid-template-columns: 1fr;
            }
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
            Pronostics analysés quotidiennement, opportunités d’investissement rentables 
            et système d’affiliation pour générer des revenus passifs. 
            Paiement simple via Orange Money ou Moov Money.
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

               @if($plan->est_investissement)
                    <div style="margin-top:1rem; padding:0.75rem 1rem; background:rgba(255,215,0,0.06); border:1px solid rgba(255,215,0,0.25);">
                        <div style="font-family:var(--font-display); font-size:0.65rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-gold); margin-bottom:0.4rem;">
                            ◆ Investissement inclus
                        </div>
                        <div style="display:flex; justify-content:space-between; align-items:baseline; gap:0.5rem; margin-bottom:0.25rem;">
                            <span style="font-size:0.75rem; color:var(--c-muted);">Gain / jour</span>
                            <span style="font-family:var(--font-display); font-weight:800; font-size:0.95rem; color:var(--c-gold);">
                                {{ number_format($plan->gainJournalier(), 0, ',', ' ') }} FCFA
                            </span>
                        </div>
                        <div style="display:flex; justify-content:space-between; align-items:baseline; gap:0.5rem; margin-bottom:0.25rem;">
                            <span style="font-size:0.75rem; color:var(--c-muted);">Sur {{ $plan->duree_jours }} jours</span>
                            <span style="font-family:var(--font-display); font-weight:700; font-size:0.85rem; color:var(--c-text);">
                                {{ number_format($plan->gainTotal(), 0, ',', ' ') }} FCFA
                            </span>
                        </div>
                        @if($plan->seuil_retrait)
                            <div style="display:flex; justify-content:space-between; align-items:baseline; gap:0.5rem;">
                                <span style="font-size:0.75rem; color:var(--c-muted);">Seuil retrait</span>
                                <span style="font-family:var(--font-display); font-weight:700; font-size:0.8rem; color:var(--c-muted);">
                                    {{ $plan->seuilRetraitFormate() }}
                                </span>
                            </div>
                        @endif
                    </div>
                    <a href="{{ route('investissement.detail') }}"
                    style="display:block; text-align:center; margin-top:0.6rem; font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.06em; text-transform:uppercase; color:var(--c-green); text-decoration:none;">
                        En savoir plus →
                    </a>
                @endif
            </div>
        @endforeach
    </div>

    <div style="text-align:center; margin-top:1.5rem; font-size:0.82rem; color:var(--c-muted);">
        Paiement via 🟠 Orange Money &nbsp;•&nbsp; 🔵 Moov Money &nbsp;•&nbsp; Activation sous 24h
    </div>

</section>

{{-- ── INVESTISSEMENT --}}
@include('partials.home-investissement')

{{-- ── AFFILIATION ───────────────────────────────────── --}}
<div style="height:1px; background:linear-gradient(90deg, transparent, var(--c-border), transparent);"></div>

<section class="section-inner" style="padding:4rem 1.5rem; max-width:960px; margin:0 auto;">

    {{-- En-tête --}}
    <div style="text-align:center; margin-bottom:3rem;">
        <div style="font-family:var(--font-display); font-size:0.75rem; font-weight:700; letter-spacing:0.15em; text-transform:uppercase; color:var(--c-gold); margin-bottom:0.75rem;">
            ● Programme de parrainage
        </div>
        <h2 style="font-family:var(--font-display); font-size:clamp(1.5rem, 4vw, 2.2rem); font-weight:800; letter-spacing:0.03em; text-transform:uppercase; margin-bottom:1rem;">
            Gagnez de l'argent en<br>
            <span style="color:var(--c-gold);">parrainant vos proches</span>
        </h2>
        <p style="font-size:0.92rem; color:var(--c-muted); max-width:520px; margin:0 auto; line-height:1.8;">
            Partagez votre code unique. Chaque ami qui s'abonne vous rapporte un bonus en cash — directement sur votre solde, retirable via Mobile Money.
        </p>
    </div>

    {{-- Mécanisme en 3 étapes --}}
    <div class="grid-3" style="margin-bottom:3rem;">

        <div style="background:var(--c-bg2); border:1px solid var(--c-border); padding:1.75rem; position:relative; overflow:hidden;">
            <div style="position:absolute; top:0; left:0; right:0; height:2px; background:var(--c-gold);"></div>
            <div style="font-family:var(--font-display); font-size:2.5rem; font-weight:800; color:rgba(255,214,0,0.08); line-height:1; margin-bottom:0.75rem;">01</div>
            <div style="font-size:1.5rem; margin-bottom:0.75rem;">🔗</div>
            <div style="font-family:var(--font-display); font-weight:700; font-size:0.92rem; letter-spacing:0.06em; text-transform:uppercase; margin-bottom:0.6rem; color:var(--c-text);">
                Obtenez votre code
            </div>
            <p style="font-size:0.83rem; color:var(--c-muted); line-height:1.7;">
                Dès votre premier abonnement, un code unique <span style="color:var(--c-gold); font-family:monospace; font-weight:700;">ZEN-XXXXXX</span> est généré automatiquement dans votre espace.
            </p>
        </div>

        <div style="background:var(--c-bg2); border:1px solid var(--c-border-g); padding:1.75rem; position:relative; overflow:hidden;">
            <div style="position:absolute; top:0; left:0; right:0; height:2px; background:var(--c-green);"></div>
            <div style="font-family:var(--font-display); font-size:2.5rem; font-weight:800; color:rgba(0,230,118,0.06); line-height:1; margin-bottom:0.75rem;">02</div>
            <div style="font-size:1.5rem; margin-bottom:0.75rem;">📲</div>
            <div style="font-family:var(--font-display); font-weight:700; font-size:0.92rem; letter-spacing:0.06em; text-transform:uppercase; margin-bottom:0.6rem; color:var(--c-text);">
                Partagez à vos contacts
            </div>
            <p style="font-size:0.83rem; color:var(--c-muted); line-height:1.7;">
                Envoyez votre lien sur WhatsApp, Facebook ou par SMS. Vos contacts s'inscrivent avec votre code et souscrivent un abonnement.
            </p>
        </div>

        <div style="background:var(--c-bg2); border:1px solid var(--c-border); padding:1.75rem; position:relative; overflow:hidden;">
            <div style="position:absolute; top:0; left:0; right:0; height:2px; background:var(--c-gold);"></div>
            <div style="font-family:var(--font-display); font-size:2.5rem; font-weight:800; color:rgba(255,214,0,0.08); line-height:1; margin-bottom:0.75rem;">03</div>
            <div style="font-size:1.5rem; margin-bottom:0.75rem;">💰</div>
            <div style="font-family:var(--font-display); font-weight:700; font-size:0.92rem; letter-spacing:0.06em; text-transform:uppercase; margin-bottom:0.6rem; color:var(--c-text);">
                Encaissez vos bonus
            </div>
            <p style="font-size:0.83rem; color:var(--c-muted); line-height:1.7;">
                Chaque filleul abonné = bonus crédité immédiatement. Plus vous parrainez, plus vous gagnez. Retrait via Orange Money ou Moov Money.
            </p>
        </div>

    </div>

    {{-- Bloc paliers — accroche visuelle --}}
    <div style="background:var(--c-bg2); border:1px solid var(--c-border-g); padding:2rem; margin-bottom:2rem; position:relative; overflow:hidden;">

        {{-- Fond décoratif --}}
        <div style="position:absolute; right:-40px; top:-40px; width:200px; height:200px; background:radial-gradient(circle, rgba(255,214,0,0.04), transparent); pointer-events:none;"></div>

        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1.5rem;">

            <div style="max-width:380px;">
                <div style="font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.12em; text-transform:uppercase; color:var(--c-gold); margin-bottom:0.6rem;">
                    ◆ Bonus par palier
                </div>
                <div style="font-family:var(--font-display); font-size:1.4rem; font-weight:800; letter-spacing:0.03em; text-transform:uppercase; margin-bottom:0.6rem;">
                    Plus vous parrainez,<br>plus les bonus explosent
                </div>
                <p style="font-size:0.83rem; color:var(--c-muted); line-height:1.7;">
                    En plus du bonus par filleul, des paliers débloquent des primes exceptionnelles quand vous atteignez des seuils — 10, 25, 50 filleuls...
                </p>
            </div>

            {{-- Illustration paliers --}}
            <div style="display:flex; flex-direction:column; gap:0.5rem; min-width:220px;">
                @php
                    $paliers = [
                        ['seuil' => '1 filleul',   'label' => 'Bonus filleul',  'color' => 'var(--c-green)', 'width' => '30%'],
                        ['seuil' => '10 filleuls',  'label' => 'Palier Bronze',  'color' => 'var(--c-gold)',  'width' => '55%'],
                        ['seuil' => '25 filleuls',  'label' => 'Palier Argent',  'color' => 'var(--c-gold)',  'width' => '75%'],
                        ['seuil' => '50 filleuls',  'label' => 'Palier Or',      'color' => 'var(--c-gold)',  'width' => '100%'],
                    ];
                @endphp
                @foreach($paliers as $palier)
                    <div>
                        <div style="display:flex; justify-content:space-between; font-family:var(--font-display); font-size:0.7rem; font-weight:700; letter-spacing:0.06em; text-transform:uppercase; color:var(--c-muted); margin-bottom:4px;">
                            <span>{{ $palier['seuil'] }}</span>
                            <span style="color:{{ $palier['color'] }};">{{ $palier['label'] }}</span>
                        </div>
                        <div style="height:5px; background:var(--c-bg3); border-radius:2px; overflow:hidden;">
                            <div style="height:100%; width:{{ $palier['width'] }}; background:{{ $palier['color'] }}; transition:width 0.5s;"></div>
                        </div>
                    </div>
                @endforeach
                <div style="font-size:0.72rem; color:var(--c-muted); margin-top:0.25rem; text-align:right;">
                    Montants définis par l'équipe ZENBET
                </div>
            </div>

        </div>
    </div>

    {{-- Chiffres clés accroche --}}
    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; margin-bottom:2.5rem;">
        <div style="background:var(--c-bg2); border:1px solid var(--c-border); padding:1.25rem; text-align:center;">
            <div style="font-family:var(--font-display); font-size:1.75rem; font-weight:800; color:var(--c-gold);">100%</div>
            <div style="font-size:0.75rem; color:var(--c-muted); margin-top:0.3rem; font-family:var(--font-display); letter-spacing:0.06em; text-transform:uppercase;">Cash réel</div>
        </div>
        <div style="background:var(--c-bg2); border:1px solid var(--c-border); padding:1.25rem; text-align:center;">
            <div style="font-family:var(--font-display); font-size:1.75rem; font-weight:800; color:var(--c-green);">Illimité</div>
            <div style="font-size:0.75rem; color:var(--c-muted); margin-top:0.3rem; font-family:var(--font-display); letter-spacing:0.06em; text-transform:uppercase;">Filleuls possibles</div>
        </div>
        <div style="background:var(--c-bg2); border:1px solid var(--c-border); padding:1.25rem; text-align:center;">
            <div style="font-family:var(--font-display); font-size:1.75rem; font-weight:800; color:var(--c-text);">Mobile<br>Money</div>
            <div style="font-size:0.75rem; color:var(--c-muted); margin-top:0.3rem; font-family:var(--font-display); letter-spacing:0.06em; text-transform:uppercase;">Retrait facile</div>
        </div>
    </div>

    {{-- CTA --}}
    <div style="text-align:center;">
        <a href="{{ route('register') }}" class="btn-primary" style="padding:13px 36px; font-size:0.95rem;">
            Démarrer et obtenir mon code →
        </a>
        <div style="margin-top:0.75rem; font-size:0.78rem; color:var(--c-muted);">
            Code généré automatiquement après votre premier abonnement
        </div>
    </div>

</section>

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