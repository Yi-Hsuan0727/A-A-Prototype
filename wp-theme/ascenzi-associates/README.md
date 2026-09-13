# Ascenzi & Associates — WordPress Theme

A from-scratch WordPress theme converting the approved `.dc.html` design
prototype into a real, installable site. Every piece of visible content is
editable from wp-admin — there are no hardcoded copy strings in the
templates for Home, About, Services or Privacy Policy.

## Install

1. Copy this folder (`ascenzi-associates`) into `wp-content/themes/`.
2. Activate it under **Appearance → Themes**. This flushes permalinks
   automatically (the theme registers custom URL slugs for Services and
   Insight).
3. Create three Pages (**Pages → Add New**) and assign each the matching
   **Page Template** (right-hand sidebar → Template):
   - "Home" — then go to **Settings → Reading** and set this as your
     "homepage displays: a static page → Homepage".
   - "About"
   - "Legal Page" (used for Privacy Policy — the footer's "Privacy Policy &
     Terms" link automatically points at whichever page uses this template)
4. Fill in each page's meta boxes (they appear below the title once the
   right template is selected and the page is saved once).
5. Go to **Settings → Ascenzi Settings** and set the phone number, office
   address, and the email address enquiry-form submissions should go to.
6. Set a **Site Icon** under **Appearance → Customize → Site Identity** for
   the browser-tab favicon, and a **Custom Logo** for the header/footer
   logo (or just leave the bundled `assets/images/logo-horizon.png` in
   place — the theme falls back to it automatically).
7. Create four **Services** posts (Talent Acquisition, Licensing, U.S.
   Market Entry, Supporting & Growth) — order them via each post's **Order**
   field under Page Attributes (lower number = earlier in the package grid,
   header dropdown, and "Other services" lists).
8. Create **Insight Categories** (Industry News, U.S. Market Entry,
   Workforce & Talent, Regulatory & Compliance, Project Insights are
   pre-seeded on first activation — add more any time) and start publishing
   **Insight Posts**.

## Bilingual content (English / 繁體中文)

There is no multilingual plugin dependency. Every translatable field in a
meta box renders as an **EN** / **繁中** pair and is stored as two post-meta
keys (`{field}_en` / `{field}_zh`). The visitor-facing switch is a small
cookie (`ascenzi_lang`) set by the header's language dropdown — switching
reloads the page so PHP can render the other language's fields. If a
Chinese field is left blank, the English value is shown instead, so a page
is never blank mid-translation.

Category/tag names get their Chinese label from a "Chinese Name" field on
the term's edit screen (Insight → Categories / Topic Tags).

## What's real vs. a stub

- **Contact / Partnership enquiry form**: real. Submits over AJAX to
  `wp_mail()` using the address set in Ascenzi Settings. See
  `inc/contact-form-handler.php` — there's an action hook
  (`ascenzi_contact_submitted`) if you later want to also log submissions
  to a CRM, spreadsheet, etc.
- **Newsletter signup** (footer + Insight archive): front-end only, shows a
  static "you're on the list" confirmation. No email-list service account
  was available to connect it to — wire it up in
  `assets/js/main.js` (`data-newsletter-form` handler) once you have one.
- **Insight article body**: a bilingual **WYSIWYG** meta field (not the
  native block editor), so one post holds both languages' full rich text
  (headings, images, tables, etc. all work from the toolbar). This was a
  deliberate simplification — WordPress's native content editor isn't
  naturally bilingual without a second post per language (which is what
  WPML/Polylang do, and was explicitly ruled out for this project).
- **Repeater fields** (FAQ, road steps, partner cards, etc.): admin can add
  and remove rows, but not drag-reorder them — remove and re-add in the
  order you want if you need to resequence.
- **World coverage map**: loads d3 + topojson-client and world topology
  JSON from a CDN (jsdelivr) at runtime — requires the visitor's browser to
  reach that CDN; there's no bundled offline fallback.

## File map

- `functions.php` — theme bootstrap, asset enqueueing.
- `inc/helpers.php` — language detection, bilingual field getters, icon
  rendering, small shared UI strings (`ascenzi_t()`).
- `inc/metabox-framework.php` — the generic engine every `metaboxes-*.php`
  file is built on (bilingual text/textarea, image picker, icon picker,
  repeaters) — read this first if you need to add a new field anywhere.
- `inc/cpt-service.php` / `inc/cpt-insight.php` — the two custom post
  types.
- `inc/metaboxes-*.php` — the field schema for each content area (Home,
  About, Privacy, Service, Insight).
- `inc/contact-form-handler.php` — the real `wp_mail()` handler.
- `inc/nav-services.php` — renders the header's Services mega-dropdown
  from the Service post type.
- `template-home.php` / `template-about.php` / `template-legal.php` —
  the three custom Page Templates.
- `single-service.php` / `single-insight_post.php` /
  `archive-insight_post.php` — the Service and Insight templates.
- `assets/css/theme.css` — all visual styling (design tokens as CSS custom
  properties at the top).
- `assets/js/main.js` — every front-end interaction (menus, modal, FAQ
  accordion, hover-reveal cards, the road-timeline curve tracking, the
  partnership parallax, Insight filters).
- `assets/js/road-map.js` — the world map (front page only).
- `assets/images/icons/` — the mask-based icon set used via the Icon field
  type; eight of these (academic/institutional/professional/specialist,
  folder/grid/pin/network) were extracted from inline SVGs in the original
  prototype into standalone files so they could become a selectable field.

## Known gaps

- **`wp_mail()` needs a working mail transport on the host.** Many servers
  (and any local dev environment without `sendmail`/SMTP configured) can't
  actually deliver mail out of the box — `wp_mail()` will return `false` and
  the enquiry form will show its honest "please call us directly" fallback
  instead of silently pretending to succeed. If enquiries aren't arriving on
  a live site, install a free SMTP plugin (e.g. WP Mail SMTP) and connect it
  to a transactional email provider or the host's SMTP relay.

- No automated test suite — this was validated with `php -l` (syntax) and
  Node's JS parser (syntax) on every file, plus a careful manual trace of
  every meta-box field key against its corresponding template read. It has
  **not** been run against a live WordPress + MySQL install in this
  environment (none was available here); do a full click-through in a
  local WP install (e.g. `wp-env`, Local, or `wp-cli` + a database) before
  going live.
- No automated translation file (`.pot`/`.po`) is bundled, since all visible
  bilingual copy goes through the meta-box/`ascenzi_t()` system rather than
  WordPress's gettext runtime — there is nothing for a `.po` file to
  translate on the front end. The theme still calls `load_theme_textdomain()`
  for the (English-only) admin-side field labels, in case a translator
  wants to localize the wp-admin UI itself.
