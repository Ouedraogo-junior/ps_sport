{{--
    Partial : bloc investissement
    Variables requises : $solde, $estInvestisseur, $seuilRetrait, $demandeEnAttente, $derniersRetraits
--}}

@if($estInvestisseur || $solde->solde > 0)

<div style="background:var(--c-bg2); border:1px solid var(--c-border-g); padding:1.5rem; margin-top:1.5rem;">

    {{-- En-tête --}}
    <div style="font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.12em; text-transform:uppercase; color:var(--c-green); margin-bottom:1.25rem;">
        ◆ Espace investissement
    </div>

    {{-- Solde + total cumulé --}}
    <div class="grid-2" style="margin-bottom:1.25rem;">
        <div style="background:var(--c-bg3); border:1px solid var(--c-border); padding:1rem;">
            <div style="font-size:0.72rem; color:var(--c-muted); margin-bottom:0.4rem; text-transform:uppercase; letter-spacing:0.08em; font-family:var(--font-display); font-weight:700;">
                Solde disponible
            </div>
            <div style="font-family:var(--font-display); font-size:1.5rem; font-weight:800; color:var(--c-green);">
                {{ $solde->soldeFormate() }}
            </div>
        </div>
        <div style="background:var(--c-bg3); border:1px solid var(--c-border); padding:1rem;">
            <div style="font-size:0.72rem; color:var(--c-muted); margin-bottom:0.4rem; text-transform:uppercase; letter-spacing:0.08em; font-family:var(--font-display); font-weight:700;">
                Total cumulé
            </div>
            <div style="font-family:var(--font-display); font-size:1.5rem; font-weight:800; color:var(--c-gold);">
                {{ $solde->totalCumuleFormate() }}
            </div>
        </div>
    </div>

    {{-- Info taux si plan investissement actif --}}
    @if($estInvestisseur)
        @php
            $planActifObj = $abonnementActif
                ? \App\Models\Plan::where('slug', $abonnementActif->plan)->first()
                : null;
        @endphp
        @if($planActifObj && $planActifObj->taux_journalier)
            <div style="background:rgba(0,230,118,0.06); border:1px solid rgba(0,230,118,0.2); padding:0.85rem 1rem; margin-bottom:1.25rem; font-size:0.82rem; color:var(--c-muted); line-height:1.6;">
                Votre plan <strong style="color:var(--c-text);">{{ $planActifObj->nom }}</strong> génère
                <strong style="color:var(--c-green);">{{ $planActifObj->taux_journalier }}% / jour</strong>
                — soit environ
                <strong style="color:var(--c-green);">
                    {{ number_format(($planActifObj->prix * $planActifObj->taux_journalier / 100), 0, ',', ' ') }} FCFA/jour
                </strong>.
                @if($seuilRetrait)
                    Retrait possible dès <strong style="color:var(--c-text);">{{ number_format($seuilRetrait, 0, ',', ' ') }} FCFA</strong>.
                @endif
            </div>
        @endif
    @endif

    {{-- Demande de retrait --}}
    @if($demandeEnAttente)
        <div style="background:rgba(255,171,0,0.06); border:1px solid rgba(255,171,0,0.3); padding:1rem; margin-bottom:1.25rem;">
            <div style="font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-warning); margin-bottom:0.5rem;">
                ⏳ Retrait en cours de traitement
            </div>
            <div style="font-size:0.82rem; color:var(--c-muted); line-height:1.7;">
                <div>Montant : <strong style="color:var(--c-text);">{{ $demandeEnAttente->montantFormate() }}</strong></div>
                <div>Opérateur : <strong style="color:var(--c-text);">{{ $demandeEnAttente->operateurLabel() }}</strong></div>
                <div>Soumis le : <strong style="color:var(--c-text);">{{ $demandeEnAttente->created_at->format('d/m/Y à H:i') }}</strong></div>
            </div>
        </div>

    @elseif($estInvestisseur && $seuilRetrait && $solde->solde >= $seuilRetrait)

        {{-- Formulaire retrait --}}
        <div x-data="{ ouvert: false }" style="margin-bottom:1.25rem;">

            <button @click="ouvert = !ouvert"
                style="width:100%; background:transparent; border:1px solid var(--c-green); color:var(--c-green); font-family:var(--font-display); font-weight:700; font-size:0.82rem; letter-spacing:0.08em; text-transform:uppercase; padding:11px; cursor:pointer; transition:all 0.2s;"
                onmouseover="this.style.background='rgba(0,230,118,0.08)'"
                onmouseout="this.style.background='transparent'">
                <span x-text="ouvert ? '✕ Fermer' : '↑ Demander un retrait'"></span>
            </button>

            <div x-show="ouvert" x-transition style="border:1px solid var(--c-border); border-top:none; padding:1.25rem;">

                @if(session('error'))
                    <div style="background:rgba(239,68,68,0.08); border:1px solid rgba(239,68,68,0.3); padding:0.75rem; font-size:0.82rem; color:var(--c-danger); margin-bottom:1rem; font-family:var(--font-display); font-weight:600;">
                        ✕ {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('dashboard.retrait') }}">
                    @csrf

                    {{-- Montant --}}
                    <div style="margin-bottom:1rem;">
                        <label style="display:block; font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.5rem;">
                            Montant (FCFA) *
                        </label>
                        <input type="number" name="montant"
                            value="{{ old('montant') }}"
                            min="{{ $seuilRetrait }}"
                            max="{{ floor($solde->solde) }}"
                            placeholder="Ex : {{ number_format($seuilRetrait, 0, ',', ' ') }}"
                            style="width:100%; background:var(--c-bg3); border:1px solid {{ $errors->has('montant') ? 'var(--c-danger)' : 'var(--c-border-g)' }}; color:var(--c-text); padding:11px 14px; font-family:var(--font-display); font-size:1rem; font-weight:700; outline:none;">
                        @error('montant')
                            <div style="color:var(--c-danger); font-size:0.8rem; margin-top:0.4rem; font-family:var(--font-display); font-weight:600;">✕ {{ $message }}</div>
                        @enderror
                        <div style="font-size:0.75rem; color:var(--c-muted); margin-top:0.3rem;">
                            Max disponible : {{ $solde->soldeFormate() }}
                        </div>
                    </div>

                    {{-- Opérateur --}}
                    <div style="margin-bottom:1rem;">
                        <label style="display:block; font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.5rem;">
                            Opérateur *
                        </label>
                        <div style="display:flex; flex-direction:column; gap:0.4rem;">
                            @foreach([['orange', '🟠 Orange Money'], ['moov', '🔵 Moov Money'], ['wave', '🔷 Wave']] as [$val, $label])
                                <label style="display:flex; align-items:center; gap:0.75rem; background:var(--c-bg3); border:1px solid var(--c-border); padding:0.7rem 1rem; cursor:pointer;">
                                    <input type="radio" name="operateur" value="{{ $val }}"
                                        {{ old('operateur') === $val ? 'checked' : '' }}
                                        style="accent-color:var(--c-green);">
                                    <span style="font-size:0.88rem;">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('operateur')
                            <div style="color:var(--c-danger); font-size:0.8rem; margin-top:0.4rem; font-family:var(--font-display); font-weight:600;">✕ {{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Numéro --}}
                    <div style="margin-bottom:1.25rem;">
                        <label style="display:block; font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.5rem;">
                            Numéro de réception *
                        </label>
                        <input type="text" name="numero_telephone"
                            value="{{ old('numero_telephone', $user->telephone) }}"
                            placeholder="Ex : 0700000000"
                            maxlength="20"
                            style="width:100%; background:var(--c-bg3); border:1px solid {{ $errors->has('numero_telephone') ? 'var(--c-danger)' : 'var(--c-border-g)' }}; color:var(--c-text); padding:11px 14px; font-family:var(--font-display); font-size:1rem; font-weight:700; outline:none;">
                        @error('numero_telephone')
                            <div style="color:var(--c-danger); font-size:0.8rem; margin-top:0.4rem; font-family:var(--font-display); font-weight:600;">✕ {{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn-primary" style="width:100%; justify-content:center; padding:12px;">
                        Soumettre la demande →
                    </button>
                </form>
            </div>
        </div>

    @elseif($estInvestisseur && $seuilRetrait && $solde->solde < $seuilRetrait)

        {{-- Progression vers seuil --}}
        @php
            $progression = $seuilRetrait > 0 ? min(100, ($solde->solde / $seuilRetrait) * 100) : 0;
            $restant     = max(0, $seuilRetrait - $solde->solde);
        @endphp
        <div style="margin-bottom:1.25rem;">
            <div style="display:flex; justify-content:space-between; font-size:0.78rem; color:var(--c-muted); margin-bottom:0.4rem;">
                <span>Progression vers le seuil de retrait</span>
                <span style="color:var(--c-text); font-weight:600; font-family:var(--font-display);">
                    {{ number_format($restant, 0, ',', ' ') }} FCFA restants
                </span>
            </div>
            <div style="height:6px; background:var(--c-bg3); border-radius:3px; overflow:hidden;">
                <div style="height:100%; width:{{ $progression }}%; background:var(--c-green); transition:width 0.5s;"></div>
            </div>
            <div style="font-size:0.75rem; color:var(--c-muted); margin-top:0.4rem; text-align:right;">
                {{ number_format($progression, 1) }}% — seuil : {{ number_format($seuilRetrait, 0, ',', ' ') }} FCFA
            </div>
        </div>

    @endif

    {{-- Historique des retraits --}}
    @if($derniersRetraits->isNotEmpty())
        <div style="border-top:1px solid var(--c-border); padding-top:1rem;">
            <div style="font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.75rem;">
                Historique des retraits
            </div>
            @foreach($derniersRetraits as $retrait)
                @php
                    $badge = $retrait->statutBadge();
                    $couleurBadge = match($retrait->statut) {
                        'valide'     => 'color:var(--c-green); border:1px solid rgba(0,230,118,0.3); background:rgba(0,230,118,0.08);',
                        'rejete'     => 'color:var(--c-danger); border:1px solid rgba(239,68,68,0.3); background:rgba(239,68,68,0.08);',
                        default      => 'color:var(--c-warning); border:1px solid rgba(255,171,0,0.3); background:rgba(255,171,0,0.06);',
                    };
                @endphp
                <div style="display:flex; justify-content:space-between; align-items:center; padding:0.65rem 0; border-bottom:1px solid var(--c-border); gap:0.5rem; flex-wrap:wrap;">
                    <div>
                        <div style="font-family:var(--font-display); font-weight:700; font-size:0.88rem; color:var(--c-green);">
                            {{ $retrait->montantFormate() }}
                        </div>
                        <div style="font-size:0.75rem; color:var(--c-muted); margin-top:2px;">
                            {{ $retrait->operateurLabel() }} · {{ $retrait->created_at->format('d/m/Y') }}
                        </div>
                        @if($retrait->statut === 'rejete' && $retrait->note_admin)
                            <div style="font-size:0.73rem; color:var(--c-danger); margin-top:2px;">
                                ✕ {{ $retrait->note_admin }}
                            </div>
                        @endif
                    </div>
                    <span style="font-family:var(--font-display); font-size:0.68rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; padding:3px 8px; white-space:nowrap; {{ $couleurBadge }}">
                        {{ $badge['label'] }}
                    </span>
                </div>
            @endforeach
        </div>
    @endif

</div>

@endif