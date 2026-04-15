@extends('layouts.app')

@section('title', 'Calendrier des performances')

@section('content')

<div style="max-width:960px; margin:0 auto; padding:2rem 1.5rem;">

    {{-- En-tête --}}
    <div style="margin-bottom:2rem;">
        <div style="font-family:var(--font-display); font-size:0.75rem; font-weight:700; letter-spacing:0.15em; text-transform:uppercase; color:var(--c-green); margin-bottom:0.5rem;">
            ● Historique
        </div>
        <h1 style="font-family:var(--font-display); font-size:2rem; font-weight:800; letter-spacing:0.03em; text-transform:uppercase;">
            Calendrier des performances
        </h1>
        <p style="color:var(--c-muted); margin-top:0.5rem; font-size:0.9rem;">
            Résultats de nos coupons jour par jour.
        </p>
    </div>

    {{-- Navigation mois --}}
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem; gap:1rem;">

        <a href="{{ route('calendrier.performances', ['mois' => $moisPrecedent]) }}"
           style="padding:7px 16px; background:var(--c-bg2); border:1px solid var(--c-border); color:var(--c-text); font-family:var(--font-display); font-size:0.78rem; font-weight:700; letter-spacing:0.06em; text-transform:uppercase; text-decoration:none;"
           onmouseover="this.style.borderColor='var(--c-green)'" onmouseout="this.style.borderColor='var(--c-border)'">
            ← Préc.
        </a>

        <div style="font-family:var(--font-display); font-weight:800; font-size:1.1rem; letter-spacing:0.08em; text-transform:uppercase; text-align:center;">
            {{ $debut->locale('fr')->isoFormat('MMMM YYYY') }}
        </div>

        @if($moisSuivant <= $moisCourant)
            <a href="{{ route('calendrier.performances', ['mois' => $moisSuivant]) }}"
               style="padding:7px 16px; background:var(--c-bg2); border:1px solid var(--c-border); color:var(--c-text); font-family:var(--font-display); font-size:0.78rem; font-weight:700; letter-spacing:0.06em; text-transform:uppercase; text-decoration:none;"
               onmouseover="this.style.borderColor='var(--c-green)'" onmouseout="this.style.borderColor='var(--c-border)'">
                Suiv. →
            </a>
        @else
            <span style="padding:7px 16px; background:var(--c-bg2); border:1px solid var(--c-border); color:var(--c-muted); font-family:var(--font-display); font-size:0.78rem; font-weight:700; letter-spacing:0.06em; text-transform:uppercase; cursor:not-allowed;">
                Suiv. →
            </span>
        @endif

    </div>

    {{-- Jours de la semaine --}}
    <div style="display:grid; grid-template-columns:repeat(7, 1fr); gap:2px; margin-bottom:2px;">
        @foreach(['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'] as $jour)
            <div style="text-align:center; font-family:var(--font-display); font-size:0.68rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); padding:6px 0;">
                {{ $jour }}
            </div>
        @endforeach
    </div>

    {{-- Grille calendrier --}}
    @php
        $premierJour    = $debut->copy()->startOfMonth();
        $offsetDebut    = ($premierJour->dayOfWeekIso - 1); // 0 = lundi
        $totalJours     = $debut->daysInMonth;
        $today          = now()->format('Y-m-d');
    @endphp

    <div style="display:grid; grid-template-columns:repeat(7, 1fr); gap:2px;">

        {{-- Cases vides avant le 1er --}}
        @for($i = 0; $i < $offsetDebut; $i++)
            <div style="background:var(--c-bg2); border:1px solid var(--c-border); min-height:90px; opacity:0.3;"></div>
        @endfor

        {{-- Jours du mois --}}
        @for($j = 1; $j <= $totalJours; $j++)
            @php
                $dateStr   = $debut->format('Y-m') . '-' . str_pad($j, 2, '0', STR_PAD_LEFT);
                $estAujourd = $dateStr === $today;
                $couponsJour = $coupons[$dateStr] ?? collect();
                $aGagne    = $couponsJour->where('statut_resultat', 'gagne')->count();
                $aPerdu    = $couponsJour->where('statut_resultat', 'perdu')->count();
                $enCours   = $couponsJour->whereIn('statut_resultat', ['en_attente', 'en_cours'])->count();
            @endphp

            <div style="background:var(--c-bg2); border:1px solid {{ $estAujourd ? 'var(--c-green)' : 'var(--c-border)' }}; min-height:90px; padding:6px; position:relative;">

                {{-- Numéro du jour --}}
                <div style="font-family:var(--font-display); font-size:0.78rem; font-weight:{{ $estAujourd ? '800' : '600' }}; color:{{ $estAujourd ? 'var(--c-green)' : 'var(--c-muted)' }}; margin-bottom:4px;">
                    {{ $j }}
                </div>

                {{-- Badges résultats --}}
                @if($couponsJour->isNotEmpty())
                    <div style="display:flex; flex-direction:column; gap:3px;">
                        @if($aGagne > 0)
                            <div style="font-family:var(--font-display); font-size:0.6rem; font-weight:800; letter-spacing:0.05em; text-transform:uppercase; color:var(--c-green); background:rgba(0,200,100,0.08); border:1px solid rgba(0,200,100,0.2); padding:2px 5px; text-align:center;">
                                ✓ {{ $aGagne }} gagné{{ $aGagne > 1 ? 's' : '' }}
                            </div>
                        @endif
                        @if($aPerdu > 0)
                            <div style="font-family:var(--font-display); font-size:0.6rem; font-weight:800; letter-spacing:0.05em; text-transform:uppercase; color:var(--c-danger); background:rgba(220,50,50,0.08); border:1px solid rgba(220,50,50,0.2); padding:2px 5px; text-align:center;">
                                ✕ {{ $aPerdu }} perdu{{ $aPerdu > 1 ? 's' : '' }}
                            </div>
                        @endif
                        @if($enCours > 0)
                            <div style="font-family:var(--font-display); font-size:0.6rem; font-weight:800; letter-spacing:0.05em; text-transform:uppercase; color:var(--c-gold); background:rgba(255,214,0,0.08); border:1px solid rgba(255,214,0,0.2); padding:2px 5px; text-align:center;">
                                ● {{ $enCours }} en cours
                            </div>
                        @endif
                    </div>
                @endif

            </div>
        @endfor

    </div>

    {{-- Légende --}}
    <div style="display:flex; gap:1.5rem; margin-top:1.25rem; flex-wrap:wrap;">
        <div style="display:flex; align-items:center; gap:0.4rem; font-family:var(--font-display); font-size:0.72rem; letter-spacing:0.06em; text-transform:uppercase; color:var(--c-muted);">
            <span style="width:10px; height:10px; background:rgba(0,200,100,0.3); border:1px solid var(--c-green); display:inline-block;"></span> Gagné
        </div>
        <div style="display:flex; align-items:center; gap:0.4rem; font-family:var(--font-display); font-size:0.72rem; letter-spacing:0.06em; text-transform:uppercase; color:var(--c-muted);">
            <span style="width:10px; height:10px; background:rgba(220,50,50,0.3); border:1px solid var(--c-danger); display:inline-block;"></span> Perdu
        </div>
        <div style="display:flex; align-items:center; gap:0.4rem; font-family:var(--font-display); font-size:0.72rem; letter-spacing:0.06em; text-transform:uppercase; color:var(--c-muted);">
            <span style="width:10px; height:10px; background:rgba(255,214,0,0.2); border:1px solid var(--c-gold); display:inline-block;"></span> En cours
        </div>
        <div style="display:flex; align-items:center; gap:0.4rem; font-family:var(--font-display); font-size:0.72rem; letter-spacing:0.06em; text-transform:uppercase; color:var(--c-muted);">
            <span style="width:10px; height:10px; border:1px solid var(--c-green); display:inline-block;"></span> Aujourd'hui
        </div>
    </div>

</div>

@endsection