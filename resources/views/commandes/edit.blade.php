<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display text-2xl text-stade-950 tracking-tight">
            Modifier la commande {{ $commande->reference }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('commandes.update', $commande) }}">
                @csrf
                @method('PATCH')
                @include('commandes._form', [
                    'commande' => $commande,
                    'clients' => $clients,
                    'referenceValue' => $commande->reference,
                    'referenceHelp' => 'La référence ne peut pas être modifiée.',
                    'submitLabel' => 'Enregistrer les modifications',
                    'cancelUrl' => route('commandes.show', $commande),
                ])
            </form>
        </div>
    </div>
</x-app-layout>
