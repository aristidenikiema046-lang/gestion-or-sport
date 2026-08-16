@props(['commande', 'prefix' => 'defaut'])
@php $modalId = $prefix.'-suppression-definitive-titre-'.$commande->id; @endphp

{{-- Modale de confirmation pour une suppression irréversible : plus insistante
     que celle de la mise à la corbeille (icône d'alerte, texte explicite sur
     l'irréversibilité, bouton dans une teinte plus foncée). --}}
<div
    x-show="confirmationSuppressionDefinitive"
    x-on:keydown.escape.window="confirmationSuppressionDefinitive = false"
    class="fixed inset-0 z-50 flex items-center justify-center px-4"
    style="display: none;"
    role="dialog"
    aria-modal="true"
    aria-labelledby="{{ $modalId }}"
>
    <div
        x-show="confirmationSuppressionDefinitive"
        x-on:click="confirmationSuppressionDefinitive = false"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-stade-950/60 backdrop-blur-sm"
    ></div>

    <div
        x-show="confirmationSuppressionDefinitive"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-2"
        class="relative w-full sm:max-w-sm bg-white rounded-2xl shadow-2xl p-6"
    >
        <div class="flex items-center gap-3">
            <div class="shrink-0 w-10 h-10 rounded-full bg-retard-500/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-retard-600" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />
                </svg>
            </div>
            <h3 id="{{ $modalId }}" class="font-display text-lg tracking-tight text-stade-950">
                Suppression définitive
            </h3>
        </div>
        <p class="mt-3 text-sm text-stade-600">
            Supprimer définitivement la commande <span class="font-semibold text-stade-950">{{ $commande->reference }}</span> ? Cette action est irréversible : toutes les données seront perdues, sans possibilité de restauration.
        </p>
        <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
            <button type="button" x-on:click="confirmationSuppressionDefinitive = false" class="inline-flex items-center justify-center rounded-lg border border-stade-700/20 bg-white px-5 py-2.5 text-sm font-semibold text-stade-700 hover:bg-stade-950/5 transition">
                Annuler
            </button>
            <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-retard-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-retard-600/90 focus:outline-none focus:ring-2 focus:ring-retard-600/50 focus:ring-offset-2 transition">
                Supprimer définitivement
            </button>
        </div>
    </div>
</div>
