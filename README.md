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
│   └── img/                hero backdrop, tents (+ glow, thumbs) and campfire (WebP)
└── README.md
```

## How to open the demo

**Double-click `index.html`.** It works from `file://`. Chrome logs a CORS warning for the two font *preloads* on `file://`. The fonts themselves still load through `@font-face`. Served over HTTP (for example `npx http-server .`) the warning goes away.

## Demo mode

`<html data-demo="true">` turns on demo mode. While it is on, internal links (any `href` starting with `/`) show a polite status message (`role="status"`), *"This page will be available in the full site."*, for 3 s instead of navigating.

**To disable it, remove the `data-demo="true"` attribute from `<html>`.** In production, also delete the `.gc-demo-notice` element and `initDemoMode` in `main.js`. Both are marked `DEMO ONLY`.

## The camp

### Assets

| File | What it is |
|---|---|
| `assets/img/camp-bg-{1280,1671}.webp` | The client's dusk photo with its original tents and fire removed (AI inpainting, LaMa) |
| `assets/img/camp-bg-portrait-{400,530}.webp` | Phones: a crop around the lake, softly blurred (sits under a dark scrim) |
| `assets/img/tent-{library,study,about}.webp` | Tents cut out of the client's render, colour-graded to the dusk scene (cool ambient, warm key from the fire side, interior light on) |
| `assets/img/tent-*-glow.webp` | Extra interior light only (transparent), shown on hover/focus with `mix-blend-mode: screen` |
| `assets/img/tent-*-thumb.webp` | 240px versions for the phone cards |
| `assets/img/campfire.webp` | The client's fire pit render, cut out and graded (warm light on the logs and stones) |

The three tents are one model, recoloured to match the reference (olive green for Library, grey for Study Groups, orange for About Us). The side icons were redrawn: books for Library, the original group icon for Study Groups, and the project mark for About Us. The mark replaces the lightbulb in the reference, as agreed.

### Coordinates

On screens 900px and wider, the hero shows the landscape photo at its natural ratio (1671×941), anchored to the bottom. Each place is positioned in **image coordinates**, so it stays on the photo's ground at every width:

```html
<a class="gc-place gc-cabin gc-cabin--library" style="--x:.19;--y:.695;--w:.28;--lift:1.5;--sx:-.12" …>
```

| Property | Meaning |
|---|---|
| `--x` | Horizontal centre of the place, as a fraction of the image width (0–1) |
| `--y` | Ground-contact line: where the bottom of the art touches the ground (0–1 of the image height) |
| `--w` | Art width as a fraction of the image width. Further back means smaller |
| `--lift` | Gap between the label and the art, in units of 0.75rem (the length of the leader line) |
| `--sx` | Horizontal offset of the cast shadow. Negative values push it left, away from the fire |

| Place | `--x` | `--y` | `--w` |
|---|---|---|---|
| Kitchen Ideas (fire) | .65 | .775 | .165 |
| Library | .19 | .695 | .28 |
| Study Groups | .456 | .645 | .215 |
| About Us | .84 | .672 | .24 |

Below 900px, the places stack in DOM order. The fire comes first, followed by three full-width cards, each with a tent thumbnail on the left.

### Hover and focus

- **Tents:** the tent rises 3px, the extra interior light fades in, the contact shadow tightens and the light spilling onto the ground brightens. The label turns amber and gets an underline.
- **Fire:** the flame layers brighten.

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
| Tent and fire artwork | `assets/img/tent-*.webp`, `campfire.webp` (see "The camp") |
| Hero eyebrow, title and subtitle | `.gc-hero__intro` |
| Camp descriptions (4) | `.gc-place__desc` |
| Hero backdrop | `assets/img/camp-bg-*.webp` |
| Pipeline, join and mission texts | the corresponding sections |
| 3 papers: titles, authors, dates, axes, abstracts, DOIs (`10.xxxx/placeholder.*`), slugs | `.gc-paper-card` (all fictitious) |
| Affiliation wording | `.gc-about__affiliation` — `<!-- LEGAL: affiliation wording pending SMN validation -->` |
| Contact e-mail `hello@example.org` | footer, JSON-LD |
| Meta description, OG texts, `og:image` | `<head>` |

