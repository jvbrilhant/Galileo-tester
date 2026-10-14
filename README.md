# [NOME DO PROJETO] — Home demo

This is a static demo of the home page of [NOME DO PROJETO], an initiative affiliated with the Galileo Commission™. It is plain HTML, CSS and JavaScript, built to validate the visual concept before the WordPress build. It has no dependencies, no build step and makes no third-party requests.

The visual language follows the current site (galileoinitiative.com):

- the dusk campsite photo;
- Prata for headings and Poppins for UI and body text;
- teal buttons and an amber accent;
- navy bands and rounded section edges.

```
/
├── index.html
├── assets/
│   ├── css/main.css        single stylesheet, commented sections
│   ├── js/main.js          loaded with `defer`, IIFE, no globals
│   ├── svg/logo.svg        placeholder logo
│   ├── fonts/              Prata 400 + Poppins 400/500/600 (woff2, latin, self-hosted)
│   └── img/                hero photo (WebP, landscape + portrait) and the fire crop
└── README.md
```

## How to open the demo

**Double-click `index.html`.** It works from `file://`. Chrome logs a CORS warning for the two font *preloads* on `file://`. The fonts themselves still load through `@font-face`. Served over HTTP (for example `npx http-server .`) the warning goes away.

## Demo mode

`<html data-demo="true">` turns on demo mode. While it is on, internal links (any `href` starting with `/`) show a polite status message (`role="status"`), *"This page will be available in the full site."*, for 3 s instead of navigating.

**To disable it, remove the `data-demo="true"` attribute from `<html>`.** In production, also delete the `.gc-demo-notice` element and `initDemoMode` in `main.js`. Both are marked `DEMO ONLY`.

## The camp

### Coordinates

On screens of 900px and wider, the hero shows the landscape photo at its natural 3:2 ratio, anchored to the bottom. Each place is positioned in **image coordinates**, so it stays on the photo's ground at every width.

```html
<a class="gc-place gc-cabin gc-cabin--library" style="--x:.33;--y:.745;--w:.13;--lift:1.5" …>
```

| Property | Meaning |
|---|---|
| `--x` | Horizontal centre of the place, as a fraction of the image width (0–1). |
| `--y` | Ground-contact line: where the bottom of the art touches the ground (0–1 of the image height). |
| `--w` | Art width as a fraction of the image width. Further back means smaller. |
| `--lift` | Gap between the label and the roof, in units of 0.75 rem (the leader-line length). |
| `--lift-n` | Optional `--lift` override for the compact range (900–1279px). |

Current places, in DOM and keyboard order:

| Place | `--x` | `--y` | `--w` | Notes |
|---|---|---|---|---|
| Kitchen Ideas | .18 | .873 | .1226 | The fire pit in the photo. |
| Library | .33 | .745 | .13 | Back row, in front of the hammock. |
| Study Groups | .735 | .905 | .30 | Front right, where the tent was (largest cabin). |
| About Us | .555 | .75 | .115 | Back row, with the flag. |

Below 900px, the places stack in DOM order. The fire comes first and large, with its label underneath. The three cabins follow as full-width cards, each with its thumbnail on the left and text on the right.

### Swapping in the final cabin artwork

Each cabin's art lives in `.gc-place__art`. The placeholder is an inline SVG. To replace it, swap the `<svg>` for an image and keep the two ground elements:

```html
<span class="gc-place__art" aria-hidden="true">
  <span class="gc-place__shadow"></span><span class="gc-place__light"></span>
  <img src="assets/img/cabins/library.webp" width="…" height="…" alt="">
</span>
```

Asset requirements:

- **Format:** transparent WebP or PNG, about 2× the largest display size. For example, Study Groups is shown at up to about 760px wide at 2560px viewports.
- **Position:** the cabin base touches the bottom edge of the file, with no empty margin below. That edge is the ground-contact line, `--y`.
- **Perspective:** match the photo. The camera is at standing height and the horizon is at about 49% of the image height. Viewed from slightly above, light comes from the dusk sky and the fire, on the left.
- **Hover:** the cabin rises 3px, the contact shadow tightens and the window light brightens on the ground. If you also supply a "lights on" version, it can cross-fade on hover.

