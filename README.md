# [NOME DO PROJETO] — Home demo

A static demo of the home page of [NOME DO PROJETO], an initiative affiliated with the Galileo Commission™. It is plain HTML, CSS and JavaScript, built to validate the visual concept before the WordPress build. It has no dependencies, no build step and makes no third-party requests.

```
/
├── index.html
├── assets/
│   ├── css/main.css        single stylesheet, commented sections
│   ├── js/main.js          loaded with `defer`, IIFE, no globals
│   ├── svg/logo.svg        placeholder logo
│   └── img/                hero backdrop (WebP, placeholder art) — see "Deviations"
└── README.md
```

## How to open the demo

Double-click `index.html`. It works from `file://` with no server. `main.js` is a classic script, not an ES module, because browsers block modules on `file://`.

To test it the way it will be served, you can also run any static server from the project root, for example `npx http-server .`.

## Demo mode

`<html data-demo="true">` turns on demo mode. While it is on, clicks on internal links (any `href` starting with `/`) are intercepted. A polite status message (`role="status"`) says *"This page will be available in the full site."* and disappears after 3 s.

**To disable it, remove the `data-demo="true"` attribute from `<html>`.** Nothing else needs to change. In production, also delete the `.gc-demo-notice` element at the end of `<body>` and the `initDemoMode` function in `main.js`. Both are marked `DEMO ONLY`.

## WordPress conversion

Each block in `index.html` sits between `<!-- WP: … -->` and `<!-- /WP: … -->` comments. `<body>` uses `home page-template-default`. Menus use the markup that `wp_nav_menu` generates (`ul.menu > li.menu-item > a`).

| Section (in order) | Template part | Data: ACF fields / taxonomies | Menu location |
|---|---|---|---|
| Skip link + header + SVG sprite | `header.php` | Sprite with `<symbol>`s and gradients, inlined once | `primary` |
| Hero / camp | `template-parts/home/camp.php` | Optional ACF options: hero title, subtitle, one label + description per place, backdrop image (WebP, art-directed portrait/landscape) | — (the four hrefs are fixed slugs) |
| From Idea to Publication | `template-parts/home/pipeline.php` | Optional ACF repeater `pipeline_steps` (title, text, link, icon) | — |
| Latest from the Library | `template-parts/home/latest-papers.php` → `template-parts/cards/paper-card.php` | CPT `paper`: `abstract` (textarea), `doi` (text), `authors` (repeater: `name`, `affiliation`, `orcid`), `publication_date` (date); taxonomy `thematic_axis` | — |
| How to Join a Study Group | `template-parts/home/join.php` | Optional ACF repeater `join_steps` + CTA link | — |
| Our Mission / affiliation | `template-parts/home/about.php` | ACF `mission_text`, `affiliation_text` (needs legal sign-off) | — |
| Footer | `footer.php` | ACF options: tagline, contact e-mail | `footer` |

Other handoff notes:

- **Slugs** (with trailing slash): `/kitchen-ideas/`, `/library/`, `/study-groups/`, `/about/`, plus `/privacy-policy/` in the footer.
- **Google Scholar tags** (`citation_*`) belong in the single paper template, not the home page. The home page has a comment saying so.
- **Tokens:**
  - `--color-*` maps to `theme.json` → `settings.color.palette`.
  - `--font-*` maps to `settings.typography`.
  - `--space-*` maps to `settings.spacing.spacingSizes`. It is an 8px scale.
- **Fonts:** the system serif/sans stacks are used. A commented `@font-face` (self-hosted woff2, `font-display: swap`) sits in *Tokens* in `main.css`, and the matching `preload` is commented in `<head>`.
- **SEO:**
  - The `<title>`, meta description, Open Graph and canonical will come from the SEO plugin.
  - `og:image` is pending.
  - The JSON-LD `Organization` has placeholders. `parentOrganization` / `memberOf` is commented as pending legal validation.

## Placeholders to replace

| What | Where |
|---|---|
| Project name `[NOME DO PROJETO]` | `<title>`, meta/OG, JSON-LD, `logo.svg`, logo `alt`, footer copyright |
| Logo and project mark (ring + horizon + star) | `assets/svg/logo.svg`, `#gc-mark` symbol in the sprite, inline favicon |
| Hero title and subtitle | `.gc-hero__intro` |
| Camp descriptions (4 one-liners) | `.gc-camp__desc` |
| Hero backdrop art | `assets/img/camp-night-*.webp` (generated placeholder painting) |
| Pipeline, join-step and mission texts | the corresponding sections |
| 3 papers: titles, authors, dates, axes, abstracts, DOIs (`10.xxxx/placeholder.*`), slugs | `.gc-paper-card` (all fictitious) |
| Affiliation wording | `.gc-about__affiliation` — `<!-- LEGAL: affiliation wording pending SMN validation -->` |
| Contact e-mail `hello@example.org` | footer, JSON-LD |
| Meta description, OG texts, `og:image` | `<head>` |

