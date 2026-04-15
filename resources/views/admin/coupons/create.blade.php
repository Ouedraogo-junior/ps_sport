@extends('layouts.admin')

@section('title', 'Nouveau coupon')

@section('content')

<div style="max-width:860px;">

<div style="display:flex; align-items:center; gap:1rem; margin-bottom:1.5rem;">
    <a href="{{ route('admin.coupons.index') }}" style="color:var(--c-muted); text-decoration:none; font-family:var(--font-display); font-size:0.85rem; letter-spacing:0.06em; text-transform:uppercase;">
        ← Retour
    </a>
    <div style="font-family:var(--font-display); font-size:1.4rem; font-weight:800; letter-spacing:0.04em; text-transform:uppercase;">
        Nouveau coupon
    </div>
</div>

<form method="POST" action="{{ route('admin.coupons.store') }}">
    @csrf

    {{-- Infos principales --}}
    <div style="background:var(--c-bg2); border:1px solid var(--c-border); padding:1.5rem; margin-bottom:1rem;">
        <div style="font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:1rem;">
            Informations
        </div>

        <div style="display:grid; grid-template-columns:1fr 200px; gap:1rem; margin-bottom:1rem;">
            <div>
                <label style="display:block; font-family:var(--font-display); font-size:0.75rem; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-muted); margin-bottom:6px;">Titre *</label>
                <input type="text" name="titre" value="{{ old('titre') }}" required
                       style="width:100%; background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:9px 12px; font-family:var(--font-body); font-size:0.9rem; outline:none; transition:border-color 0.2s;"
                       onfocus="this.style.borderColor='var(--c-green)'"
                       onblur="this.style.borderColor='var(--c-border)'"
                       placeholder="Ex: Combiné ligue des champions">
                @error('titre')<div style="color:var(--c-danger); font-size:0.78rem; margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div>
                <label style="display:block; font-family:var(--font-display); font-size:0.75rem; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-muted); margin-bottom:6px;">Niveau de risque *</label>
                <select name="niveau_risque" required
                        style="width:100%; background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:9px 12px; font-family:var(--font-body); font-size:0.9rem; outline:none;">
                    <option value="">— Choisir —</option>
                    @foreach(['faible' => 'Faible risque', 'modere' => 'Risque modéré', 'risque' => 'Risqué'] as $val => $label)
                        <option value="{{ $val }}" {{ old('niveau_risque') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div style="margin-bottom:1rem;">
            <label style="display:block; font-family:var(--font-display); font-size:0.75rem; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-muted); margin-bottom:6px;">Description courte</label>
            <textarea name="description" rows="2"
                      style="width:100%; background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:9px 12px; font-family:var(--font-body); font-size:0.9rem; outline:none; resize:vertical;"
                      onfocus="this.style.borderColor='var(--c-green)'"
                      onblur="this.style.borderColor='var(--c-border)'"
                      placeholder="Résumé visible dans la liste">{{ old('description') }}</textarea>
        </div>

        <div>
            <label style="display:block; font-family:var(--font-display); font-size:0.75rem; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-muted); margin-bottom:6px;">Analyse détaillée</label>
            <textarea name="analyse" rows="4"
                      style="width:100%; background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:9px 12px; font-family:var(--font-body); font-size:0.9rem; outline:none; resize:vertical;"
                      onfocus="this.style.borderColor='var(--c-green)'"
                      onblur="this.style.borderColor='var(--c-border)'"
                      placeholder="Analyse visible uniquement pour les abonnés">{{ old('analyse') }}</textarea>
        </div>
    </div>

    {{-- Sélections (matchs) --}}
    <div style="background:var(--c-bg2); border:1px solid var(--c-border); padding:1.5rem; margin-bottom:1rem;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
            <div style="font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted);">
                Sélections (matchs) — optionnel
            </div>
            <button type="button" onclick="ajouterSelection()" class="btn-sm-outline">+ Ajouter</button>
        </div>

        <div id="selections-container">
            {{-- Ligne vide par défaut --}}
            <div class="selection-row" style="display:grid; grid-template-columns:1fr 1fr 1fr 120px 1fr 80px 30px; gap:8px; margin-bottom:8px; align-items:start;">
                <input type="text" name="selections[0][equipe_domicile]" placeholder="Équipe dom." style="background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:7px 10px; font-size:0.82rem; width:100%; outline:none;">
                <input type="text" name="selections[0][equipe_exterieur]" placeholder="Équipe ext." style="background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:7px 10px; font-size:0.82rem; width:100%; outline:none;">
                <input type="text" name="selections[0][competition]" placeholder="Compétition" style="background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:7px 10px; font-size:0.82rem; width:100%; outline:none;">
                <input type="datetime-local" name="selections[0][date_match]" style="background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:7px 10px; font-size:0.82rem; width:100%; outline:none;">
                <input type="text" name="selections[0][type_pari]" placeholder="Type pari" style="background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:7px 10px; font-size:0.82rem; width:100%; outline:none;">
                <input type="number" name="selections[0][cote]" placeholder="Cote" step="0.01" min="1" style="background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:7px 10px; font-size:0.82rem; width:100%; outline:none;">
                <button type="button" onclick="supprimerLigne(this)" style="background:none; border:none; color:var(--c-danger); cursor:pointer; font-size:1rem; padding:7px 0;">✕</button>
            </div>
        </div>
    </div>

    {{-- Codes bookmakers --}}
    <div style="background:var(--c-bg2); border:1px solid var(--c-border); padding:1.5rem; margin-bottom:1.5rem;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
            <div style="font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted);">
                Codes bookmakers — optionnel
            </div>
            <button type="button" onclick="ajouterCode()" class="btn-sm-outline">+ Ajouter</button>
        </div>

        <div id="codes-container">
            <div class="code-row" style="display:grid; grid-template-columns:200px 1fr 30px; gap:8px; margin-bottom:8px; align-items:start;">
                <select name="codes[0][bookmaker]" style="background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:7px 10px; font-size:0.82rem; width:100%; outline:none;">
                    <option value="">— Bookmaker —</option>
                    <option value="1xbet">1xBet</option>
                    <option value="betwinner">BetWinner</option>
                    <option value="melbet">Melbet</option>
                    <option value="1win">1Win</option>
                </select>
                <input type="text" name="codes[0][code]" placeholder="Code du coupon" style="background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:7px 10px; font-size:0.82rem; width:100%; outline:none;">
                <button type="button" onclick="supprimerLigne(this)" style="background:none; border:none; color:var(--c-danger); cursor:pointer; font-size:1rem; padding:7px 0;">✕</button>
            </div>
        </div>
    </div>

    <div style="display:flex; gap:1rem;">
        <button type="submit" class="btn-sm-green" style="padding:10px 28px; font-size:0.9rem;">
            Enregistrer
        </button>
        <a href="{{ route('admin.coupons.index') }}" class="btn-sm-outline" style="padding:10px 28px; font-size:0.9rem;">
            Annuler
        </a>
    </div>

</form>
</div>

@endsection

@push('scripts')
<script>
let selIdx = 1;
let codeIdx = 1;

function ajouterSelection() {
    const container = document.getElementById('selections-container');
    const i = selIdx++;
    const row = document.createElement('div');
    row.className = 'selection-row';
    row.style.cssText = 'display:grid; grid-template-columns:1fr 1fr 1fr 120px 1fr 80px 30px; gap:8px; margin-bottom:8px; align-items:start;';
    row.innerHTML = `
        <input type="text" name="selections[${i}][equipe_domicile]" placeholder="Équipe dom." style="background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:7px 10px; font-size:0.82rem; width:100%; outline:none;">
        <input type="text" name="selections[${i}][equipe_exterieur]" placeholder="Équipe ext." style="background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:7px 10px; font-size:0.82rem; width:100%; outline:none;">
        <input type="text" name="selections[${i}][competition]" placeholder="Compétition" style="background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:7px 10px; font-size:0.82rem; width:100%; outline:none;">
        <input type="datetime-local" name="selections[${i}][date_match]" style="background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:7px 10px; font-size:0.82rem; width:100%; outline:none;">
        <input type="text" name="selections[${i}][type_pari]" placeholder="Type pari" style="background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:7px 10px; font-size:0.82rem; width:100%; outline:none;">
        <input type="number" name="selections[${i}][cote]" placeholder="Cote" step="0.01" min="1" style="background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:7px 10px; font-size:0.82rem; width:100%; outline:none;">
        <button type="button" onclick="supprimerLigne(this)" style="background:none; border:none; color:var(--c-danger); cursor:pointer; font-size:1rem; padding:7px 0;">✕</button>
    `;
    container.appendChild(row);
}

function ajouterCode() {
    const container = document.getElementById('codes-container');
    const i = codeIdx++;
    const row = document.createElement('div');
    row.className = 'code-row';
    row.style.cssText = 'display:grid; grid-template-columns:200px 1fr 30px; gap:8px; margin-bottom:8px; align-items:start;';
    row.innerHTML = `
        <select name="codes[${i}][bookmaker]" style="background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:7px 10px; font-size:0.82rem; width:100%; outline:none;">
            <option value="">— Bookmaker —</option>
            <option value="1xbet">1xBet</option>
            <option value="betwinner">BetWinner</option>
            <option value="melbet">Melbet</option>
            <option value="1win">1Win</option>
        </select>
        <input type="text" name="codes[${i}][code]" placeholder="Code du coupon" style="background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:7px 10px; font-size:0.82rem; width:100%; outline:none;">
        <button type="button" onclick="supprimerLigne(this)" style="background:none; border:none; color:var(--c-danger); cursor:pointer; font-size:1rem; padding:7px 0;">✕</button>
    `;
    container.appendChild(row);
}

function supprimerLigne(btn) {
    btn.closest('.selection-row, .code-row').remove();
}
</script>
@endpush