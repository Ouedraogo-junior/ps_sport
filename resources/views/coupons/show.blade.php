@extends('layouts.app')

@section('title', $coupon->titre)

@section('content')
<div style="max-width:900px; margin:0 auto; padding:2rem 1.5rem;">

    {{-- Retour --}}
    <a href="{{ route('coupons.index') }}"
       style="font-family:var(--font-display); font-size:0.8rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-muted); text-decoration:none; display:inline-flex; align-items:center; gap:6px; margin-bottom:1.5rem; transition:color 0.2s;"
       onmouseover="this.style.color='var(--c-green)'" onmouseout="this.style.color='var(--c-muted)'">
        ← Tous les coupons
    </a>

    {{-- Header coupon --}}
    <div class="card" style="padding:1.75rem; margin-bottom:1.5rem;">
        <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:1rem;">
            <div>
                <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:0.75rem; flex-wrap:wrap;">
                    <span class="badge-{{ $coupon->niveau_risque }}"
                          style="font-family:var(--font-display); font-size:0.7rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; padding:3px 10px; border-radius:2px;">
                        {{ $coupon->niveauRisqueLabel() }}
                    </span>

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

                <h1 style="font-family:var(--font-display); font-size:1.75rem; font-weight:800; text-transform:uppercase; letter-spacing:0.05em;">
                    {{ $coupon->titre }}
                </h1>

                <p style="color:var(--c-muted); font-size:0.8rem; margin-top:0.4rem; font-family:var(--font-display); letter-spacing:0.04em; text-transform:uppercase;">
                    Publié le {{ $coupon->publie_le->locale('fr')->isoFormat('dddd D MMMM à HH[h]mm') }}
                </p>
            </div>
        </div>

        @if($coupon->description)
            <div class="gold-line"></div>
            <p style="color:var(--c-text); font-size:0.95rem; line-height:1.7;">
                {{ $coupon->description }}
            </p>
        @endif
    </div>

    {{-- Codes bookmakers --}}
    @if($coupon->codes->count() > 0)
        <div style="margin-bottom:1.5rem;">
            <h2 style="font-family:var(--font-display); font-size:1rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:var(--c-muted); margin-bottom:0.75rem;">
                Codes par bookmaker
            </h2>
            <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(200px, 1fr)); gap:0.75rem;">
                @foreach($coupon->codes as $code)
                    <div style="background:var(--c-bg2); border:1px solid var(--c-border); padding:1rem 1.25rem; display:flex; justify-content:space-between; align-items:center;">
                        <span style="font-family:var(--font-display); font-size:0.85rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:var(--c-muted);">
                            {{ $code->bookmakerLabel() }}
                        </span>
                        <div style="display:flex; align-items:center; gap:0.75rem;">
                            <span style="font-family:var(--font-display); font-size:1rem; font-weight:800; letter-spacing:0.1em; color:var(--c-green);">
                                {{ $code->code }}
                            </span>
                            <button onclick="copierCode(this, '{{ $code->code }}')"
                                    style="background:none; border:1px solid var(--c-border); color:var(--c-muted); padding:4px 10px; cursor:pointer; font-family:var(--font-display); font-size:0.65rem; font-weight:700; letter-spacing:0.07em; text-transform:uppercase; transition:all 0.2s;">
                                Copier
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Sélections --}}
    @if($coupon->selections->count() > 0)
        <div style="margin-bottom:1.5rem;">
            <h2 style="font-family:var(--font-display); font-size:1rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:var(--c-muted); margin-bottom:0.75rem;">
                Sélections ({{ $coupon->selections->count() }})
            </h2>
            <div style="display:flex; flex-direction:column; gap:0.6rem;">
                @foreach($coupon->selections as $selection)
                    <div style="background:var(--c-bg2); border:1px solid var(--c-border); padding:1rem 1.25rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.75rem;">

                        <div>
                            <div style="font-family:var(--font-display); font-weight:700; font-size:1rem; text-transform:uppercase; letter-spacing:0.04em;">
                                {{ $selection->matchLabel() }}
                            </div>
                            <div style="display:flex; gap:1rem; margin-top:0.35rem; flex-wrap:wrap;">
                                @if($selection->competition)
                                    <span style="font-size:0.8rem; color:var(--c-muted);">{{ $selection->competition }}</span>
                                @endif
                                @if($selection->date_match)
                                    <span style="font-size:0.8rem; color:var(--c-muted);">
                                        {{ $selection->date_match->locale('fr')->isoFormat('D MMM à HH[h]mm') }}
                                    </span>
                                @endif
                                @if($selection->type_pari)
                                    <span style="font-size:0.8rem; color:var(--c-text);">{{ $selection->type_pari }}</span>
                                @endif
                            </div>
                        </div>

                        <div style="display:flex; align-items:center; gap:1rem;">
                            @if($selection->cote)
                                <div style="font-family:var(--font-display); font-size:1.25rem; font-weight:800; color:var(--c-gold);">
                                    {{ number_format($selection->cote, 2) }}
                                </div>
                            @endif

                            @if($selection->score_final)
                                <div style="font-family:var(--font-display); font-size:0.85rem; color:var(--c-muted); text-transform:uppercase; letter-spacing:0.06em;">
                                    {{ $selection->score_final }}
                                </div>
                            @endif

                            <span class="badge-{{ $selection->statut }}"
                                  style="font-family:var(--font-display); font-size:0.7rem; font-weight:700; letter-spacing:0.07em; text-transform:uppercase;">
                                @if($selection->statut === 'gagne') ✓ Gagné
                                @elseif($selection->statut === 'perdu') ✕ Perdu
                                @elseif($selection->statut === 'en_cours') ● En cours
                                @elseif($selection->statut === 'annule') Annulé
                                @else En attente
                                @endif
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Analyse --}}
    @if($coupon->analyse)
        <div class="card" style="padding:1.5rem;">
            <h2 style="font-family:var(--font-display); font-size:1rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:var(--c-muted); margin-bottom:0.75rem;">
                Analyse
            </h2>
            <p style="color:var(--c-text); font-size:0.95rem; line-height:1.8; white-space:pre-line;">
                {{ $coupon->analyse }}
            </p>
        </div>
    @endif

</div>

@push('scripts')
<script>
function copierCode(btn, code) {
    navigator.clipboard.writeText(code).then(() => {
        const original = btn.textContent;
        btn.textContent = '✓ Copié';
        btn.style.borderColor = 'var(--c-green)';
        btn.style.color = 'var(--c-green)';
        setTimeout(() => {
            btn.textContent = original;
            btn.style.borderColor = 'var(--c-border)';
            btn.style.color = 'var(--c-muted)';
        }, 2000);
    });
}
</script>
@endpush

@endsection