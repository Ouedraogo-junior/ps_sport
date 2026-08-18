@extends('layouts.app')

@section('content')
<div style="max-width:1200px; margin:0 auto; padding:2rem 1.5rem;">


    <div style="background:rgba(255,214,0,0.06); border:1px solid rgba(255,214,0,0.25); padding:1rem 1.25rem; margin-bottom:1.5rem; display:flex; align-items:flex-start; gap:0.75rem;">
        <span style="font-size:1.25rem; flex-shrink:0;">💡</span>
        <div style="font-size:1rem; color:var(--c-muted); line-height:1.6;">
            <strong style="font-family:var(--font-display); font-weight:800; color:var(--c-gold); ...">
                Plans d'investissement
            </strong><br>
                Les plans d'investissement sont accessibles à partir d'un abonnement de
            <strong style="color:var(--c-text);">10 000 FCFA</strong>.
                Ils vous permettent de générer des gains journaliers.
        </div>
    </div>

    {{-- En-tête de section --}}
    <div style="margin-bottom:2rem;">
        <div style="font-family:var(--font-display); font-size:0.75rem; font-weight:700; letter-spacing:0.12em; text-transform:uppercase; color:var(--c-green); margin-bottom:0.5rem;">
            Outil de planification
        </div>
        <h1 style="font-family:var(--font-display); font-weight:800; font-size:2rem; letter-spacing:0.04em; text-transform:uppercase; color:var(--c-text); margin:0 0 0.5rem;">
            Simulateur d'investissement
        </h1>
        <p style="color:var(--c-muted); font-size:0.9rem; margin:0;">
            Estimez vos gains selon votre montant et votre durée de placement.
        </p>
    </div>

    {{-- Sliders --}}
    <div class="card" style="padding:1.5rem; margin-bottom:1.5rem;">
        <div style="margin-bottom:1.25rem;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.5rem;">
                <label style="font-family:var(--font-display); font-weight:700; font-size:0.8rem; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-muted);">
                    Montant investi
                </label>
                <span id="montant-out" style="font-family:var(--font-display); font-weight:700; font-size:1rem; color:var(--c-green);">10 000 FCFA</span>
            </div>
            <input type="range" id="montant" min="10000" max="200000" step="5000" value="10000"
                   style="width:100%; accent-color:var(--c-green); cursor:pointer;">
        </div>

        <div>
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.5rem;">
                <label style="font-family:var(--font-display); font-weight:700; font-size:0.8rem; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-muted);">
                    Durée
                </label>
                <span id="duree-out" style="font-family:var(--font-display); font-weight:700; font-size:1rem; color:var(--c-green);">30 jours</span>
            </div>
            <input type="range" id="duree" min="30" max="365" step="30" value="30"
                   style="width:100%; accent-color:var(--c-green); cursor:pointer;">
        </div>
    </div>

    {{-- Cartes statistiques --}}
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(140px, 1fr)); gap:1rem; margin-bottom:1.5rem;">
        <div class="card" style="padding:1.25rem; text-align:center;">
            <div style="font-family:var(--font-display); font-size:0.7rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.5rem;">Gain / jour</div>
            <div id="s-jour" style="font-family:var(--font-display); font-weight:800; font-size:1.6rem; color:var(--c-green);">100 F</div>
        </div>
        <div class="card" style="padding:1.25rem; text-align:center;">
            <div style="font-family:var(--font-display); font-size:0.7rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.5rem;">Gain total</div>
            <div id="s-total" style="font-family:var(--font-display); font-weight:800; font-size:1.6rem; color:var(--c-green);">3 000 F</div>
        </div>
        <div class="card" style="padding:1.25rem; text-align:center;">
            <div style="font-family:var(--font-display); font-size:0.7rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.5rem;">Capital final</div>
            <div id="s-capital" style="font-family:var(--font-display); font-weight:800; font-size:1.6rem; color:var(--c-text);">13 000 F</div>
        </div>
        {{-- <div class="card" style="padding:1.25rem; text-align:center;">
            <div style="font-family:var(--font-display); font-size:0.7rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.5rem;">Rendement</div>
            <div id="s-rdt" style="font-family:var(--font-display); font-weight:800; font-size:1.6rem; color:var(--c-gold);">30%</div>
        </div> --}}
    </div>

    {{-- Projections par période --}}
    <div class="card" style="padding:1.5rem; margin-bottom:1.5rem;">
        <div style="font-family:var(--font-display); font-weight:700; font-size:0.8rem; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:1.25rem;">
            Projections par période
        </div>
        <div id="bars"></div>
    </div>

    {{-- Tableau des plans --}}
    <div class="card" style="padding:1.5rem; margin-bottom:1.5rem;">
        <div style="font-family:var(--font-display); font-weight:700; font-size:0.8rem; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:1.25rem;">
            Comparatif des plans
        </div>
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; font-size:0.85rem; min-width:540px;">
                <thead>
                    <tr>
                        <th style="font-family:var(--font-display); font-size:0.7rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-muted); text-align:left; padding:8px 12px; border-bottom:1px solid var(--c-border);">Plan</th>
                        <th style="font-family:var(--font-display); font-size:0.7rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-muted); text-align:left; padding:8px 12px; border-bottom:1px solid var(--c-border);">Investissement</th>
                        <th style="font-family:var(--font-display); font-size:0.7rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-muted); text-align:left; padding:8px 12px; border-bottom:1px solid var(--c-border);">Gain / jour</th>
                        <th style="font-family:var(--font-display); font-size:0.7rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-muted); text-align:left; padding:8px 12px; border-bottom:1px solid var(--c-border);">Gain 30j</th>
                        <th style="font-family:var(--font-display); font-size:0.7rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-muted); text-align:left; padding:8px 12px; border-bottom:1px solid var(--c-border);">Gain 1 an</th>
                        {{-- <th style="font-family:var(--font-display); font-size:0.7rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-muted); text-align:left; padding:8px 12px; border-bottom:1px solid var(--c-border);">Rendement</th> --}}
                    </tr>
                </thead>
                <tbody id="tbody-plans"></tbody>
            </table>
        </div>
    </div>

    {{-- Note avertissement --}}
    <div style="background:rgba(255,171,0,0.06); border-left:3px solid var(--c-warning); padding:1rem 1.25rem;">
        <p style="color:var(--c-warning); font-family:var(--font-display); font-weight:700; font-size:0.75rem; letter-spacing:0.08em; text-transform:uppercase; margin:0 0 0.4rem;">
            ⚠ Information importante
        </p>
        <p style="color:var(--c-muted); font-size:0.85rem; margin:0; line-height:1.7;">
            Le gain est de <strong style="color:var(--c-text);">10 CFA par jour</strong> pour un investissement de 10 000 FCFA.
            Les gains sont calculés sur la base de la durée de votre plan.
            Vous pouvez augmenter votre investissement à tout moment pour maximiser vos revenus.
        </p>
    </div>

