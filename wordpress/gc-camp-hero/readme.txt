=== Camp Hero ===
Requires at least: 6.0
Requires PHP: 7.4
Stable tag: 1.0.0
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

= 1.0.0 =
* First release.
