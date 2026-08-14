@props(['statut'])

@php
    $styles = [
        'non_paye' => 'bg-retard-500/10 text-retard-600 ring-retard-500/25',
        'acompte_verse' => 'bg-approche-500/10 text-approche-600 ring-approche-500/25',
        'solde' => 'bg-livree-500/10 text-livree-600 ring-livree-500/25',
    ][$statut] ?? 'bg-stade-950/5 text-stade-600 ring-stade-950/10';

    $label = \App\Models\Commande::STATUTS_PAIEMENT[$statut] ?? $statut;
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset whitespace-nowrap $styles"]) }}>
    {{ $label }}
</span>