**Replacing the hero backdrop.** Keep the same file names and aspect ratios:

- landscape 16:9: 1280, 1920 and 2560 px wide;
- portrait 1:2: 540 and 1080 px wide.

Keep the horizon at about 56% (landscape) or 40% (portrait) of the height, with the clearing below it. The fire and cabins are drawn on top in SVG. The image is cropped with `object-fit: cover` anchored to the bottom, so leave dark, calm sky at the top for the title. Export as WebP at about 70–75 quality and aim for each file to stay under about 16 KB.

## Deviations from the brief (agreed during the build)

1. **Hero backdrop is a WebP image, not SVG.** The client asked for an image background. Fire, cabins and stars are still SVG and fully interactive.
2. **The LCP element is the hero image, not the `<h1>`.** Chrome only skips a background image for LCP when it is under about 0.05 bits/pixel. On mobile that means about 1 KB, which made the image visibly blocky. We chose to keep a good-looking image and meet the LCP < 1.8 s target instead (measured: 1.3 s mobile, 0.3 s desktop). The image is about 4–15 KB and has `fetchpriority="high"`.
3. **`<link rel="canonical" href="/">` costs about 8 SEO points** in Lighthouse (SEO 92), because the audit requires an absolute URL. It is kept literally as specified because the SEO plugin will output the absolute canonical in WordPress. With an absolute URL, SEO scores 100.

## Responsive behaviour

The breakpoints are mobile-first:

| Range | Width |
|---|---|
| Base | ≤ 599px |
| Tablet | ≥ 600px |
| Desktop | ≥ 1024px |
| Wide | ≥ 1440px |

The camp uses a container query (`container: camp / inline-size`):

- **< 540px container (phones):** the fire comes first, large, with its label and description. Below it, the three cabins are full-width cards, each with its mini illustration on the left and text on the right.
- **≥ 540px container (about a 600px viewport):** the arc layout.
  - Study Groups is at the back, in the centre.
  - Library is on the left and About Us on the right.
  - The fire is in front, in the centre.
- **≥ 1024px container:** the same arc with larger illustrations and more spacing.

The same DOM and SVGs are used at every size. Only CSS grid moves them.

## Final measurements

**File sizes** (uncompressed / gzip -9):

| File | Size | Budget |
|---|---|---|
| `index.html` (with all inline SVG) | 30.6 KB / 8.2 KB | ≤ 60 KB ✅ |
| `assets/css/main.css` | 29.1 KB / 7.4 KB | ≤ 30 KB ✅ |
| `assets/js/main.js` | 3.9 KB / 1.6 KB | ≤ 8 KB ✅ |
| `assets/svg/logo.svg` | 0.7 KB | — |
| Hero WebP (one file is loaded per viewport) | 3.7–15.2 KB | — |

**Requests:** the HTML, the CSS, the JS, the logo and one WebP, all local. There are no external requests.

**Lighthouse 13.5** (headless Chromium, served over `http-server`):

| Profile | Performance | Accessibility | Best Practices | SEO | LCP | FCP | CLS | TBT |
|---|---|---|---|---|---|---|---|---|
| Mobile (simulated slow 4G, 4× CPU) | 100 | 100 | 100 | 92* | 1.3 s | 1.1 s | 0 | 0 ms |
| Desktop | 100 | 100 | 100 | 92* | 0.3 s | 0.3 s | 0 | 0 ms |

\* The only failing audit is the relative canonical (see Deviations, item 3). The very first mobile run after a cold browser start reported TBT 380 ms (Performance 91). The next four runs all reported 0 ms.

**Automated checks** (Playwright + Chromium):

- **Horizontal overflow:** none at 20 widths from 320 to 2560 px. These include 640 px, which is equivalent to 1280 px at 200% zoom, and 320 px for reflow.
- **Keyboard order:** skip link → logo → primary menu → header CTA → Kitchen Ideas → Library → Study Groups → About Us → content.
- **Camp links:** each accessible name is exactly its visible label, and each description is exposed via `aria-describedby`.
- **Menu:** the toggle sets `aria-expanded`. Esc closes the menu and returns focus to the button.
- **Without JS:** the menu is expanded and the toggle is hidden.
- **Demo mode:** the notice appears on click and is cleared after about 3 s. With `data-demo` removed, links navigate normally.
- **Pausing:** the hero gets `.is-paused` and all of its animations are paused when it scrolls out of view or when the tab is hidden.
- **`prefers-reduced-motion: reduce`:**
  - 0 running animations;
  - no embers or smoke;
  - hover still lights the windows, instantly.
- **Contrast (WCAG 2.2 AA):** 29 colour pairs were checked, all ≥ the required ratio. For example:
  - h1 on the brightest sky: 13.0:1;
  - camp descriptions on their plaque, even over pure white: 5.8:1;
  - amber focus ring on the sky: 10.5:1.
