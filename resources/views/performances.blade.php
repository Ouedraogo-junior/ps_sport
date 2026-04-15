@extends('layouts.app')

@section('title', 'Performances')
@section('meta_description', 'Historique complet de nos pronostics sportifs — taux de réussite, coupons gagnés et perdus.')

@section('content')

<div style="max-width:960px; margin:0 auto; padding:2rem 1.5rem;">

    {{-- En-tête --}}
    <div style="margin-bottom:2rem;">
        <div style="font-family:var(--font-display); font-size:0.75rem; font-weight:700; letter-spacing:0.15em; text-transform:uppercase; color:var(--c-green); margin-bottom:0.5rem;">
            ● Transparence totale
        </div>
        <h1 style="font-family:var(--font-display); font-size:2rem; font-weight:800; letter-spacing:0.03em; text-transform:uppercase;">
            Nos performances
        </h1>
        <p style="color:var(--c-muted); margin-top:0.5rem; font-size:0.9rem;">
            Historique complet de tous nos coupons publiés — aucun résultat masqué.
        </p>
    </div>

    {{-- Stats globales --}}
    <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:1rem; margin-bottom:2rem;">

        <div style="background:var(--c-bg2); border:1px solid var(--c-border-g); padding:1.5rem; text-align:center;">
            <div style="font-family:var(--font-display); font-size:2.5rem; font-weight:800; color:var(--c-green); line-height:1;">{{ $tauxReussite }}%</div>
            <div style="font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-top:0.5rem;">Taux global</div>
            <div style="margin-top:0.75rem; height:3px; background:var(--c-bg3); overflow:hidden;">
                <div style="height:100%; width:{{ $tauxReussite }}%; background:var(--c-green);"></div>
            </div>
        </div>

        <div style="background:var(--c-bg2); border:1px solid var(--c-border); padding:1.5rem; text-align:center;">
            <div style="font-family:var(--font-display); font-size:2.5rem; font-weight:800; color:var(--c-gold); line-height:1;">{{ $taux30j }}%</div>
            <div style="font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-top:0.5rem;">Taux 30 jours</div>
            <div style="margin-top:0.75rem; height:3px; background:var(--c-bg3); overflow:hidden;">
                <div style="height:100%; width:{{ $taux30j }}%; background:var(--c-gold);"></div>
            </div>
        </div>

        <div style="background:var(--c-bg2); border:1px solid var(--c-border); padding:1.5rem; text-align:center;">
            <div style="font-family:var(--font-display); font-size:2.5rem; font-weight:800; color:var(--c-text); line-height:1;">{{ $couponsGagnes }}/{{ $couponsTermines }}</div>
            <div style="font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-top:0.5rem;">Gagnés / Terminés</div>
        </div>

    </div>

    {{-- Captures coupons gagnants --}}
    @if($captures->isNotEmpty())
    <div style="margin-bottom:2rem;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem;">
            <div style="font-family:var(--font-display); font-weight:700; font-size:0.85rem; letter-spacing:0.08em; text-transform:uppercase;">
                📷 Coupons gagnants
            </div>
            @if($totalCaptures > 5)
                <a href="{{ route('performances.captures') }}" style="font-family:var(--font-display); font-size:0.75rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-green); text-decoration:none;">
                    Voir toutes ({{ $totalCaptures }}) →
                </a>
            @endif
        </div>

        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(160px, 1fr)); gap:0.75rem;">
            @foreach($captures as $coupon)
                <a href="{{ Storage::url($coupon->capture_gagnant) }}" target="_blank"
                style="display:block; border:1px solid var(--c-border); overflow:hidden; position:relative; text-decoration:none;">
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

    {{-- Séparateur --}}
    <div style="height:1px; background:linear-gradient(90deg, transparent, var(--c-border), transparent); margin-bottom:2rem;"></div>

    {{-- Historique --}}
    <div style="margin-bottom:1rem; display:flex; align-items:center; justify-content:space-between;">
        <div style="font-family:var(--font-display); font-weight:700; font-size:0.85rem; letter-spacing:0.08em; text-transform:uppercase;">
            Historique des coupons
        </div>
        <div style="font-family:var(--font-display); font-size:0.78rem; color:var(--c-muted); letter-spacing:0.06em; text-transform:uppercase;">
            {{ $historique->total() }} coupon(s)
        </div>
    </div>

    <div style="background:var(--c-bg2); border:1px solid var(--c-border); overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; font-size:0.875rem;">
            <thead>
                <tr>
                    <th style="font-family:var(--font-display); font-weight:700; font-size:0.7rem; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); padding:10px 14px; text-align:left; border-bottom:1px solid var(--c-border); background:var(--c-bg3);">Date</th>
                    <th style="font-family:var(--font-display); font-weight:700; font-size:0.7rem; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); padding:10px 14px; text-align:left; border-bottom:1px solid var(--c-border); background:var(--c-bg3);">Coupon</th>
                    <th style="font-family:var(--font-display); font-weight:700; font-size:0.7rem; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); padding:10px 14px; text-align:left; border-bottom:1px solid var(--c-border); background:var(--c-bg3);">Risque</th>
                    <th style="font-family:var(--font-display); font-weight:700; font-size:0.7rem; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); padding:10px 14px; text-align:center; border-bottom:1px solid var(--c-border); background:var(--c-bg3);">Résultat</th>
                    <th style="font-family:var(--font-display); font-weight:700; font-size:0.7rem; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); padding:10px 14px; text-align:center; border-bottom:1px solid var(--c-border); background:var(--c-bg3);">Capture</th>
                </tr>
            </thead>
            <tbody>
                @forelse($historique as $coupon)
                    <tr style="border-bottom:1px solid var(--c-border);">

                        {{-- Date --}}
                        <td style="padding:12px 14px; color:var(--c-muted); font-size:0.82rem; white-space:nowrap;">
                            {{ $coupon->updated_at->format('d/m/Y') }}
                        </td>

                        {{-- Titre --}}
                        <td style="padding:12px 14px;">
                            <div style="font-family:var(--font-display); font-weight:600; font-size:0.9rem;">
                                {{ Str::limit($coupon->titre, 45) }}
                            </div>
                            @if($coupon->description)
                                <div style="font-size:0.78rem; color:var(--c-muted); margin-top:2px;">
                                    {{ Str::limit($coupon->description, 60) }}
                                </div>
                            @endif
                        </td>

                        {{-- Risque --}}
                        <td style="padding:12px 14px;">
                            <span style="font-family:var(--font-display); font-size:0.68rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; padding:2px 8px; border-radius:2px;"
                                  class="{{ match($coupon->niveau_risque ?? '') {
                                      'faible'  => 'badge-faible',
                                      'modere'  => 'badge-modere',
                                      'risque'  => 'badge-risque',
                                      default   => ''
                                  } }}">
                                {{ ucfirst($coupon->niveau_risque ?? '—') }}
                            </span>
                        </td>

                        {{-- Résultat --}}
                        <td style="padding:12px 14px; text-align:center;">
                            @if($coupon->statut_resultat === 'gagne')
                                <span style="font-family:var(--font-display); font-size:0.78rem; font-weight:800; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-green);">
                                    ✓ Gagné
                                </span>
                            @else
                                <span style="font-family:var(--font-display); font-size:0.78rem; font-weight:800; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-danger);">
                                    ✕ Perdu
                                </span>
                            @endif
                        </td>

                        {{-- Capture --}}
                        <td style="padding:12px 14px; text-align:center;">
                            @if($coupon->capture_gagnant)
                                <a href="{{ Storage::url($coupon->capture_gagnant) }}" target="_blank"
                                style="font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.06em; text-transform:uppercase; color:var(--c-gold); text-decoration:none;">
                                    📷
                                </a>
                            @else
                                <span style="color:var(--c-border);">—</span>
                            @endif
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align:center; padding:3rem; color:var(--c-muted); font-family:var(--font-display); letter-spacing:0.06em; text-transform:uppercase; font-size:0.85rem;">
                            Aucun résultat disponible pour le moment.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($historique->hasPages())
        <div style="display:flex; justify-content:center; gap:0.5rem; margin-top:1.5rem; flex-wrap:wrap;">
            @if($historique->onFirstPage())
                <span style="padding:6px 14px; background:var(--c-bg2); border:1px solid var(--c-border); color:var(--c-muted); font-family:var(--font-display); font-size:0.8rem; font-weight:700; letter-spacing:0.06em; text-transform:uppercase;">← Préc.</span>
            @else
                <a href="{{ $historique->previousPageUrl() }}" style="padding:6px 14px; background:var(--c-bg2); border:1px solid var(--c-border); color:var(--c-text); font-family:var(--font-display); font-size:0.8rem; font-weight:700; letter-spacing:0.06em; text-transform:uppercase; text-decoration:none; transition:border-color 0.2s;" onmouseover="this.style.borderColor='var(--c-green)'" onmouseout="this.style.borderColor='var(--c-border)'">← Préc.</a>
            @endif

            <span style="padding:6px 14px; background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-muted); font-family:var(--font-display); font-size:0.8rem; letter-spacing:0.06em; text-transform:uppercase;">
                Page {{ $historique->currentPage() }} / {{ $historique->lastPage() }}
            </span>

            @if($historique->hasMorePages())
                <a href="{{ $historique->nextPageUrl() }}" style="padding:6px 14px; background:var(--c-bg2); border:1px solid var(--c-border); color:var(--c-text); font-family:var(--font-display); font-size:0.8rem; font-weight:700; letter-spacing:0.06em; text-transform:uppercase; text-decoration:none; transition:border-color 0.2s;" onmouseover="this.style.borderColor='var(--c-green)'" onmouseout="this.style.borderColor='var(--c-border)'">Suiv. →</a>
            @else
                <span style="padding:6px 14px; background:var(--c-bg2); border:1px solid var(--c-border); color:var(--c-muted); font-family:var(--font-display); font-size:0.8rem; font-weight:700; letter-spacing:0.06em; text-transform:uppercase;">Suiv. →</span>
            @endif
        </div>
    @endif

    {{-- CTA --}}
    @guest
        <div style="margin-top:2.5rem; background:var(--c-bg2); border:1px solid var(--c-border-g); padding:2rem; text-align:center;">
            <div style="font-family:var(--font-display); font-weight:800; font-size:1.1rem; letter-spacing:0.06em; text-transform:uppercase; margin-bottom:0.75rem;">
                Accédez aux prochains coupons
            </div>
            <p style="font-size:0.85rem; color:var(--c-muted); margin-bottom:1.25rem;">
                Abonnez-vous et recevez nos pronostics quotidiennement.
            </p>
            <a href="{{ route('register') }}" class="btn-primary" style="padding:11px 28px;">
                S'abonner →
            </a>
        </div>
    @endguest

</div>

@endsection