After a swap, adjust `--x`, `--y` and `--w` per cabin.

### Backdrop

The backdrop is the hero photo from the current site. The tent was removed digitally: the valley, the bushes, the two tree trunks and the ground were rebuilt from other parts of the same photo. The Study Groups cabin covers that area. The files are:

- `assets/img/camp-dusk-{1280,1920,2528}.webp` — landscape;
- `assets/img/camp-dusk-portrait-{480,760,1010}.webp` — phones: a left crop, softly blurred, because it sits under a dark scrim;
- `assets/img/campfire.webp` — the fire crop, used for the flicker layers and for the phone fire.

### Fire

The fire in the photo is the art. Three screen-blended copies of its crop are masked to the flames and flicker at 1.7, 2.3 and 3.1 s. A static radial glow pulses in opacity only, and six embers rise. On hover or focus the flames brighten.

## WordPress conversion

Each block sits between `<!-- WP: … -->` and `<!-- /WP: … -->` comments. `<body>` uses `home page-template-default`. Menus use `wp_nav_menu` markup (`ul.menu > li.menu-item > a`).

| Section | Template part | Data: ACF fields / taxonomies | Menu location |
|---|---|---|---|
| Skip link + header + SVG sprite | `header.php` | — | `primary` |
| Hero / camp | `template-parts/home/camp.php` | ACF options: title, subtitle, backdrop image. Repeater `camp_places`: label, description, link, art image, `x`, `y`, `w`, `lift`. | — |
| From Idea to Publication | `template-parts/home/pipeline.php` | Optional repeater `pipeline_stages`: title, text, link, icon. | — |
| Latest from the Library | `template-parts/home/latest-papers.php` → `template-parts/cards/paper-card.php` | CPT `paper`: `abstract` (textarea), `doi` (text), `authors` (repeater: `name`, `affiliation`, `orcid`), `publication_date` (date). Taxonomy `thematic_axis`. | — |
| How to Join a Study Group | `template-parts/home/join.php` | Optional repeater `join_steps` + CTA link. | — |
| Our Mission / affiliation | `template-parts/home/about.php` | `mission_text`, `affiliation_text` (needs legal sign-off). | — |
| Footer | `footer.php` | ACF options: tagline, contact e-mail. | `footer` |

**Slugs:** `/kitchen-ideas/`, `/library/`, `/study-groups/`, `/about/`, plus `/privacy-policy/` in the footer.

**Google Scholar tags:** the `citation_*` meta tags belong in the single paper template, not the home page.

**Tokens and `theme.json`:**

| Tokens | Maps to |
|---|---|
| `--color-*` | `settings.color.palette` |
| `--font-*` | `settings.typography` |
| `--space-*` | `settings.spacing.spacingSizes` (8px scale) |

The palette was sampled from the current site. The teal is darkened from `#008397` to `#007a8c` so that white text passes 4.5:1.

## Placeholders to replace

| What | Where |
|---|---|
| Project name `[NOME DO PROJETO]` | `<title>`, meta/OG, JSON-LD, `logo.svg`, logo `alt`, footer |
| Logo and mark | `assets/svg/logo.svg`, `#gc-mark` symbol, inline favicon |
| Cabin artwork (3) | `.gc-place__art` in the camp (see above) |
| Hero eyebrow, title and subtitle | `.gc-hero__intro` |
| Camp descriptions (4) | `.gc-place__desc` |
| Pipeline, join and mission texts | the corresponding sections |
| 3 papers: titles, authors, dates, axes, abstracts, DOIs (`10.xxxx/placeholder.*`), slugs | `.gc-paper-card` (all fictitious) |
| Affiliation wording | `.gc-about__affiliation` — `<!-- LEGAL: affiliation wording pending SMN validation -->` |
| Contact e-mail `hello@example.org` | footer, JSON-LD |
| Meta description, OG texts, `og:image` | `<head>` |

