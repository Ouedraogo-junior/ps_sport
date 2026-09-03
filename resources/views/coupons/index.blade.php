@extends('layouts.app')

@section('title', 'Coupons du jour')

@section('content')
<div style="max-width:1200px; margin:0 auto; padding:2rem 1.5rem;">

    {{-- Header --}}
    <div style="margin-bottom:2rem;">
        <h1 style="font-family:var(--font-display); font-size:2rem; font-weight:800; text-transform:uppercase; letter-spacing:0.05em;">
            Coupons <span style="color:var(--c-green)">du jour</span>
        </h1>
        <p style="color:var(--c-muted); margin-top:0.25rem; font-size:0.9rem;">
            {{ $coupons->total() }} coupon(s) disponible(s)
        </p>
    </div>

    @if($coupons->isEmpty())
        <div style="background:var(--c-bg2); border:1px solid var(--c-border); padding:3rem; text-align:center;">
            <div style="font-size:2rem; margin-bottom:1rem;">⏳</div>
            <p style="font-family:var(--font-display); font-size:1.1rem; text-transform:uppercase; letter-spacing:0.06em; color:var(--c-muted);">
                Aucun coupon publié pour le moment
            </p>
            <p style="color:var(--c-muted); font-size:0.85rem; margin-top:0.5rem;">
                Revenez plus tard, les coupons sont publiés quotidiennement.
            </p>
        </div>
    @else
        <div style="display:flex; flex-direction:column; gap:1rem;">
            @foreach($coupons as $coupon)
                <a href="{{ route('coupons.show', $coupon) }}"
                   style="display:block; text-decoration:none; color:inherit;">
                    <div class="card coupon-card" style="padding:1.5rem;">

                        {{-- Infos principales --}}
                        <div class="coupon-card-main">
                            <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:0.5rem; flex-wrap:wrap;">
                                {{-- Badge risque --}}
                                <span class="badge-{{ $coupon->niveau_risque }}"
                                      style="font-family:var(--font-display); font-size:0.7rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; padding:2px 10px; border-radius:2px;">
                                    {{ $coupon->niveauRisqueLabel() }}
                                </span>

                                {{-- Statut résultat --}}
                                @if($coupon->statut_resultat !== 'en_attente')
                                    <span class="badge-{{ $coupon->statut_resultat }}"
                                          style="font-family:var(--font-display); font-size:0.75rem; font-weight:700; letter-spacing:0.06em; text-transform:uppercase;">
                                        @if($coupon->statut_resultat === 'gagne') ✓ Gagné
                                        @elseif($coupon->statut_resultat === 'perdu') ✕ Perdu
                                        @elseif($coupon->statut_resultat === 'en_cours') ● En cours
                                        @else Annulé
                                        @endif
                                    </span>
                                @endif
                            </div>

                            <h2 style="font-family:var(--font-display); font-size:1.25rem; font-weight:700; text-transform:uppercase; letter-spacing:0.04em; margin-bottom:0.35rem;">
                                {{ $coupon->titre }}
                            </h2>

                            @if($coupon->description)
                                <p style="color:var(--c-muted); font-size:0.875rem; line-height:1.5;">
                                    {{ Str::limit($coupon->description, 100) }}
                                </p>
                            @endif
                        </div>

                        {{-- Méta droite --}}
                        <div class="coupon-card-meta">

                            {{-- Nombre de sélections --}}
                            @if($coupon->selections->count() > 0)
                                <div style="font-family:var(--font-display); font-size:0.8rem; color:var(--c-muted); text-transform:uppercase; letter-spacing:0.06em;">
                                    {{ $coupon->selections->count() }} match(s)
                                </div>
                            @endif

                            {{-- Bookmakers disponibles --}}
                            @if($coupon->codes->count() > 0)
                                <div style="display:flex; gap:0.4rem; flex-wrap:wrap; justify-content:flex-end;">
                                    @foreach($coupon->codes as $code)
                                        <span style="font-family:var(--font-display); font-size:0.65rem; font-weight:700; letter-spacing:0.06em; text-transform:uppercase; padding:2px 7px; background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-muted);">
                                            {{ $code->bookmakerLabel() }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Date --}}
                            <div style="font-family:var(--font-display); font-size:0.75rem; color:var(--c-muted); letter-spacing:0.04em;">
                                {{ $coupon->publie_le->locale('fr')->isoFormat('D MMM à HH[h]mm') }}
                            </div>

                            {{-- Flèche --}}
                            <span class="coupon-card-arrow" style="color:var(--c-green); font-size:1.1rem;">→</span>
                        </div>

                    </div>
                </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($coupons->hasPages())
            <div style="margin-top:2rem; display:flex; justify-content:center; gap:0.5rem;">
                {{ $coupons->links() }}
            </div>
        @endif
    @endif

</div>

<style>
    .coupon-card {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1.25rem;
    }
    .coupon-card-main {
        flex: 1 1 240px;
        min-width: 0; /* permet au titre/description de rétrécir sans forcer de débordement */
    }
    .coupon-card-meta {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 0.6rem;
        flex: 0 0 auto;
    }

    @media (max-width: 640px) {
        .coupon-card {
            flex-direction: column;
            align-items: stretch;
            gap: 0;
        }
        .coupon-card-main {
            /* En colonne, le flex-basis de 240px (pensé pour la largeur en desktop)
               s'applique à la hauteur et forçait un vide énorme sous le texte. */
            flex: 1 1 auto;
        }
        .coupon-card-meta {
            flex-direction: row;
            flex-wrap: wrap;
            align-items: center;
            justify-content: flex-start;
            gap: 0.6rem 1rem;
            width: 100%;
            border-top: 1px solid var(--c-border);
            margin-top: 0.35rem;
            padding-top: 0.35rem;
        }
        /* La flèche est redondante sur mobile : toute la card est déjà cliquable */
        .coupon-card-arrow { display: none; }
    }
</style>

@endsection