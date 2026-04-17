@if($plansInvestissement->isNotEmpty())

{{-- ── SÉPARATEUR --}}
<div style="height:1px; background:linear-gradient(90deg, transparent, var(--c-border), transparent);"></div>

{{-- ── INVESTISSEMENT ─────────────────────────────────── --}}
<section style="padding:4rem 1.5rem; max-width:960px; margin:0 auto;">

    <div style="text-align:center; margin-bottom:2.5rem;">
        <div style="font-family:var(--font-display); font-size:0.75rem; font-weight:700; letter-spacing:0.15em; text-transform:uppercase; color:var(--c-gold); margin-bottom:0.5rem;">
            ● Investissement
        </div>
        <h2 style="font-family:var(--font-display); font-size:clamp(1.5rem, 4vw, 2rem); font-weight:800; letter-spacing:0.03em; text-transform:uppercase;">
            Faites fructifier votre argent
        </h2>
        <p style="font-size:0.9rem; color:var(--c-muted); max-width:520px; margin:0.75rem auto 0; line-height:1.8;">
            En plus des pronostics, investissez votre mise et recevez un gain journalier automatique sur toute la durée de votre abonnement.
        </p>
    </div>

    {{-- Comment ça marche --}}
    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; margin-bottom:3rem;">

        <div style="background:var(--c-bg2); border:1px solid var(--c-border); padding:1.5rem; text-align:center;">
            <div style="font-size:1.75rem; margin-bottom:0.75rem;">💰</div>
            <div style="font-family:var(--font-display); font-weight:700; font-size:0.85rem; letter-spacing:0.06em; text-transform:uppercase; margin-bottom:0.5rem;">Investissez</div>
            <p style="font-size:0.82rem; color:var(--c-muted); line-height:1.7;">
                Choisissez un plan investissement à partir de 10 000 FCFA.
            </p>
        </div>

        <div style="background:var(--c-bg2); border:1px solid var(--c-border-g); padding:1.5rem; text-align:center;">
            <div style="font-size:1.75rem; margin-bottom:0.75rem;">📈</div>
            <div style="font-family:var(--font-display); font-weight:700; font-size:0.85rem; letter-spacing:0.06em; text-transform:uppercase; margin-bottom:0.5rem;">Gagnez chaque jour</div>
            <p style="font-size:0.82rem; color:var(--c-muted); line-height:1.7;">
                Votre solde est crédité automatiquement chaque jour selon votre plan.
            </p>
        </div>

        <div style="background:var(--c-bg2); border:1px solid var(--c-border); padding:1.5rem; text-align:center;">
            <div style="font-size:1.75rem; margin-bottom:0.75rem;">📲</div>
            <div style="font-family:var(--font-display); font-weight:700; font-size:0.85rem; letter-spacing:0.06em; text-transform:uppercase; margin-bottom:0.5rem;">Retirez</div>
            <p style="font-size:0.82rem; color:var(--c-muted); line-height:1.7;">
                Demandez un retrait via Orange Money ou Moov Money dès le seuil atteint.
            </p>
        </div>

    </div>

    {{-- Plans investissement --}}
    <div style="display:grid; grid-template-columns:repeat({{ $plansInvestissement->count() }}, 1fr); gap:1rem;">
        @foreach($plansInvestissement as $index => $plan)
            @php $isPopular = $index === 1 || $plansInvestissement->count() === 1; @endphp
            <div style="background:var(--c-bg2); border:1px solid {{ $isPopular ? 'var(--c-gold)' : 'var(--c-border)' }}; padding:2rem 1.5rem; text-align:center; position:relative;">

                @if($isPopular)
                    <div style="position:absolute; top:-1px; left:50%; transform:translateX(-50%); background:var(--c-gold); color:#000; font-family:var(--font-display); font-size:0.65rem; font-weight:800; letter-spacing:0.1em; text-transform:uppercase; padding:3px 12px; white-space:nowrap;">
                        Recommandé
                    </div>
                @endif

                <div style="font-family:var(--font-display); font-weight:700; font-size:1rem; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-muted); margin-bottom:1rem;">
                    {{ $plan->nom }}
                </div>

                <div style="font-family:var(--font-display); font-size:2.5rem; font-weight:800; color:{{ $isPopular ? 'var(--c-gold)' : 'var(--c-text)' }}; line-height:1; margin-bottom:0.25rem;">
                    {{ $plan->prixFormate() }}
                </div>

                <div style="font-size:0.78rem; color:var(--c-muted); margin-bottom:1.25rem;">
                    {{ $plan->duree_jours }} jours
                </div>

                {{-- Gains --}}
                <div style="background:var(--c-bg3); border:1px solid var(--c-border); padding:0.75rem; margin-bottom:1.25rem; display:flex; flex-direction:column; gap:0.4rem;">
                    <div style="display:flex; justify-content:space-between; font-size:0.8rem;">
                        <span style="color:var(--c-muted);">Gain / jour</span>
                        <span style="font-family:var(--font-display); font-weight:700; color:var(--c-green);">+ {{ $plan->gainJournalierFormate() }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; font-size:0.8rem;">
                        <span style="color:var(--c-muted);">Gain total</span>
                        <span style="font-family:var(--font-display); font-weight:700; color:var(--c-green);">+ {{ $plan->gainTotalFormate() }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; font-size:0.8rem;">
                        <span style="color:var(--c-muted);">Seuil retrait</span>
                        <span style="font-family:var(--font-display); font-weight:700;">{{ $plan->seuilRetraitFormate() }}</span>
                    </div>
                </div>

                <a href="{{ route('register') }}"
                   style="display:flex; align-items:center; justify-content:center; width:100%; padding:10px; font-family:var(--font-display); font-weight:700; font-size:0.82rem; letter-spacing:0.08em; text-transform:uppercase; text-decoration:none; transition:all 0.2s; {{ $isPopular ? 'background:var(--c-gold); color:#000;' : 'background:var(--c-bg3); color:var(--c-text); border:1px solid var(--c-border);' }}"
                   onmouseover="this.style.opacity='0.85'"
                   onmouseout="this.style.opacity='1'">
                    Investir maintenant →
                </a>

            </div>
        @endforeach
    </div>

    <div style="text-align:center; margin-top:1.5rem; font-size:0.82rem; color:var(--c-muted);">
        Gains crédités automatiquement chaque jour &nbsp;•&nbsp; Retrait via 🟠 Orange Money &nbsp;&amp;&nbsp; 🔵 Moov Money
    </div>

</section>

@endif