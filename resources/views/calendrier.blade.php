@extends('layouts.app')

@section('title', 'Calendrier des matchs')

@section('content')

<div style="max-width:960px; margin:0 auto; padding:2rem 1.5rem;">

    {{-- En-tête --}}
    <div style="margin-bottom:2rem;">
        <div style="font-family:var(--font-display); font-size:0.75rem; font-weight:700; letter-spacing:0.15em; text-transform:uppercase; color:var(--c-green); margin-bottom:0.5rem;">
            ● Live & Calendrier
        </div>
        <h1 style="font-family:var(--font-display); font-size:2rem; font-weight:800; letter-spacing:0.03em; text-transform:uppercase;">
            Matchs du jour & demain
        </h1>
    </div>

    @if($matchs->isEmpty())
        <div style="text-align:center; padding:4rem; color:var(--c-muted); font-family:var(--font-display); letter-spacing:0.08em; text-transform:uppercase; font-size:0.9rem;">
            Aucun match trouvé pour ces compétitions.
        </div>
    @else
        @foreach($matchs as $date => $joursMatchs)

            {{-- Titre jour --}}
            <div style="font-family:var(--font-display); font-weight:800; font-size:1rem; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-gold); margin:1.75rem 0 0.75rem; padding-bottom:0.5rem; border-bottom:1px solid var(--c-border);">
                {{ \Carbon\Carbon::parse($date)->locale('fr')->isoFormat('dddd D MMMM') }}
            </div>

            {{-- Grouper par compétition --}}
            @foreach($joursMatchs->groupBy('competition') as $competition => $matchsComp)

                {{-- Header compétition --}}
                <div style="display:flex; align-items:center; gap:0.75rem; padding:0.6rem 0.75rem; background:var(--c-bg2); border:1px solid var(--c-border); border-bottom:none; margin-top:0.75rem;">
                    @if($matchsComp->first()['league_logo'])
                        <img src="{{ $matchsComp->first()['league_logo'] }}" alt="{{ $competition }}" style="width:20px; height:20px; object-fit:contain;">
                    @endif
                    <span style="font-family:var(--font-display); font-weight:700; font-size:0.8rem; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-text);">
                        {{ $competition }}
                    </span>
                </div>

                {{-- Matchs --}}
                @foreach($matchsComp as $match)
                    @php
                        $enCours   = in_array($match['statut'], ['1H', '2H', 'HT', 'ET', 'P', 'LIVE']);
                        $termine   = in_array($match['statut'], ['FT', 'AET', 'PEN']);
                        $annule    = in_array($match['statut'], ['CANC', 'ABD', 'PST']);
                        $aVenir    = !$enCours && !$termine && !$annule;
                        $heure     = \Carbon\Carbon::parse($match['date'])->format('H:i');
                    @endphp

                    <div style="display:grid; grid-template-columns:1fr auto 1fr; align-items:center; gap:1rem; padding:0.85rem 1rem; background:var(--c-bg2); border:1px solid var(--c-border); border-top:none; {{ $enCours ? 'border-left:2px solid var(--c-green);' : '' }}">

                        {{-- Équipe domicile --}}
                        <div style="display:flex; align-items:center; gap:0.6rem; justify-content:flex-end;">
                            <span style="font-family:var(--font-display); font-weight:700; font-size:0.9rem; text-align:right;">
                                {{ $match['domicile'] }}
                            </span>
                            @if($match['domicile_logo'])
                                <img src="{{ $match['domicile_logo'] }}" alt="" style="width:24px; height:24px; object-fit:contain; flex-shrink:0;">
                            @endif
                        </div>

                        {{-- Score / Heure --}}
                        <div style="text-align:center; min-width:80px;">
                            @if($termine)
                                <div style="font-family:var(--font-display); font-weight:800; font-size:1.1rem; color:var(--c-text);">
                                    {{ $match['score_dom'] }} - {{ $match['score_ext'] }}
                                </div>
                                <div style="font-size:0.65rem; color:var(--c-muted); font-family:var(--font-display); letter-spacing:0.06em; text-transform:uppercase; margin-top:2px;">
                                    Terminé
                                </div>
                            @elseif($enCours)
                                <div style="font-family:var(--font-display); font-weight:800; font-size:1.1rem; color:var(--c-green);">
                                    {{ $match['score_dom'] }} - {{ $match['score_ext'] }}
                                </div>
                                <div style="font-size:0.65rem; color:var(--c-green); font-family:var(--font-display); letter-spacing:0.06em; text-transform:uppercase; margin-top:2px; animation: pulse 1.5s infinite;">
                                    ● Live
                                </div>
                            @elseif($annule)
                                <div style="font-family:var(--font-display); font-size:0.75rem; color:var(--c-danger); font-weight:700; letter-spacing:0.06em; text-transform:uppercase;">
                                    Annulé
                                </div>
                            @else
                                <div style="font-family:var(--font-display); font-weight:800; font-size:1rem; color:var(--c-muted);">
                                    {{ $heure }}
                                </div>
                            @endif
                        </div>

                        {{-- Équipe extérieur --}}
                        <div style="display:flex; align-items:center; gap:0.6rem;">
                            @if($match['exterieur_logo'])
                                <img src="{{ $match['exterieur_logo'] }}" alt="" style="width:24px; height:24px; object-fit:contain; flex-shrink:0;">
                            @endif
                            <span style="font-family:var(--font-display); font-weight:700; font-size:0.9rem;">
                                {{ $match['exterieur'] }}
                            </span>
                        </div>

                    </div>
                @endforeach

            @endforeach

        @endforeach
    @endif

</div>

<style>
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50%       { opacity: 0.4; }
    }
</style>

@endsection