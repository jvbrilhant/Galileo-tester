=== Camp Hero ===
Requires at least: 6.0
Requires PHP: 7.4
Stable tag: 1.6.2
License: GPLv2 or later

The camp hero section (fire + three tents) as an Elementor widget and a shortcode.

== Description ==

* Elementor: drag the "Camp Hero" widget into a full-width container with no padding.
* Shortcode: [gc_camp_hero header_height="80" header_overlay="yes"]

All images, fonts (Prata, Poppins), CSS and JS ship with the plugin. CSS/JS load only on
pages that use the hero. Everything is scoped under .gc-hero.

Tent and fire positions are fractions of the backdrop photo and live in includes/render.php
($tents and the fire's inline style): --x centre, --y bottom of the art, --w width.

== Changelog ==

= 1.6.2 =
* Fix: image URLs now carry the plugin version (?ver=), so updated images (e.g. the 1.6.1 tent icons) replace copies cached by browsers, page caches and CDNs. Before, a cached old icon-light layer could show over the new icon.

= 1.6.1 =
* Library and About Us tent icons redrawn in the perspective of the tent panel (like the Study Groups icon), at rest and in the hover light layer.

= 1.6.0 =
* Hover: the icon painted on each tent lights up softly (warm layer traced from the icon). Style → Barracas → Brilho do ícone no hover (0–1, default 0.8). Not loaded on phones.

= 1.5.0 =
* Entrance animation for the texts (Conteúdo → Animação de entrada dos textos): fade, up, down, blur, zoom or title word by word; optional plaques; duration, delay and stagger. Off with reduced motion. Shortcode: reveal, reveal_buttons.

= 1.4.1 =
* Fix: an emptied description is no longer replaced by the default text (it is hidden, along with its aria-describedby). An emptied name or link still falls back to the default.
* Fix: clearing the firefly Quantidade slider no longer turns the fireflies off (it falls back to 6).
* Fix: Botões → Espaçamento interno no longer pads the text inside the phone cards.
* Hero height: empty by default = the full screen (100svh on phones, so nothing starts under the browser bars).
* Animation pause: uses the latest IntersectionObserver record.

= 1.4.0 =
* Hero height: responsive control (Texto inicial → Altura do hero, vh or px, per device), default 100vh; shortcode attributes height and height_mobile. Desktop keeps a minimum so the tents never climb into the title; on very tall heroes the photo stays bottom-anchored and fades into the sky colour. On phones the value is a minimum height.

= 1.3.3 =
* Fix: with the cursor resting just below an open plaque, the plaque could flicker open and closed. It now keeps its open width until the description has collapsed.

= 1.3.2 =
* Hover: the plaque no longer animates its width (the text was re-wrapping while it appeared). It takes its open width at once, grows downward, and the description fades in.

= 1.3.1 =
* Hover: the description now opens inside the plaque, under the name (the plaque widens a little). Removed the separate description-background opacity control.
* Fire moved slightly up.

= 1.3.0 =
* Fireflies: on/off, count (1-60) and speed.

= 1.2.0 =
* Style tab → Botões: background colour (rest + hover), background opacity, description opacity, border style/width/colour (rest + hover), radius and padding.

= 1.1.0 =
* Style tab: typography and colour for the eyebrow, title, place names and descriptions.

= 1.0.0 =
* First release.
