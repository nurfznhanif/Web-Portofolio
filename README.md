# Nurfauzan Hanif — Portfolio

A warm, editorial single-page portfolio website. Built as a static site (HTML, CSS, vanilla JavaScript) — no build step, no dependencies.

## Structure

```
WebPorto/
├── index.html            # the whole page
├── css/styles.css        # design system + all sections (light & dark themes)
├── js/main.js            # project data, filtering, lightbox modal, interactions
├── favicon.svg           # "NH" monogram tab icon
└── Assets/
    ├── profile.jpg              # hero portrait (optimized)
    ├── CV - Nurfauzan Hanif.pdf # downloadable CV (matches the site style)
    └── projects/               # optimized project screenshots
```

## Preview locally

Because the page loads local files (CSS, JS, images), open it through a small web server rather than double-clicking the file:

```bash
python -m http.server 8123
```

Then visit http://127.0.0.1:8123 in your browser.

## Deploy (free options)

Any static host works. Easiest paths:

- **GitHub Pages** — push this folder to a repo, then enable Pages on the `main` branch (root).
- **Netlify** — drag-and-drop the `WebPorto` folder onto https://app.netlify.com/drop.
- **Vercel** — `vercel` in this folder, accept the static defaults.

## Editing content

- **Projects** live in the `PROJECTS` array in `js/main.js`. Translatable fields (`title`, `role`, `context`, `blurb`, `desc`, `note`) hold `{ en, id }` — edit both, or pass a plain string if a field is the same in both languages. `tech`, `repo`, and the cover/screenshots stay as-is.
- **Text sections** (About, Experience, Skills, Contact) are plain HTML in `index.html`. Each translatable element has a `data-i18n="key"`; the English text lives in the HTML, and the Indonesian text lives in the `ID` dictionary in `js/main.js` under the same key. To edit English, change the HTML; to edit Indonesian, change the dictionary.
- **Colors & type** are CSS variables at the top of `css/styles.css` (`--accent`, `--bg`, `--ink`, fonts). Change `--accent` to reskin the whole site.

## Notes

- **Bilingual (EN / ID)** — a language toggle in the header switches the whole site, including project details; the choice is remembered. English is the default; to make Indonesian the default, change the fallback in the `applyLang(...)` call near the bottom of `js/main.js`.
- **Light / dark theme** follows the visitor's system setting and can be toggled; the choice is remembered.
- Fully responsive, keyboard-accessible modal, and reduced-motion friendly.
