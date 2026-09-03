@extends('layouts.app')

@section('title', $coupon->titre)

@section('content')
<div x-data="{ lightboxOpen: false }" style="max-width:900px; margin:0 auto; padding:2rem 1.5rem 3.5rem;">

    {{-- Retour --}}
    <div style="margin-bottom:12px;">
        <a href="{{ route('coupons.index') }}"
           style="display:inline-flex; align-items:center; gap:6px; font-family:var(--font-display); font-weight:700; font-size:11px; letter-spacing:0.14em; text-transform:uppercase; color:#555; text-decoration:none; transition:color 0.15s;"
           onmouseover="this.style.color='var(--c-green)'" onmouseout="this.style.color='#555'">
            ← Tous les coupons
        </a>
    </div>

    {{-- En-tête --}}
    <div style="padding-bottom:20px; border-bottom:1px solid rgba(255,255,255,0.06); margin-bottom:0;">

        {{-- Badges --}}
        <div style="display:flex; flex-wrap:wrap; gap:8px; margin-bottom:14px;">
            <span style="display:inline-flex; align-items:center; gap:5px; padding:3px 8px; border:1px solid {{ $coupon->niveauRisqueColor() }}; font-family:var(--font-display); font-weight:700; font-size:10px; letter-spacing:0.14em; text-transform:uppercase; color:{{ $coupon->niveauRisqueColor() }};">
                <span style="font-size:7px;">◆</span>
                {{ $coupon->niveauRisqueLabel() }}
            </span>

            @if($coupon->statut_resultat !== 'en_attente')
                @php
                    $resultCfg = match($coupon->statut_resultat) {
                        'gagne'    => ['label' => 'Gagné',   'color' => 'var(--c-green)',  'bg' => 'rgba(0,230,118,0.12)'],
                        'perdu'    => ['label' => 'Perdu',   'color' => 'var(--c-danger)', 'bg' => 'rgba(255,61,61,0.12)'],
                        'en_cours' => ['label' => 'En cours','color' => 'var(--c-gold)',   'bg' => 'rgba(255,214,0,0.12)'],
                        default    => ['label' => 'Annulé',  'color' => 'var(--c-muted)',  'bg' => 'rgba(255,255,255,0.06)'],
                    };
                @endphp
                <span style="display:inline-flex; align-items:center; gap:7px; padding:3px 10px; background:{{ $resultCfg['bg'] }}; font-family:var(--font-display); font-weight:800; font-size:11px; letter-spacing:0.15em; text-transform:uppercase; color:{{ $resultCfg['color'] }};">
                    @if($coupon->statut_resultat === 'en_cours')
                        <span style="position:relative; display:inline-flex; width:8px; height:8px; flex-shrink:0;">
                            <span class="ps-ping" style="position:absolute; inset:0; border-radius:50%; background:{{ $resultCfg['color'] }}; opacity:0.6;"></span>
                            <span style="position:relative; width:8px; height:8px; border-radius:50%; background:{{ $resultCfg['color'] }};"></span>
                        </span>
                    @endif
                    {{ $resultCfg['label'] }}
                </span>
            @endif
        </div>

        {{-- Titre --}}
        <h1 style="font-family:var(--font-display); font-weight:800; font-size:clamp(22px, 5.5vw, 38px); letter-spacing:0.04em; line-height:1.05; text-transform:uppercase; color:var(--c-text); margin:0 0 12px;">
            {{ $coupon->titre }}
        </h1>

        {{-- Ligne méta : date + cote totale --}}
        <div style="display:flex; flex-wrap:wrap; align-items:center; gap:6px 20px; margin-bottom:{{ $coupon->description ? '10px' : '0' }};">
            <span style="font-family:'JetBrains Mono', monospace; font-size:11px; color:#555;">
                {{ strtoupper($coupon->publie_le->locale('fr')->isoFormat('DD MMM YYYY')) }} · {{ $coupon->publie_le->isoFormat('HH[h]mm') }}
            </span>

            @if($coupon->oddsCombinees() > 1)
                <div style="display:flex; align-items:center; gap:7px;">
                    <span style="font-family:var(--font-display); font-weight:600; font-size:9.5px; letter-spacing:0.13em; color:#444; text-transform:uppercase;">
                        Cote totale
                    </span>
                    <span style="font-family:'JetBrains Mono', monospace; font-weight:700; font-size:20px; color:var(--c-gold); line-height:1;">
                        {{ number_format($coupon->oddsCombinees(), 2) }}
                    </span>
                </div>
            @endif
        </div>

        @if($coupon->description)
            <p style="font-size:13px; color:#888; line-height:1.65; margin:0; max-width:560px;">
                {{ $coupon->description }}
            </p>
        @endif
    </div>

    {{-- Grille responsive : image + codes (sidebar) + sélections + analyse --}}
    <div class="coupon-grid">

        {{-- Image des événements du jour --}}
        @if($coupon->image_evenements)
            <div class="coupon-image-section" style="padding-top:20px;">
                <div class="ps-section-label">Matchs du jour</div>

                <button type="button" @click="lightboxOpen = true"
                        style="display:block; width:100%; padding:0; margin:0; border:none; text-align:left; font:inherit; background:var(--c-bg2); cursor:pointer; position:relative; overflow:hidden;">
                    <img src="{{ Storage::url($coupon->image_evenements) }}"
                         alt="Sélection de matchs du jour — {{ $coupon->titre }}"
                         class="ps-coupon-image"
                         style="display:block; width:100%; aspect-ratio:16/7; object-fit:cover;">
                    <div style="position:absolute; inset:0; background:linear-gradient(to top, rgba(0,0,0,0.75) 0%, transparent 60%); pointer-events:none;"></div>
                    <div style="position:absolute; bottom:10px; left:12px; right:12px; display:flex; justify-content:space-between; align-items:flex-end; pointer-events:none;">
                        <span style="font-family:var(--font-display); font-weight:700; font-size:10px; letter-spacing:0.13em; color:rgba(255,255,255,0.5); text-transform:uppercase;">
                            {{ $coupon->selections->count() }} match{{ $coupon->selections->count() > 1 ? 's' : '' }} sélectionné{{ $coupon->selections->count() > 1 ? 's' : '' }}
                        </span>
                        <span style="font-family:var(--font-display); font-weight:700; font-size:10px; letter-spacing:0.13em; color:var(--c-green); text-transform:uppercase;">
                            ⊕ Agrandir
                        </span>
                    </div>
                </button>
            </div>

            {{-- Lightbox --}}
            <div x-show="lightboxOpen"
                 x-transition.opacity
                 @keydown.escape.window="lightboxOpen = false"
                 @click.self="lightboxOpen = false"
                 class="lightbox-overlay"
                 style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.92); padding:16px;">
                <div style="position:relative; max-width:1000px; width:100%;" @click.stop>
                    <img src="{{ Storage::url($coupon->image_evenements) }}"
                         alt="Matchs sélectionnés — vue agrandie"
                         style="display:block; width:100%; max-height:85vh; object-fit:contain;">
                    <button @click="lightboxOpen = false"
                            style="position:absolute; top:10px; right:10px; font-family:var(--font-display); font-weight:700; font-size:10px; letter-spacing:0.13em; text-transform:uppercase; color:#bbb; background:rgba(0,0,0,0.75); border:1px solid rgba(255,255,255,0.1); padding:6px 12px; cursor:pointer;">
                        ✕ Fermer
                    </button>
                </div>
            </div>
        @endif

        {{-- Codes bookmakers --}}
        @if($coupon->codes->count() > 0)
            <div class="coupon-codes-section" style="padding-top:20px;">
                <div class="ps-section-label">Codes bookmaker</div>

                <div class="ps-perforated"></div>

                <div>
                    @foreach($coupon->codes as $code)
                        <div style="display:flex; align-items:stretch; background:#141414; border-bottom:1px solid rgba(255,255,255,0.05);">

                            {{-- Pavé identité bookmaker --}}
                            <div style="min-width:76px; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:5px; padding:14px 10px; border-right:2px dashed rgba(255,255,255,0.12);">
                                <div style="width:34px; height:34px; background:{{ $code->accentColor() }}; display:flex; align-items:center; justify-content:center; font-family:var(--font-display); font-weight:800; font-size:13px; letter-spacing:0.04em; color:#fff;">
                                    {{ $code->shortLabel() }}
                                </div>
                                <span style="font-family:var(--font-display); font-weight:700; font-size:9.5px; letter-spacing:0.1em; color:#666; text-transform:uppercase; text-align:center; line-height:1.1;">
                                    {{ $code->bookmakerLabel() }}
                                </span>
                            </div>

                            {{-- Code + copier --}}
                            <div style="flex:1; display:flex; align-items:center; justify-content:space-between; gap:10px; padding:14px 14px 14px 16px; min-width:0;">
                                <div style="min-width:0;">
                                    <div style="font-family:'JetBrains Mono', monospace; font-weight:700; font-size:15px; letter-spacing:0.08em; color:var(--c-green); word-break:break-all; line-height:1.2;">
                                        {{ $code->code }}
                                    </div>
                                    <div style="font-family:var(--font-display); font-size:9px; letter-spacing:0.12em; color:#444; margin-top:3px; text-transform:uppercase;">
                                        Code de pari
                                    </div>
                                </div>

                                <button type="button" class="ps-copy-btn" data-code="{{ $code->code }}" data-copied="false" onclick="copierCode(this)"
                                        style="flex-shrink:0; display:flex; flex-direction:column; align-items:center; gap:3px; padding:8px 11px; background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.08); cursor:pointer; transition:all 0.15s ease;">
                                    <span class="ps-copy-icon" style="font-size:17px; line-height:1; color:#999;">⎘</span>
                                    <span class="ps-copy-label" style="font-family:var(--font-display); font-size:8.5px; letter-spacing:0.1em; color:#666; text-transform:uppercase;">Copier</span>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="ps-perforated"></div>

                <p style="font-size:11px; color:#444; margin-top:8px; line-height:1.5; font-style:italic;">
                    Copiez le code de votre bookmaker et collez-le lors de l'import du coupon.
                </p>
            </div>
        @endif

        {{-- Sélections --}}
        @if($coupon->selections->count() > 0)
            <div class="coupon-selections-section" style="padding-top:20px;">
                <div class="ps-section-label">Sélections ({{ $coupon->selections->count() }})</div>

                <div style="background:#111; border:1px solid rgba(255,255,255,0.06);">
                    @foreach($coupon->selections as $selection)
                        <div style="padding:12px 16px; border-bottom:1px solid rgba(255,255,255,0.05); opacity:{{ $selection->statut === 'perdu' ? '0.6' : '1' }}; transition:opacity 0.15s;">

                            {{-- Compétition + heure --}}
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                                <span style="font-family:var(--font-display); font-weight:600; font-size:9.5px; letter-spacing:0.13em; color:#555; text-transform:uppercase;">
                                    {{ $selection->competition ?? '—' }}
                                </span>
                                @if($selection->date_match)
                                    <span style="font-family:'JetBrains Mono', monospace; font-size:10px; color:#555;">
                                        {{ $selection->date_match->isoFormat('HH[h]mm') }}
                                    </span>
                                @endif
                            </div>

                            {{-- Équipes + cote --}}
                            <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:12px; margin-bottom:6px;">
                                <div style="flex:1; min-width:0;">
                                    @if($selection->equipe_domicile && $selection->equipe_exterieur)
                                        <div style="font-size:13.5px; font-weight:600; line-height:1.25; color:#e8e8e8; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                            {{ $selection->equipe_domicile }}
                                        </div>
                                        <div style="font-size:11px; color:#666; margin-top:1px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                            vs {{ $selection->equipe_exterieur }}
                                        </div>
                                    @else
                                        <div style="font-size:13.5px; font-weight:600; line-height:1.25; color:#e8e8e8;">
                                            {{ $selection->matchLabel() }}
                                        </div>
                                    @endif
                                </div>

                                @if($selection->cote)
                                    <div style="font-family:'JetBrains Mono', monospace; font-weight:700; font-size:22px; color:{{ $selection->statut === 'perdu' ? 'var(--c-danger)' : 'var(--c-gold)' }}; letter-spacing:-0.02em; line-height:1; flex-shrink:0;">
                                        {{ number_format($selection->cote, 2) }}
                                    </div>
                                @endif
                            </div>

                            {{-- Type de pari + statut --}}
                            <div style="display:flex; justify-content:space-between; align-items:center; gap:8px;">
                                <span style="font-size:11px; color:#777; font-style:italic; min-width:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                    {{ $selection->type_pari ?? '—' }}{{ $selection->score_final ? ' · '.$selection->score_final : '' }}
                                </span>
                                <span style="font-family:var(--font-display); font-weight:700; font-size:9.5px; letter-spacing:0.12em; color:{{ $selection->statutColor() }}; text-transform:uppercase; flex-shrink:0;">
                                    {{ $selection->statutSymbole() }} {{ $selection->statutLabel() }}
                                </span>
                            </div>
                        </div>
                    @endforeach

                    @if($coupon->oddsCombinees() > 1)
                        <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 16px; background:#0d0d0d; border-top:1px solid rgba(255,214,0,0.12);">
                            <span style="font-family:var(--font-display); font-weight:700; font-size:10px; letter-spacing:0.15em; color:#555; text-transform:uppercase;">
                                Cote combinée
                            </span>
                            <span style="font-family:'JetBrains Mono', monospace; font-weight:700; font-size:26px; color:var(--c-gold); letter-spacing:-0.02em; line-height:1;">
                                {{ number_format($coupon->oddsCombinees(), 2) }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Analyse --}}
        @if($coupon->analyse)
            <div class="coupon-analysis-section" style="padding-top:20px;">
                <div class="ps-section-label">Analyse du tipster</div>

                <div style="border-left:2px solid rgba(0,230,118,0.25); padding-left:18px;">
                    <p style="font-size:13.5px; line-height:1.8; color:#999; margin:0; white-space:pre-line;">
                        {{ $coupon->analyse }}
                    </p>
                </div>

                <div style="margin-top:20px; padding:10px 12px; background:rgba(255,61,61,0.06); border-left:2px solid rgba(255,61,61,0.25);">
                    <p style="font-family:var(--font-display); font-weight:600; font-size:10.5px; letter-spacing:0.1em; color:var(--c-danger); text-transform:uppercase; margin:0; line-height:1.6;">
                        Pari responsable — Les pronostics ne garantissent aucun gain. Misez uniquement ce que vous êtes prêt à perdre.
                    </p>
                </div>
            </div>
        @endif

    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600;700&display=swap');

    .ps-section-label {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
        font-family: var(--font-display);
        font-weight: 800;
        font-size: 11px;
        letter-spacing: 0.18em;
        color: #444;
        text-transform: uppercase;
        white-space: nowrap;
    }
    .ps-section-label::after {
        content: '';
        flex: 1;
        height: 1px;
        background: rgba(255,255,255,0.06);
    }

    .ps-perforated {
        height: 13px;
        background-image: radial-gradient(circle at center, #0a0a0a 5.5px, #141414 5.5px);
        background-size: 22px 13px;
        background-repeat: repeat-x;
        background-color: #141414;
    }

    .ps-coupon-image {
        filter: brightness(0.88) saturate(0.9);
        transition: filter 0.25s;
    }
    .coupon-image-section button:hover .ps-coupon-image {
        filter: brightness(1) saturate(1);
    }

    .ps-copy-btn[data-copied="true"] {
        background: rgba(0,230,118,0.12) !important;
        border-color: rgba(0,230,118,0.35) !important;
    }
    .ps-copy-btn[data-copied="true"] .ps-copy-icon,
    .ps-copy-btn[data-copied="true"] .ps-copy-label {
        color: var(--c-green) !important;
    }

    .lightbox-overlay {
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(4px);
    }

    @keyframes ps-ping {
        75%, 100% { transform: scale(2); opacity: 0; }
    }
    .ps-ping { animation: ps-ping 1.2s cubic-bezier(0,0,0.2,1) infinite; }

    /* Grille responsive : 1 colonne mobile, 2 colonnes (contenu + sidebar codes) dès 700px */
    .coupon-grid {
        display: grid;
        grid-template-columns: 1fr;
        grid-template-areas:
            "image"
            "codes"
            "selections"
            "analysis";
    }
    @media (min-width: 700px) {
        .coupon-grid {
            grid-template-columns: minmax(0, 1fr) 320px;
            grid-template-areas:
                "image      codes"
                "selections codes"
                "analysis   codes";
            column-gap: 24px;
            align-items: start;
        }
        .coupon-codes-section { position: sticky; top: 80px; }
    }
    .coupon-image-section      { grid-area: image; }
    .coupon-codes-section      { grid-area: codes; }
    .coupon-selections-section { grid-area: selections; }
    .coupon-analysis-section   { grid-area: analysis; }
</style>

@push('scripts')
<script>
function copierCode(btn) {
    const code = btn.dataset.code;
    navigator.clipboard.writeText(code).then(() => {
        const icon  = btn.querySelector('.ps-copy-icon');
        const label = btn.querySelector('.ps-copy-label');
        btn.dataset.copied = 'true';
        icon.textContent = '✓';
        label.textContent = 'Copié';
        setTimeout(() => {
            btn.dataset.copied = 'false';
            icon.textContent = '⎘';
            label.textContent = 'Copier';
        }, 2000);
    });
}
</script>
@endpush

@endsection