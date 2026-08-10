<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display text-2xl text-stade-950 tracking-tight">
            Tableau de bord
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-stade-950/5">
                <div class="p-8">
                    <p class="text-stade-950 font-medium">Bienvenue, {{ Auth::user()->name }}.</p>
                    <p class="mt-2 text-sm text-stade-600">
                        Les alertes de livraison et le résumé des commandes par statut s'afficheront ici.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
