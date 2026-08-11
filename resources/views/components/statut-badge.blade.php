@props(['statut'])

@php
    $styles = [
        'en_attente' => 'bg-stade-950/5 text-stade-700 ring-stade-950/10',
        'en_preparation' => 'bg-or-500/10 text-or-700 ring-or-500/20',
        'prete' => 'bg-pret-500/10 text-pret-600 ring-pret-500/25',
        'livree' => 'bg-livree-500/10 text-livree-600 ring-livree-500/25',
        'annulee' => 'bg-stade-950/5 text-stade-600/70 ring-stade-950/10 line-through decoration-stade-600/40',
    ][$statut] ?? 'bg-stade-950/5 text-stade-600 ring-stade-950/10';

    $label = \App\Models\Commande::STATUTS[$statut] ?? $statut;
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset whitespace-nowrap $styles"]) }}>
    {{ $label }}
</span>
