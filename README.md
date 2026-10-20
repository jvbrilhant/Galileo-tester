# [NOME DO PROJETO] — Home demo

This is a static demo of the home page of [NOME DO PROJETO], an initiative affiliated with the Galileo Commission™. It is plain HTML, CSS and JavaScript, built to validate the visual concept before the WordPress build. It has no dependencies, no build step and makes no third-party requests.

The visual language follows the current site (galileoinitiative.com):

- a dusk campsite scene (the client's camp art);
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
│   └── img/                hero backdrop, tents (+ hover glow) and campfire, in several widths (WebP)
└── README.md
```

## How to open the demo

**Double-click `index.html`.** It works from `file://`. Chrome logs a CORS warning for the two font *preloads* on `file://`. The fonts themselves still load through `@font-face`. Served over HTTP (for example `npx http-server .`) the warning goes away.

## Demo mode

`<html data-demo="true">` turns on demo mode. While it is on, internal links (any `href` starting with `/`) show a polite status message (`role="status"`), *"This page will be available in the full site."*, for 3 s instead of navigating.

**To disable it, remove the `data-demo="true"` attribute from `<html>`.** In production, also delete the `.gc-demo-notice` element and `initDemoMode` in `main.js`. Both are marked `DEMO ONLY`.

## The camp

### Assets

All images are WebP, served through `srcset`, so retina screens get sharp versions.

| File | What it is |
|---|---|
| `assets/img/camp-bg-{1280,1920,2560,3342}.webp` | The client's dusk photo. Its original tents and fire were removed (LaMa inpainting), then it was upscaled 2× (EDSR super-resolution) |
| `assets/img/camp-bg-portrait-{540,1060}.webp` | Phone version: a crop around the lake and fire, softly blurred because it sits under a scrim |
| `assets/img/tent-{library,study,about}-{260,520,900,1400}.webp` | The client's three tent PNGs, with their props: suitcase, books and lantern (Library); backpack (Study Groups); chair and table (About Us) |
| `assets/img/tent-*-glow.webp` | Extra interior light, shown on hover and focus (`mix-blend-mode: screen`) |
| `assets/img/campfire-{420,800}.webp` | The client's fire pit PNG, graded: dusk ambient, firelight on logs and stones, ember glow |
| `assets/img/campfire-flame.webp` | The flames only. Three copies of it flicker at 1.7, 2.3 and 3.1 s |

How the tents were built:

- **Fabric colour:** each tent keeps its own colour: blue for Library, beige for Study Groups and orange for About Us.
- **Side icons:** the client's bookshelf icon for Library, the original group icon for Study Groups, and the project mark for About Us (in place of the lightbulb).
- **Lighting:** graded to the dusk scene, with cool ambient light and a warm key from the fire side. The interior is lamp-lit through the door, and the lanterns keep their flames.
- **Orientation:** About Us is mirrored, so its door faces left towards the fire. Mirroring keeps full resolution.

### Framing and coordinates

**Screens 900px and wider.** The hero fills the viewport, clamped between 560px and 61vw, and at least 50vw tall on ultra-wide screens. The photo is drawn with `object-fit: cover`, anchored to the bottom.

The hero is a size container (`container-type: size`). It exposes the photo's rendered size:

- `--img-w: max(100cqw, 100cqh × 1.7758)`
- `--img-h`

Each place is pinned to the same spot of the photo with values in **image fractions**, so the tents stay on the ground at any width or height:

```html
<a class="gc-place gc-cabin gc-cabin--library" style="--x:.189;--y:.73;--w:.32;--ar:.5615;--sx:-.1" …>
```

| Property | Meaning |
|---|---|
| `--x` | Horizontal centre of the element (0–1 of the photo width) |
| `--y` | Bottom of the art, i.e. the stake tips or the base of the stones (0–1 of the photo height) |
| `--w` | Width of the art (0–1 of the photo width) |
| `--ar` | Art height ÷ width: 0.558 for the tents, 0.7122 for the fire |
| `--sx` | Offset of the cast shadow. Negative values push it left, away from the fire |

The ground of the photo was mapped and every element stands on the dirt clearing, which spans about x .20–.90 and y .60–.85. The back corners of each tent footprint stay on dirt, not on the bushes or the slope. The scale is realistic: the fire ring is less than half a tent's width.

| Place | `--x` | `--y` | `--w` |
|---|---|---|---|
| Kitchen Ideas (fire) | .60 | .85 | .104 |
| Library | .235 | .815 | .25 |
| Study Groups | .445 | .735 | .19 |
| About Us | .80 | .80 | .235 |

**Labels.** A label sits on the ground under each element and shows only the category name, in Poppins (sans-serif) with generous vertical padding. On hover or keyboard focus, the description slides out below it, animated with opacity and transform only. It is always exposed to screen readers through `aria-describedby`. Phones keep the description visible in the cards, because they have no hover. From 900 to 1199px the labels are compact and the eyebrow line is hidden.

**Below 900px.** The fire comes first, followed by three full-width cards, each with a tent on the left.

### Hover and focus

- **Tents:** the tent rises 3px, the extra interior light fades in, the cast shadow tightens and the door light on the ground brightens. The label name and border turn amber, and the description appears.
- **Fire:** the flames brighten, and the description appears.

## WordPress conversion

Each block sits between `<!-- WP: … -->` and `<!-- /WP: … -->` comments. `<body>` uses `home page-template-default`. Menus use `wp_nav_menu` markup (`ul.menu > li.menu-item > a`).

| Section | Template part | Data: ACF fields / taxonomies | Menu location |
|---|---|---|---|
| Skip link + header + SVG sprite | `header.php` | — | `primary` |
| Hero / camp | `template-parts/home/camp.php` | ACF options: eyebrow, title, backdrop image. Repeater `camp_places`: label, description, link, art image, `x`, `y`, `w`, `lift`. | — |
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
| Tent and fire artwork | `assets/img/tent-*.webp`, `campfire.webp` (see "The camp") |
| Hero eyebrow and title | `.gc-hero__intro` |
| Camp descriptions (4) | `.gc-place__desc` |
| Hero backdrop | `assets/img/camp-bg-*.webp` |
| Pipeline, join and mission texts | the corresponding sections |
| 3 papers: titles, authors, dates, axes, abstracts, DOIs (`10.xxxx/placeholder.*`), slugs | `.gc-paper-card` (all fictitious) |
| Affiliation wording | `.gc-about__affiliation` — `<!-- LEGAL: affiliation wording pending SMN validation -->` |
| Contact e-mail `hello@example.org` | footer, JSON-LD |
| Meta description, OG texts, `og:image` | `<head>` |

## Deviations from the brief (agreed during the build)

1. **Raster hero art (WebP).** The client asked for the backdrop, tents and fire as images instead of SVG.
2. **On desktop the LCP element is the photo, not the `<h1>`**, because a photo backdrop is always the largest element. On phones the LCP element is the `<h1>`, as the brief asked.
3. **Self-hosted web fonts** (Prata + Poppins, 43 KB in total, `font-display: swap`, two preloads) instead of system fonts, so the demo matches the current site's typography. No third-party requests are made.
4. **No stars.** The sky is a dusk sky, so stars would read as fake. Ambient motion comes from six fireflies, the flickering fire and its embers.
5. **Arc layout from 900px.** Below that, including tablet portrait at 768px, the camp uses the vertical card layout, because labels and descriptions on the arc would not stay legible.
6. **`<link rel="canonical" href="/">`** is kept as specified. Lighthouse SEO scores 92 because it expects an absolute URL; the WP SEO plugin will output one.

## Final measurements

**File sizes**

| File | Size | Budget |
|---|---|---|
| `index.html` | 23.7 KB | ≤ 60 KB ✅ |
| `assets/css/main.css` | 29.0 KB | ≤ 30 KB ✅ |
| `assets/js/main.js` | 3.9 KB | ≤ 8 KB ✅ |
| Fonts | 43 KB (4 files) | — |

**Hero images per viewport**

| Viewport | What loads | Total |
|---|---|---|
| Phone | 16–37 KB backdrop + 23 KB fire + 3 × 7–18 KB tents | ≈ 60–110 KB |
| Desktop 1440px @1x | 98 KB backdrop + 23 KB fire + 3 × 18 KB tents + 3 × 21 KB glows | ≈ 240 KB |

All requests are local. There are no third-party requests.

**Lighthouse 13.5** (headless Chromium over `http-server`)

| Profile | Performance | Accessibility | Best Practices | SEO | LCP | FCP | CLS | TBT |
|---|---|---|---|---|---|---|---|---|
| Mobile (slow 4G, 4× CPU) | 97–98 | 100 | 100 | 92* | 2.4 s | 1.2 s | 0.002 | 0 ms |
| Desktop | 100 | 100 | 100 | 92* | 0.7 s | 0.4 s | 0.001 | 0 ms |

\* The only failing audit is the relative canonical.

**Mobile LCP misses the < 1.8 s target.** On phones the LCP element is the `<h1>`, painted at about 120 ms in the real (unthrottled) run. Lighthouse's slow-4G simulation then models the whole critical path: HTML, CSS, fonts, fire and backdrop. Things already tried:

- removing the preload of the fire image;
- serving small tent images on phones (through `srcset`);
- skipping the glow layers on phones;
- setting Poppins to `font-display: optional`.

Each brought it down from 2.8 s. Getting under 1.8 s would require dropping the self-hosted brand fonts on phones or inlining the critical CSS. That is a decision for the WordPress build: a cache plugin and HTTP/2 push/preload typically handle it.

**Automated checks** (Playwright + Chromium)

- **No horizontal overflow** at 20 widths from 320 to 2560px.
- **Framing:** checked at 12 desktop viewports from 900×700 to 2560×1440, including 2560×1080. In every one, the whole camp is visible, no element is cut off, and no label overlaps another label, the fire or the hero text.
- **Keyboard order:** skip link → logo → primary menu → CTA → Kitchen Ideas → Library → Study Groups → About Us → content. Each link's accessible name is exactly its visible label.
- **Menu:** `aria-expanded` is set correctly, and Esc closes the menu and returns focus. Without JS, the menu is expanded.
- **Demo mode:** the notice appears and clears after 3 s. With `data-demo` removed, the links navigate.
- **Paused animations:** the hero animations pause when the hero is off-screen and when the tab is hidden.
- **Reduced motion:** no animations run, and hover still lights the tents.
- **Contrast (WCAG 2.2 AA):**
  - Hero text is measured on the rendered pixels at 390, 1024, 1440 and 1920px. The minimum is 8.4:1. The open description panels measure at least 12:1.
  - Text in the light and dark sections passes; the lowest pair is 4.69:1.