## Deviations from the brief (agreed during the build)

1. **Raster hero backdrop (WebP)** instead of SVG, using the current site's photo. The client requested this.
2. **The LCP element is the photo** (desktop) or the fire crop (phones), not the `<h1>`. This was agreed earlier: a photo backdrop will always be the largest element.
3. **Self-hosted web fonts** (Prata + Poppins, 43 KB in total, `font-display: swap`, two preloads) instead of system fonts, so the demo matches the current site's typography. No third-party requests are made.
4. **No stars.** The new backdrop is a dusk sky, so stars would read as fake. Ambient motion comes from six fireflies (as on the current site), the fire, the embers and the chimney smoke.
5. **Arc layout from 900px.** Below that, including tablet portrait at 768px, the camp uses the vertical card layout, because labels and descriptions on the arc would not stay legible.
6. **`<link rel="canonical" href="/">`** is kept as specified. Lighthouse SEO scores 92 because it expects an absolute URL; the WP SEO plugin will output one.

## Final measurements

**File sizes:**

| File | Size | Budget |
|---|---|---|
| `index.html` (with inline SVG) | 28.7 KB | ≤ 60 KB ✅ |
| `assets/css/main.css` | 29.96 KB | ≤ 30 KB ✅ |
| `assets/js/main.js` | 3.9 KB | ≤ 8 KB ✅ |
| Fonts | 43 KB (4 files) | — |
| Hero photo, one file per viewport | 16–38 KB (phones), 92–282 KB (desktop) | — |

**Requests:** all requests are local. There are none to third parties.

**Lighthouse 13.5** (headless Chromium over `http-server`):

| Profile | Performance | Accessibility | Best Practices | SEO | LCP | FCP | CLS | TBT |
|---|---|---|---|---|---|---|---|---|
| Mobile (slow 4G, 4× CPU) | 99 | 100 | 100 | 92* | 1.8–2.0 s | 1.4 s | 0.002 | 0 ms |
| Desktop | 100 | 100 | 100 | 92* | 0.6 s | 0.4 s | 0.001 | 0 ms |

\* The only failing audit is the relative canonical.

The mobile LCP target is < 1.8 s. It measures 1.8 s on most runs and 2.0 s on some, so it misses the target on some runs. The floor is the render-blocking HTML and CSS plus the brand fonts (FCP is 1.4 s). Removing the font preloads made it worse: LCP 2.0–2.1 s and CLS 0.013.

**Automated checks** (Playwright + Chromium):

- **Overflow:** no horizontal overflow at 20 widths from 320 to 2560px.
- **Camp labels:** no camp label overlaps another label or the hero text at 13 widths from 900 to 2560px.
- **Keyboard order:** skip link → logo → primary menu → CTA → Kitchen Ideas → Library → Study Groups → About Us → content.
- **Camp link names:** each accessible name is exactly the visible label; descriptions use `aria-describedby`.
- **Menu:** `aria-expanded` toggles, Esc closes the menu and returns focus to the button. Without JS the menu is expanded and the header is a solid navy bar.
- **Demo mode:** the notice appears on click and is cleared after 3 s. With `data-demo` removed, links navigate.
- **Pausing:** hero animations pause when the hero is out of view or the tab is hidden.
- **Reduced motion:** no animations; hover still lights the windows.
- **Contrast (WCAG 2.2 AA):**
  - Hero text was measured on the rendered pixels (worst pixel behind each text box, at 390, 1024, 1440 and 1920px). The minimum is 6.9:1.
  - 27 token pairs in the light and dark sections all pass. The lowest is teal link text on `#f4f7f9`, at 4.69:1.