## Deviations from the brief (agreed during the build)

1. **Raster hero art (WebP).** The client asked for the backdrop, tents and fire as images instead of SVG.
2. **The LCP element is the photo on desktop and the hero subtitle on phones**, not the `<h1>`. A photo backdrop is always the largest element on desktop.
3. **Self-hosted web fonts** (Prata + Poppins, 43 KB in total, `font-display: swap`, two preloads) instead of system fonts, so the demo matches the current site's typography. No third-party requests are made.
4. **No stars.** The sky is a dusk sky, so stars would read as fake. Ambient motion comes from six fireflies, the flickering fire and its embers.
5. **Arc layout from 900px.** Below that, including tablet portrait at 768px, the camp uses the vertical card layout, because labels and descriptions on the arc would not stay legible.
6. **`<link rel="canonical" href="/">`** is kept as specified. Lighthouse SEO scores 92 because it expects an absolute URL; the WP SEO plugin will output one.

## Final measurements

**File sizes**

| File | Size | Budget |
|---|---|---|
| `index.html` | 23.3 KB | ≤ 60 KB ✅ |
| `assets/css/main.css` | 28.6 KB | ≤ 30 KB ✅ |
| `assets/js/main.js` | 3.9 KB | ≤ 8 KB ✅ |
| Fonts | 43 KB (4 files) | — |

**Hero images, per viewport**

| Viewport | What loads | Total |
|---|---|---|
| Phone | 15 KB backdrop + 24 KB fire + 3 × 6 KB tent thumbnails | 57 KB |
| Desktop | 61–90 KB backdrop + 24 KB fire + 3 × 30 KB tents + 3 × 10 KB glows | ≈ 235 KB |

All requests are local; there are no third-party requests.

**Lighthouse 13.5** (headless Chromium over `http-server`)

| Profile | Performance | Accessibility | Best Practices | SEO | LCP | FCP | CLS | TBT |
|---|---|---|---|---|---|---|---|---|
| Mobile, no-cache server | 97–98 | 100 | 100 | 92* | 2.3–2.5 s | 1.2 s | 0 | 0 ms |
| Mobile, cache headers | 99 | 100 | 100 | 92* | 2.1 s | 1.2 s | 0 | 0 ms |
| Desktop | 100 | 100 | 100 | 92* | 0.7 s | 0.3 s | 0.001 | 0 ms |

\* The only failing audit is the relative canonical.

**Mobile LCP misses the < 1.8 s target.** On phones the LCP element is the hero subtitle, painted at about 120 ms in the real (unthrottled) run. Lighthouse's slow-4G simulation then models the whole critical path: HTML, CSS, fonts, fire and backdrop. Things already tried:

- removing the preload of the fire image;
- serving small tent thumbnails on phones;
- skipping the glow layers on phones;
- setting Poppins to `font-display: optional`.

Each brought it down from 2.8 s. Getting under 1.8 s would require dropping the self-hosted brand fonts on phones or inlining the critical CSS. That is a decision for the WordPress build: a cache plugin and HTTP/2 push/preload typically handle it.

**Automated checks** (Playwright + Chromium)

- **No horizontal overflow** at 20 widths from 320 to 2560px.
- **No overlaps:** no camp label overlaps another label or the hero text, at 13 widths from 900 to 2560px.
- **Keyboard order:** skip link → logo → primary menu → CTA → Kitchen Ideas → Library → Study Groups → About Us → content. Each link's accessible name is exactly its visible label.
- **Menu:** `aria-expanded` is set correctly, and Esc closes the menu and returns focus. Without JS, the menu is expanded.
- **Demo mode:** the notice appears and clears after 3 s. With `data-demo` removed, the links navigate.
- **Paused animations:** the hero animations pause when the hero is off-screen and when the tab is hidden.
- **Reduced motion:** no animations run, and hover still lights the tents.
- **Contrast (WCAG 2.2 AA):**
  - Hero text is measured on the rendered pixels at 390, 1024, 1440 and 1920px. The minimum is 8.9:1.
  - Text in the light and dark sections passes; the lowest pair is 4.69:1.
