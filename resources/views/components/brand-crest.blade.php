{{-- Decorative club-crest mark: double ring, arced wordmark, center star. --}}
<svg viewBox="0 0 200 200" {{ $attributes }} aria-hidden="true">
    <defs>
        <path id="crestArcTop" d="M 24,108 A 84,84 0 1 1 176,108" fill="none" />
    </defs>

    <circle cx="100" cy="100" r="94" fill="none" stroke="currentColor" stroke-width="1" />
    <circle cx="100" cy="100" r="80" fill="none" stroke="currentColor" stroke-width="1" />

    <text font-family="var(--font-display)" font-size="13" letter-spacing="4" fill="currentColor">
        <textPath href="#crestArcTop" startOffset="50%" text-anchor="middle">OR SPORT · ABIDJAN</textPath>
    </text>

    <path
        d="M100 58 L109.5 82.5 L136 84.5 L115.5 101.5 L122 127.5 L100 113 L78 127.5 L84.5 101.5 L64 84.5 L90.5 82.5 Z"
        fill="currentColor"
    />
</svg>
