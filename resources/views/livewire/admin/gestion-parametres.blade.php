<div>

    {{-- Flash --}}
    @if(session('success'))
        <div style="display:flex; align-items:center; gap:0.6rem; background:var(--c-bg2); border:1px solid var(--c-green); border-left:3px solid var(--c-green); padding:0.75rem 1rem; margin-bottom:1.5rem; font-family:var(--font-display); font-size:0.82rem; color:var(--c-green);">
            ✓ {{ session('success') }}
        </div>
    @endif

    {{-- En-tête --}}
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem;">
        <div style="font-family:var(--font-display); font-weight:700; font-size:0.85rem; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-text);">
            Paramètres
            <span style="font-weight:400; color:var(--c-muted); font-size:0.75rem; letter-spacing:0.04em; margin-left:0.5rem;">
                {{ count($parametres) }} entrée{{ count($parametres) > 1 ? 's' : '' }}
            </span>
        </div>
        <button wire:click="ouvrir()" style="display:inline-flex; align-items:center; gap:0.4rem; background:var(--c-green); color:#fff; border:none; padding:8px 18px; font-family:var(--font-display); font-size:0.78rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; cursor:pointer;">
            + Nouveau paramètre
        </button>
    </div>

    {{-- Filtres groupes --}}
    @if(count($groupes) > 0)
        <div style="display:flex; flex-wrap:wrap; gap:0.5rem; margin-bottom:1.5rem;">
            <button wire:click="$set('groupeActif','tous')"
                style="padding:5px 14px; font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; cursor:pointer; border:1px solid {{ $groupeActif === 'tous' ? 'var(--c-green)' : 'var(--c-border)' }}; background:{{ $groupeActif === 'tous' ? 'var(--c-green)' : 'var(--c-bg2)' }}; color:{{ $groupeActif === 'tous' ? '#fff' : 'var(--c-muted)' }};">
                Tous
            </button>
            @foreach($groupes as $g)
                <button wire:click="$set('groupeActif','{{ $g }}')"
                    style="padding:5px 14px; font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; cursor:pointer; border:1px solid {{ $groupeActif === $g ? 'var(--c-green)' : 'var(--c-border)' }}; background:{{ $groupeActif === $g ? 'var(--c-green)' : 'var(--c-bg2)' }}; color:{{ $groupeActif === $g ? '#fff' : 'var(--c-muted)' }};">
                    {{ $g }}
                </button>
            @endforeach
        </div>
    @endif

    {{-- Tableau --}}
    @if($parametres->isEmpty())
        <div style="background:var(--c-bg2); border:1px solid var(--c-border); padding:3rem 1.5rem; text-align:center; color:var(--c-muted); font-family:var(--font-display); font-size:0.82rem; letter-spacing:0.04em;">
            Aucun paramètre trouvé.
        </div>
    @else
        {{-- Vue tablette/desktop --}}
        <div style="background:var(--c-bg2); border:1px solid var(--c-border); overflow-x:auto; display:none;" class="table-wrapper">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="border-bottom:1px solid var(--c-border);">
                        @foreach(['Libellé','Clé','Groupe','Type','Valeur',''] as $col)
                            <th style="padding:10px 14px; text-align:left; font-family:var(--font-display); font-size:0.68rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); white-space:nowrap;">
                                {{ $col }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($parametres as $p)
                        <tr style="border-bottom:1px solid var(--c-border); transition:background 0.15s;"
                            onmouseover="this.style.background='var(--c-bg3)'"
                            onmouseout="this.style.background='transparent'">
                            <td style="padding:10px 14px; font-family:var(--font-display); font-size:0.85rem; color:var(--c-text); font-weight:600;">
                                {{ $p->libelle }}
                            </td>
                            <td style="padding:10px 14px;">
                                <code style="font-size:0.78rem; color:var(--c-muted); background:var(--c-bg3); padding:2px 6px; border:1px solid var(--c-border);">{{ $p->cle }}</code>
                            </td>
                            <td style="padding:10px 14px; font-family:var(--font-display); font-size:0.78rem; color:var(--c-muted); text-transform:uppercase; letter-spacing:0.06em;">
                                {{ $p->groupe }}
                            </td>
                            <td style="padding:10px 14px; font-family:var(--font-display); font-size:0.75rem; color:var(--c-muted);">
                                <span style="background:var(--c-bg3); border:1px solid var(--c-border); padding:2px 8px; letter-spacing:0.06em; text-transform:uppercase;">{{ $p->type }}</span>
                            </td>
                            <td style="padding:10px 14px; font-size:0.82rem; color:var(--c-text); max-width:220px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; font-family:var(--font-body);">
                                {{ $p->valeur ?? '—' }}
                            </td>
                            <td style="padding:10px 14px; white-space:nowrap; text-align:right;">
                                <button wire:click="ouvrir('{{ $p->cle }}')"
                                    style="background:none; border:1px solid var(--c-border); color:var(--c-muted); padding:5px 12px; font-family:var(--font-display); font-size:0.7rem; font-weight:700; letter-spacing:0.06em; text-transform:uppercase; cursor:pointer; margin-right:0.4rem;"
                                    onmouseover="this.style.borderColor='var(--c-text)';this.style.color='var(--c-text)'"
                                    onmouseout="this.style.borderColor='var(--c-border)';this.style.color='var(--c-muted)'">
                                    Modifier
                                </button>
                                <button wire:click="supprimer('{{ $p->cle }}')"
                                    wire:confirm="Supprimer « {{ $p->libelle }} » ?"
                                    style="background:none; border:1px solid var(--c-danger); color:var(--c-danger); padding:5px 12px; font-family:var(--font-display); font-size:0.7rem; font-weight:700; letter-spacing:0.06em; text-transform:uppercase; cursor:pointer;"
                                    onmouseover="this.style.background='var(--c-danger)';this.style.color='#fff'"
                                    onmouseout="this.style.background='none';this.style.color='var(--c-danger)'">
                                    Suppr.
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Vue mobile : cartes --}}
        <div class="cards-wrapper">
            @foreach($parametres as $p)
                <div style="background:var(--c-bg2); border:1px solid var(--c-border); padding:1rem; margin-bottom:0.75rem;">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:0.5rem; margin-bottom:0.6rem;">
                        <div>
                            <div style="font-family:var(--font-display); font-weight:700; font-size:0.88rem; color:var(--c-text); margin-bottom:0.25rem;">{{ $p->libelle }}</div>
                            <code style="font-size:0.75rem; color:var(--c-muted); background:var(--c-bg3); padding:2px 6px; border:1px solid var(--c-border);">{{ $p->cle }}</code>
                        </div>
                        <div style="display:flex; gap:0.4rem; flex-shrink:0;">
                            <button wire:click="ouvrir('{{ $p->cle }}')"
                                style="background:none; border:1px solid var(--c-border); color:var(--c-muted); padding:5px 10px; font-family:var(--font-display); font-size:0.68rem; font-weight:700; letter-spacing:0.06em; text-transform:uppercase; cursor:pointer;">
                                Modifier
                            </button>
                            <button wire:click="supprimer('{{ $p->cle }}')"
                                wire:confirm="Supprimer « {{ $p->libelle }} » ?"
                                style="background:none; border:1px solid var(--c-danger); color:var(--c-danger); padding:5px 10px; font-family:var(--font-display); font-size:0.68rem; font-weight:700; letter-spacing:0.06em; text-transform:uppercase; cursor:pointer;">
                                ✕
                            </button>
                        </div>
                    </div>
                    <div style="display:flex; gap:0.5rem; flex-wrap:wrap; margin-bottom:0.5rem;">
                        <span style="font-family:var(--font-display); font-size:0.68rem; text-transform:uppercase; letter-spacing:0.06em; color:var(--c-muted); background:var(--c-bg3); border:1px solid var(--c-border); padding:2px 8px;">{{ $p->groupe }}</span>
                        <span style="font-family:var(--font-display); font-size:0.68rem; text-transform:uppercase; letter-spacing:0.06em; color:var(--c-muted); background:var(--c-bg3); border:1px solid var(--c-border); padding:2px 8px;">{{ $p->type }}</span>
                    </div>
                    @if($p->valeur)
                        <div style="font-size:0.8rem; color:var(--c-muted); font-family:var(--font-body); overflow:hidden; text-overflow:ellipsis; white-space:nowrap; border-top:1px solid var(--c-border); padding-top:0.5rem; margin-top:0.5rem;">
                            {{ $p->valeur }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    {{-- Modal --}}
    @if($showModal)
        {{-- Overlay --}}
        <div wire:click="$set('showModal',false)"
            style="position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:998;">
        </div>

        {{-- Panel --}}
        <div style="position:fixed; top:0; right:0; bottom:0; width:min(480px,100vw); background:var(--c-bg2); border-left:1px solid var(--c-border); z-index:999; overflow-y:auto; display:flex; flex-direction:column;">

            {{-- Header --}}
            <div style="display:flex; align-items:center; justify-content:space-between; padding:1.25rem 1.5rem; border-bottom:1px solid var(--c-border); position:sticky; top:0; background:var(--c-bg2); z-index:1;">
                <div style="font-family:var(--font-display); font-weight:700; font-size:0.85rem; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-text);">
                    {{ $modeEdition ? 'Modifier le paramètre' : 'Nouveau paramètre' }}
                </div>
                <button wire:click="$set('showModal',false)"
                    style="background:none; border:none; color:var(--c-muted); font-size:1.2rem; cursor:pointer; line-height:1; padding:0 4px;"
                    onmouseover="this.style.color='var(--c-text)'"
                    onmouseout="this.style.color='var(--c-muted)'">✕</button>
            </div>

            {{-- Corps --}}
            <div style="padding:1.5rem; flex:1;">

                {{-- Libellé --}}
                <div style="margin-bottom:1rem;">
                    <label style="display:block; font-family:var(--font-display); font-size:0.7rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.4rem;">
                        Libellé <span style="color:var(--c-danger);">*</span>
                    </label>
                    <input wire:model="libelle" type="text" placeholder="Ex: Numéro WhatsApp"
                        style="width:100%; background:var(--c-bg3); border:1px solid {{ $errors->has('libelle') ? 'var(--c-danger)' : 'var(--c-border)' }}; color:var(--c-text); padding:9px 12px; font-family:var(--font-display); font-size:0.88rem; outline:none; box-sizing:border-box;">
                    @error('libelle')
                        <div style="color:var(--c-danger); font-size:0.75rem; margin-top:0.3rem; font-family:var(--font-display);">✕ {{ $message }}</div>
                    @enderror
                </div>

                {{-- Clé --}}
                <div style="margin-bottom:1rem;">
                    <label style="display:block; font-family:var(--font-display); font-size:0.7rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.4rem;">
                        Clé <span style="color:var(--c-danger);">*</span>
                        <span style="font-weight:400; font-size:0.65rem; color:var(--c-muted);">(minuscules, chiffres, _)</span>
                    </label>
                    <input wire:model="cle" type="text" placeholder="auto"
                        {{ ! $modeEdition ? 'readonly' : '' }}
                        style="width:100%; background:var(--c-bg3); border:1px solid {{ $errors->has('cle') ? 'var(--c-danger)' : 'var(--c-border)' }}; color:{{ ! $modeEdition ? 'var(--c-muted)' : 'var(--c-text)' }}; padding:9px 12px; font-family:monospace; font-size:0.88rem; outline:none; box-sizing:border-box; {{ $modeEdition ? '' : 'cursor:default;' }}">
                    @error('cle')
                        <div style="color:var(--c-danger); font-size:0.75rem; margin-top:0.3rem; font-family:var(--font-display);">✕ {{ $message }}</div>
                    @enderror
                    @if($modeEdition)
                        <div style="font-size:0.72rem; color:var(--c-muted); margin-top:0.3rem; font-family:var(--font-display);">⚠ Modifier la clé recrée l'entrée.</div>
                    @endif
                </div>

                {{-- Groupe + Type --}}
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">

                    <div>
                        <label style="display:block; font-family:var(--font-display); font-size:0.7rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.4rem;">
                            Groupe <span style="color:var(--c-danger);">*</span>
                        </label>
                        <input wire:model="groupe" type="text" placeholder="general"
                            style="width:100%; background:var(--c-bg3); border:1px solid {{ $errors->has('groupe') ? 'var(--c-danger)' : 'var(--c-border)' }}; color:var(--c-text); padding:9px 12px; font-family:var(--font-display); font-size:0.88rem; outline:none; box-sizing:border-box;">
                        @error('groupe')
                            <div style="color:var(--c-danger); font-size:0.75rem; margin-top:0.3rem; font-family:var(--font-display);">✕ {{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label style="display:block; font-family:var(--font-display); font-size:0.7rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.4rem;">
                            Type <span style="color:var(--c-danger);">*</span>
                        </label>
                        <select wire:model="type"
                            style="width:100%; background:var(--c-bg3); border:1px solid {{ $errors->has('type') ? 'var(--c-danger)' : 'var(--c-border)' }}; color:var(--c-text); padding:9px 12px; font-family:var(--font-display); font-size:0.88rem; outline:none; box-sizing:border-box; cursor:pointer;">
                            @foreach($types as $t)
                                <option value="{{ $t }}">{{ $t }}</option>
                            @endforeach
                        </select>
                        @error('type')
                            <div style="color:var(--c-danger); font-size:0.75rem; margin-top:0.3rem; font-family:var(--font-display);">✕ {{ $message }}</div>
                        @enderror
                    </div>

                </div>

                {{-- Valeur --}}
                <div style="margin-bottom:1.5rem;">
                    <label style="display:block; font-family:var(--font-display); font-size:0.7rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.4rem;">
                        Valeur
                    </label>
                    @if($type === 'textarea')
                        <textarea wire:model="valeur" rows="4" placeholder="Valeur du paramètre…"
                            style="width:100%; background:var(--c-bg3); border:1px solid {{ $errors->has('valeur') ? 'var(--c-danger)' : 'var(--c-border)' }}; color:var(--c-text); padding:9px 12px; font-family:var(--font-body); font-size:0.88rem; outline:none; resize:vertical; box-sizing:border-box;"></textarea>
                    @else
                        <input wire:model="valeur" type="{{ $type }}" placeholder="Valeur du paramètre"
                            style="width:100%; background:var(--c-bg3); border:1px solid {{ $errors->has('valeur') ? 'var(--c-danger)' : 'var(--c-border)' }}; color:var(--c-text); padding:9px 12px; font-family:var(--font-display); font-size:0.88rem; outline:none; box-sizing:border-box;">
                    @endif
                    @error('valeur')
                        <div style="color:var(--c-danger); font-size:0.75rem; margin-top:0.3rem; font-family:var(--font-display);">✕ {{ $message }}</div>
                    @enderror
                </div>

            </div>

            {{-- Footer actions --}}
            <div style="display:flex; gap:0.75rem; padding:1.25rem 1.5rem; border-top:1px solid var(--c-border); position:sticky; bottom:0; background:var(--c-bg2);">
                <button wire:click="sauvegarder"
                    style="flex:1; background:var(--c-green); color:#fff; border:none; padding:10px 20px; font-family:var(--font-display); font-size:0.78rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; cursor:pointer;">
                    <span wire:loading.remove wire:target="sauvegarder">💾 Sauvegarder</span>
                    <span wire:loading wire:target="sauvegarder">…</span>
                </button>
                <button wire:click="$set('showModal',false)"
                    style="background:none; border:1px solid var(--c-border); color:var(--c-muted); padding:10px 20px; font-family:var(--font-display); font-size:0.78rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; cursor:pointer;">
                    Annuler
                </button>
            </div>

        </div>
    @endif

    {{-- Responsive : affichage table sur écran large --}}
    <style>
        @media (min-width: 640px) {
            .table-wrapper { display: block !important; }
            .cards-wrapper  { display: none !important; }
        }
    </style>

</div>