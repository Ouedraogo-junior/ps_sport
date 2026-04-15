@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<style>
    /* ── Grilles responsive ───────────────────────────── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .tables-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    /* ── Table responsive ─────────────────────────────── */
    .table-wrapper {
        background: var(--c-bg2);
        border: 1px solid var(--c-border);
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .table-row-mobile {
        display: none;
    }

    @media (max-width: 900px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .tables-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 560px) {
        .stats-grid {
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }

        /* Cache le tableau classique, affiche les cartes mobile */
        .table-desktop { display: none; }
        .table-row-mobile { display: block; }

        .stat-value {
            font-size: 1.6rem !important;
        }
    }
</style>

{{-- ── STATS CARDS ─────────────────────────────────────────── --}}
<div class="stats-grid">

    <div class="stat-card" style="{{ $paiementsEnAttente > 0 ? 'border-color:rgba(255,61,61,0.3)' : '' }}">
        <div class="stat-label">Paiements en attente</div>
        <div class="stat-value {{ $paiementsEnAttente > 0 ? 'red' : '' }}">
            {{ $paiementsEnAttente }}
        </div>
        @if($paiementsEnAttente > 0)
            <a href="{{ route('admin.paiements.index') }}"
               style="display:inline-block; margin-top:0.75rem; font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-danger); text-decoration:none;">
                Traiter →
            </a>
        @endif
    </div>

    <div class="stat-card">
        <div class="stat-label">Abonnés actifs</div>
        <div class="stat-value green">{{ $abonnesActifs }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Total utilisateurs</div>
        <div class="stat-value">{{ $totalUtilisateurs }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Coupons publiés aujourd'hui</div>
        <div class="stat-value gold">{{ $couponsDuJour }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Revenus — {{ now()->locale('fr')->isoFormat('MMMM YYYY') }}</div>
        <div class="stat-value green" style="font-size:1.5rem;">
            {{ number_format($revenusMois, 0, '.', ' ') }} <span style="font-size:1rem; color:var(--c-muted);">XOF</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Taux de réussite <span style="font-weight:400;">(30 jours)</span></div>
        <div class="stat-value {{ $tauxReussite >= 60 ? 'green' : ($tauxReussite >= 40 ? 'gold' : 'red') }}">
            {{ $tauxReussite }}<span style="font-size:1.2rem;">%</span>
        </div>
        <div style="margin-top:0.5rem; font-size:0.75rem; color:var(--c-muted); font-family:var(--font-display); letter-spacing:0.05em;">
            {{ $couponsGagnes }} gagnés / {{ $couponsTermines }} terminés
        </div>
        <div style="margin-top:0.75rem; height:3px; background:var(--c-bg3); border-radius:2px; overflow:hidden;">
            <div style="height:100%; width:{{ $tauxReussite }}%; background:{{ $tauxReussite >= 60 ? 'var(--c-green)' : ($tauxReussite >= 40 ? 'var(--c-gold)' : 'var(--c-danger)') }}; transition:width 0.5s;"></div>
        </div>
    </div>

</div>

{{-- ── SÉPARATEUR ───────────────────────────────────────────── --}}
<div style="height:1px; background:linear-gradient(90deg, transparent, var(--c-border), transparent); margin-bottom:2rem;"></div>

{{-- ── TABLEAUX RÉCENTS ─────────────────────────────────────── --}}
<div class="tables-grid">

    {{-- Derniers paiements en attente --}}
    <div>
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.75rem;">
            <div style="font-family:var(--font-display); font-weight:700; font-size:0.85rem; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-text);">
                Paiements en attente
            </div>
            <a href="{{ route('admin.paiements.index') }}"
               style="font-family:var(--font-display); font-size:0.72rem; font-weight:600; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-muted); text-decoration:none; transition:color 0.2s;"
               onmouseover="this.style.color='var(--c-green)'"
               onmouseout="this.style.color='var(--c-muted)'">
                Voir tout →
            </a>
        </div>

        <div style="background:var(--c-bg2); border:1px solid var(--c-border); overflow:hidden;">
            @forelse($derniersPaiements as $paiement)
                <div style="display:flex; align-items:center; justify-content:space-between; gap:0.75rem; padding:10px 14px; border-bottom:1px solid var(--c-border); flex-wrap:wrap;">
                    <div style="min-width:0;">
                        <div style="font-family:var(--font-display); font-weight:600; font-size:0.88rem; letter-spacing:0.04em; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                            {{ $paiement->user->nom ?? $paiement->user->telephone }}
                        </div>
                        <div style="font-size:0.78rem; color:var(--c-muted); margin-top:1px;">
                            {{ ucfirst($paiement->operateur) }} — {{ $paiement->montantFormate() }}
                        </div>
                    </div>
                    <div style="text-align:right; flex-shrink:0;">
                        <span class="pill pill-yellow">En attente</span>
                        <div style="font-size:0.72rem; color:var(--c-muted); margin-top:4px;">
                            {{ $paiement->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
            @empty
                <div style="padding:2rem; text-align:center; color:var(--c-muted); font-family:var(--font-display); font-size:0.82rem; letter-spacing:0.06em; text-transform:uppercase;">
                    Aucun paiement en attente
                </div>
            @endforelse
        </div>
    </div>

    {{-- Derniers coupons --}}
    <div>
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.75rem;">
            <div style="font-family:var(--font-display); font-weight:700; font-size:0.85rem; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-text);">
                Derniers coupons
            </div>
            <a href="{{ route('admin.coupons.index') }}"
               style="font-family:var(--font-display); font-size:0.72rem; font-weight:600; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-muted); text-decoration:none; transition:color 0.2s;"
               onmouseover="this.style.color='var(--c-green)'"
               onmouseout="this.style.color='var(--c-muted)'">
                Voir tout →
            </a>
        </div>

        <div style="background:var(--c-bg2); border:1px solid var(--c-border); overflow:hidden;">
            @forelse($derniersCoupons as $coupon)
                <div style="display:flex; align-items:center; justify-content:space-between; gap:0.75rem; padding:10px 14px; border-bottom:1px solid var(--c-border); flex-wrap:wrap;">
                    <div style="min-width:0;">
                        <div style="font-family:var(--font-display); font-weight:600; font-size:0.88rem; letter-spacing:0.04em; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                            {{ Str::limit($coupon->titre, 30) }}
                        </div>
                        <div style="display:flex; gap:4px; margin-top:4px; flex-wrap:wrap;">
                            @foreach($coupon->codes as $code)
                                <span class="pill pill-gray" style="font-size:0.62rem; padding:1px 6px;">
                                    {{ $code->bookmakerLabel() }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    <div style="text-align:right; flex-shrink:0;">
                        <span class="pill {{ $coupon->isPublie() ? 'pill-green' : 'pill-gray' }}">
                            {{ $coupon->isPublie() ? 'Publié' : 'Dépublié' }}
                        </span>
                        <div style="font-size:0.72rem; color:var(--c-muted); margin-top:4px;">
                            {{ $coupon->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
            @empty
                <div style="padding:2rem; text-align:center; color:var(--c-muted); font-family:var(--font-display); font-size:0.82rem; letter-spacing:0.06em; text-transform:uppercase;">
                    Aucun coupon créé
                </div>
            @endforelse
        </div>
    </div>

</div>

{{-- ── SÉPARATEUR ───────────────────────────────────────────── --}}
<div style="height:1px; background:linear-gradient(90deg, transparent, var(--c-border), transparent); margin:2rem 0;"></div>

{{-- ── HISTORIQUE TRANSACTIONS ─────────────────────────────── --}}
<div>
    <div style="font-family:var(--font-display); font-weight:700; font-size:0.85rem; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-text); margin-bottom:0.75rem;">
        Historique des transactions
    </div>

    {{-- ── Version tableau (desktop) ──────────────────── --}}
    <div class="table-wrapper table-desktop">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Utilisateur</th>
                    <th>Plan</th>
                    <th>Montant</th>
                    <th>Source</th>
                </tr>
            </thead>
            <tbody>
                @forelse($historiqueTransactions as $t)
                    <tr>
                        <td style="font-size:0.82rem; white-space:nowrap; color:var(--c-muted);">
                            {{ \Carbon\Carbon::parse($t['date'])->format('d/m/Y H:i') }}
                        </td>
                        <td style="font-family:var(--font-display); font-weight:600; font-size:0.88rem;">
                            {{ $t['user'] }}
                        </td>
                        <td>
                            <span class="pill pill-gold" style="text-transform:capitalize;">
                                {{ $t['plan'] }}
                            </span>
                        </td>
                        <td style="font-family:var(--font-display); font-weight:700; color:var(--c-green); font-size:0.9rem; white-space:nowrap;">
                            {{ number_format($t['montant'], 0, '.', ' ') }} XOF
                        </td>
                        <td>
                            @if($t['source'] === 'whatsapp')
                                <span class="pill pill-green">WhatsApp</span>
                            @else
                                <span class="pill pill-gray">Formulaire</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center; padding:3rem; color:var(--c-muted); font-family:var(--font-display); letter-spacing:0.06em; text-transform:uppercase; font-size:0.85rem;">
                            Aucune transaction
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ── Version cartes (mobile) ─────────────────────── --}}
    <div class="table-row-mobile">
        @forelse($historiqueTransactions as $t)
            <div style="background:var(--c-bg2); border:1px solid var(--c-border); border-top:none; padding:0.875rem 1rem;">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:0.5rem; margin-bottom:0.5rem;">
                    <div style="font-family:var(--font-display); font-weight:700; font-size:0.9rem; letter-spacing:0.04em;">
                        {{ $t['user'] }}
                    </div>
                    <span style="font-family:var(--font-display); font-weight:700; color:var(--c-green); font-size:0.9rem; white-space:nowrap;">
                        {{ number_format($t['montant'], 0, '.', ' ') }} XOF
                    </span>
                </div>
                <div style="display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap;">
                    <span class="pill pill-gold" style="text-transform:capitalize;">{{ $t['plan'] }}</span>
                    @if($t['source'] === 'whatsapp')
                        <span class="pill pill-green">WhatsApp</span>
                    @else
                        <span class="pill pill-gray">Formulaire</span>
                    @endif
                    <span style="font-size:0.75rem; color:var(--c-muted); margin-left:auto;">
                        {{ \Carbon\Carbon::parse($t['date'])->format('d/m/Y H:i') }}
                    </span>
                </div>
            </div>
        @empty
            <div style="background:var(--c-bg2); border:1px solid var(--c-border); padding:2rem; text-align:center; color:var(--c-muted); font-family:var(--font-display); letter-spacing:0.06em; text-transform:uppercase; font-size:0.85rem;">
                Aucune transaction
            </div>
        @endforelse
    </div>

</div>

@endsection