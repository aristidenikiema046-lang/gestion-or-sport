<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display text-2xl text-stade-950 tracking-tight">
            Mes livraisons
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-stade-950/5">
                <div class="p-8">
                    <p class="text-stade-950 font-medium">Bonjour, {{ Auth::user()->name }}.</p>
                    <p class="mt-2 text-sm text-stade-600">
                        La liste des commandes à livrer et le bouton « Livré » s'afficheront ici.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
