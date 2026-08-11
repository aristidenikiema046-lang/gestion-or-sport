{{--
    Marque OR SPORT — monogramme circulaire "R" (anneau ouvert + lettre stylisée),
    version simplifiée inspirée du logo officiel du client.

    Emplacement unique de la marque : nav (haut gauche), écran de connexion,
    filigrane. Le dimensionnement se fait par les classes w-*/h-* de l'appelant,
    ce composant remplit son conteneur — donc quand le client fournira le vrai
    fichier, il suffit de remplacer le <svg> ci-dessous par :

        <img src="{{ asset('images/logo.svg') }}" alt="OR SPORT" class="w-full h-full object-contain" />

    pour que le nouveau logo s'applique partout sans toucher au reste des vues.
--}}
<svg viewBox="0 0 100 100" {{ $attributes }} aria-hidden="true">
    <path d="M 74.1,84.4 A 42 42 0 1 1 91.8,53.7" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" />
    <g fill="none" stroke="currentColor" stroke-width="8" stroke-linecap="round" stroke-linejoin="round">
        <path d="M 32,17 Q 22,19 28,28" />
        <path d="M 38,26 L 38,80" />
        <path d="M 38,26 C 58,26 64,34 64,43 C 64,52 56,55 38,55" />
        <path d="M 40,56 L 84,84" />
    </g>
</svg>
