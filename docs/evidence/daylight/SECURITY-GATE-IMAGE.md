# Approved security-gate hero update

The owner selected the edited architectural image with the foreground woman removed and a stone/bronze perimeter security gate added at the left of the fountain. The approved edit is the direct source of `public/assets/corporate/hero-security-*`; no further generated changes were made.

The home picture and matching responsive preload now use versioned asset URLs, avoiding reuse of the previous cached photograph. AVIF, WebP and progressive JPEG at widths 640, 960 and 1536 are provided; mobile media candidates remain capped at 960px. The original photograph is retained for rollback. The original corporate seal remains unchanged.

This updates the real Laravel homepage, not a flat image of the website. Existing conceptual-architecture labeling, live translated text, links and controls are retained.

Source PNG SHA-256: `8516a9b55d30e9959d07b008f806ba8649e8e961616c082c9e14fec2b8988476`.

Verification for this asset update: Vite production build and TypeScript passed. The actual Laravel preview was opened in Chrome, the new AVIF loaded successfully, and the removed woman / added security boundary were visually verified. `after-desktop-en.jpg` was refreshed from that preview. New AVIF sizes: 640px 75,647 bytes; 960px 146,768 bytes; 1536px 280,706 bytes.

The repository is now public. GitHub run 40889394419 exposed the prior pre-start failure annotation: "The job was not started because recent account payments have failed or your spending limit needs to be increased." No financial setting or limit was changed. Repository visibility does not grant a cPanel deployment session or production backup access.
