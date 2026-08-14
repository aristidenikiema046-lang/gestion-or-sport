{{--
    Marque OR SPORT — monogramme "R", logo officiel du client.

    Emplacement : nav (haut gauche), filigrane, et tout usage compact où
    l'icône seule suffit. Le dimensionnement se fait par les classes w-*/h-*
    de l'appelant.
--}}
<img src="{{ asset('icons/logo-icon-tight-crop.png') }}" alt="" aria-hidden="true" {{ $attributes->merge(['class' => 'object-contain']) }} />
