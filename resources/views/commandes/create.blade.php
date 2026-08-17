<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display text-2xl text-stade-950 tracking-tight">
            Nouvelle commande
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('commandes.store') }}">
                @csrf
                @include('commandes._form', [
                    'commande' => null,
                    'clients' => $clients,
                    'referenceValue' => $referenceProchaine,
                    'referenceHelp' => "Générée automatiquement à l'enregistrement.",
                    'submitLabel' => 'Créer la commande',
                    'cancelUrl' => route('commandes.index'),
                    'showArticles' => true,
                ])
            </form>
        </div>
    </div>
</x-app-layout>
