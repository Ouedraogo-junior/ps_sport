@extends('layouts.app')

@section('title', 'Toutes les captures gagnantes')

@section('content')
<div x-data="{ lightboxOpen: false, lightboxSrc: '' }" style="max-width:960px; margin:0 auto; padding:2rem 1.5rem;">

    <div style="margin-bottom:2rem;">
        <a href="{{ route('performances') }}" style="font-family:var(--font-display); font-size:0.75rem; color:var(--c-muted); text-decoration:none; letter-spacing:0.08em; text-transform:uppercase;">
            ← Retour aux performances
        </a>
        <h1 style="font-family:var(--font-display); font-size:1.8rem; font-weight:800; letter-spacing:0.03em; text-transform:uppercase; margin-top:0.75rem;">
            Captures gagnantes
        </h1>
        <p style="color:var(--c-muted); font-size:0.85rem; margin-top:0.4rem;">
            {{ $captures->total() }} capture(s) au total
        </p>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(200px, 1fr)); gap:1rem;">
        @forelse($captures as $coupon)
            <a href="{{ Storage::url($coupon->capture_gagnant) }}"
               @click.prevent="lightboxSrc = @js(Storage::url($coupon->capture_gagnant)); lightboxOpen = true"
               style="display:block; border:1px solid var(--c-border); overflow:hidden; text-decoration:none; cursor:pointer;">
                <img src="{{ Storage::url($coupon->capture_gagnant) }}"
                     style="width:100%; height:150px; object-fit:cover; display:block;">
                <div style="padding:8px 10px; background:var(--c-bg2);">
                    <div style="font-family:var(--font-display); font-size:0.68rem; font-weight:700; letter-spacing:0.06em; text-transform:uppercase; color:var(--c-green);">✓ Gagné</div>
                    <div style="font-family:var(--font-body); font-size:0.78rem; color:var(--c-muted); margin-top:3px;">
                        {{ Str::limit($coupon->titre, 35) }}
                    </div>
                    <div style="font-family:var(--font-display); font-size:0.68rem; color:var(--c-muted); margin-top:3px;">
                        {{ $coupon->publie_le?->format('d/m/Y') ?? $coupon->updated_at->format('d/m/Y') }}
                    </div>
                </div>
            </a>
        @empty
            <div style="grid-column:1/-1; text-align:center; padding:3rem; color:var(--c-muted); font-family:var(--font-display); letter-spacing:0.06em; text-transform:uppercase; font-size:0.85rem;">
                Aucune capture disponible.
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($captures->hasPages())
        <div style="display:flex; justify-content:center; gap:0.5rem; margin-top:1.5rem; flex-wrap:wrap;">
            @if($captures->onFirstPage())
                <span style="padding:6px 14px; background:var(--c-bg2); border:1px solid var(--c-border); color:var(--c-muted); font-family:var(--font-display); font-size:0.8rem; font-weight:700; letter-spacing:0.06em; text-transform:uppercase;">← Préc.</span>
            @else
                <a href="{{ $captures->previousPageUrl() }}" style="padding:6px 14px; background:var(--c-bg2); border:1px solid var(--c-border); color:var(--c-text); font-family:var(--font-display); font-size:0.8rem; font-weight:700; letter-spacing:0.06em; text-transform:uppercase; text-decoration:none;">← Préc.</a>
            @endif
            <span style="padding:6px 14px; background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-muted); font-family:var(--font-display); font-size:0.8rem; letter-spacing:0.06em; text-transform:uppercase;">
                Page {{ $captures->currentPage() }} / {{ $captures->lastPage() }}
            </span>
            @if($captures->hasMorePages())
                <a href="{{ $captures->nextPageUrl() }}" style="padding:6px 14px; background:var(--c-bg2); border:1px solid var(--c-border); color:var(--c-text); font-family:var(--font-display); font-size:0.8rem; font-weight:700; letter-spacing:0.06em; text-transform:uppercase; text-decoration:none;">Suiv. →</a>
            @else
                <span style="padding:6px 14px; background:var(--c-bg2); border:1px solid var(--c-border); color:var(--c-muted); font-family:var(--font-display); font-size:0.8rem; font-weight:700; letter-spacing:0.06em; text-transform:uppercase;">Suiv. →</span>
            @endif
        </div>
    @endif

    {{-- Lightbox image (capture gagnante) --}}
    <div x-show="lightboxOpen"
         x-transition.opacity
         @keydown.escape.window="lightboxOpen = false"
         @click.self="lightboxOpen = false"
         class="lightbox-overlay"
         style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.92); z-index:9999; padding:2rem;">
        <button @click="lightboxOpen = false"
                aria-label="Fermer"
                style="position:absolute; top:1.25rem; right:1.25rem; width:40px; height:40px; background:var(--c-bg2); border:1px solid var(--c-border); color:var(--c-text); font-size:1.1rem; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                onmouseover="this.style.borderColor='var(--c-green)'"
                onmouseout="this.style.borderColor='var(--c-border)'">✕</button>
        <img :src="lightboxSrc" alt="Capture gagnante"
             style="max-width:100%; max-height:100%; object-fit:contain; border:1px solid var(--c-border);"
             @click.stop>
    </div>

</div>

<style>
    .lightbox-overlay {
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

@endsection