</div>
@endsection

@push('scripts')
<script>
const RATE_FIXED = 10; // 10 CFA par jour pour 10 000 FCFA
const plans = [
    { nom: '10 000 F',  montant: 10000  },
    { nom: '25 000 F',  montant: 25000  },
    { nom: '50 000 F',  montant: 50000  },
    { nom: '100 000 F', montant: 100000 },
    { nom: '200 000 F', montant: 200000 },
];
const periodes = [
    { label: '1 semaine', jours: 7   },
    { label: '1 mois',    jours: 30  },
    { label: '3 mois',    jours: 90  },
    { label: '6 mois',    jours: 180 },
    { label: '1 an',      jours: 365 },
];

function gainJourParMontant(montant) {
    return (montant / 10000) * RATE_FIXED;
}

function fmt(n) {
    return Math.round(n).toLocaleString('fr-FR') + ' F';
}

function update() {
    const montant = parseInt(document.getElementById('montant').value);
    const duree   = parseInt(document.getElementById('duree').value);
    const gj      = gainJourParMontant(montant);
    const gTotal  = gj * duree;
    const capital = gj * 365;
    // const rdt     = Math.round(gTotal / montant * 100);

    document.getElementById('montant-out').textContent = montant.toLocaleString('fr-FR') + ' FCFA';
    document.getElementById('duree-out').textContent   = duree + ' jours';
    document.getElementById('s-jour').textContent      = fmt(gj);
    document.getElementById('s-total').textContent     = fmt(gTotal);
    document.getElementById('s-capital').textContent   = fmt(capital);
    // document.getElementById('s-rdt').textContent       = rdt + '%';

    const maxGain = gj * 365;
    document.getElementById('bars').innerHTML = periodes.map(p => {
        const g = gj * p.jours;
        const w = maxGain > 0 ? Math.min(100, (g / maxGain) * 100) : 0;
        return `<div style="display:flex; align-items:center; gap:12px; margin-bottom:10px;">
            <span style="font-family:var(--font-display); font-size:0.75rem; font-weight:600; letter-spacing:0.06em; text-transform:uppercase; color:var(--c-muted); min-width:80px;">${p.label}</span>
            <div style="flex:1; height:6px; background:var(--c-bg3); border-radius:3px; overflow:hidden;">
                <div style="width:${w}%; height:100%; background:var(--c-green); border-radius:3px; transition:width 0.3s;"></div>
            </div>
            <span style="font-family:var(--font-display); font-weight:700; font-size:0.85rem; color:var(--c-green); min-width:90px; text-align:right;">${fmt(g)}</span>
        </div>`;
    }).join('');
}

function buildTable() {
    document.getElementById('tbody-plans').innerHTML = plans.map(p => {
        const j    = gainJourParMontant(p.montant);
        const g30  = j * 30;
        const g365 = j * 365;
        // const rdt  = Math.round(g365 / p.montant * 100);
        return `<tr style="border-bottom:1px solid var(--c-border);">
            <td style="padding:10px 12px;">
                <span style="font-family:var(--font-display); font-size:0.7rem; font-weight:700; letter-spacing:0.06em; text-transform:uppercase; padding:3px 10px; border:1px solid var(--c-border-g); color:var(--c-green); background:var(--c-green-bg);">${p.nom}</span>
            </td>
            <td style="padding:10px 12px; color:var(--c-muted); font-size:0.85rem;">${p.montant.toLocaleString('fr-FR')} FCFA</td>
            <td style="padding:10px 12px; color:var(--c-green); font-family:var(--font-display); font-weight:700;">${fmt(j)}</td>
            <td style="padding:10px 12px; color:var(--c-text); font-size:0.85rem;">${fmt(g30)}</td>
            <td style="padding:10px 12px; color:var(--c-text); font-size:0.85rem;">${fmt(g365)}</td>
        </tr>`;
    }).join('');
}

document.getElementById('montant').addEventListener('input', update);
document.getElementById('duree').addEventListener('input', update);

buildTable();
update();
</script>
@endpush