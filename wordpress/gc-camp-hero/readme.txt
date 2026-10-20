=== Camp Hero ===
Requires at least: 6.0
Requires PHP: 7.4
Stable tag: 1.3.2
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
