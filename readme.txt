=== CreceWeb Lumen ===
Contributors: creceweb
Requires at least: 6.6
Tested up to: 7.1
Requires PHP: 8.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A flexible classic-hybrid theme for professional sites, blogs, and landing pages.

== Description ==

CreceWeb Lumen provides a clean, responsive foundation for professional websites, editorial blogs, and commercial landing pages.

* Flexible header and accessible desktop and mobile navigation.
* Native left, right, or disabled sidebar layouts.
* Widget-based top bar and footer areas.
* Presentation support for the WordPress core Social Icons block, with shared or independent header/footer content modes plus theme-controlled placement, true icon size, spacing, alignment, and optional colors.
* Full-width, hero full-width, and blank landing templates.
* Gutenberg editor styles, wide alignment, and block styles.
* Customizer controls for colors, typography, layout, blog cards, editable blog-list headings, card content visibility, read-more presentation, footer, responsive spacing, and optional content-specific color tokens.
* Independent submenu hover text/background colors with optional 0–100% hover-background opacity; empty fields leave that hover property unforced.
* Optional back-to-top button with keyboard-accessible behavior.
* Translation-ready administration in Spanish and English.
* Compatible with Elementor on individual pages.

The theme works independently and does not require a plugin.

== Installation ==

1. In WordPress, go to Appearance > Themes > Add New > Upload Theme.
2. Upload the theme ZIP and activate CreceWeb Lumen.
3. Open Appearance > CreceWeb Lumen for the guided setup page.
4. Configure the site identity, layout, footer, and blog from Appearance > Customize.
5. Add widgets only to the areas you want to display.

== Frequently Asked Questions ==

= Does the theme require a plugin? =

No. CreceWeb Lumen works independently. Compatible extensions may add optional tools when they are installed by the user.

= Does it support Gutenberg? =

Yes. The theme includes editor styles, wide and full alignment support, block styles, and layout rules shared between the editor and the front end.

= Can I use Elementor? =

Yes. Elementor can be used on individual pages. The theme continues to control the global header, navigation, sidebars, and footer. On supported page/post documents, Elementor Gallery keeps any explicit widget or Global Color border choice; when no Gallery border color is saved, Lumen uses the Theme global border color as a visual fallback in both the front end and Elementor preview. Elementor Canvas remains isolated from these Theme content integrations.

= Can I customize the blog listing cards? =

Yes. Under Appearance > Customize > Design > Content and blog, you can edit the latest-posts heading and description, show or hide the category, choose Original, Landscape 16:9, or Square 1:1 featured-image ratios, and configure the read-more label, style, shape, and optional colors. Media-left and Compact list cards respect the selected image ratio and let titles and excerpts use the available text column.

= Can I customize submenu hover colors independently? =

Yes. Under Appearance > Customize > Design > Header and navigation > Navigation, the submenu hover text and hover background can be set independently. The hover background also has a 0–100% opacity control. Leaving either hover color empty means Lumen does not force that property, so you can use text-only hover, background-only hover, both, or neither. On desktop, the optional submenu hover background spans the full row width.

= How do the optional content colors interact with Gutenberg and Elementor? =

The content color controls are opt-in. In Gutenberg, explicit block colors continue to override the Theme content token. With Elementor active, Lumen mirrors 16 stable Lumen-owned custom Global Colors into the active Elementor Kit: the 10-color base Theme palette plus six content-semantic colors. The picker keeps them together with Base/Content title prefixes, and Elementor Site Settings exposes the same colors in two compact Lumen sections. The Lumen Customizer remains the source of truth. Only configured content-semantic colors become inherited defaults for compatible Heading, Button, and Icon List widgets; choosing a local color or another Elementor Global Color overrides the Lumen default. Lumen does not rewrite page/post Elementor data. Lumen box colors remain opt-in through the Lumen box classes, and Elementor Canvas templates remain isolated from these rules.

= What does the Full width reading option change on individual posts? =

Full width expands the single-post reading canvas when no sidebar is selected. The native entry header follows the same full canvas, while ordinary Gutenberg content remains constrained to a readable wide measure unless a block explicitly uses a full-width alignment. Elementor page/post content can use the available full canvas. Standard, Narrow, and Wide keep their existing behavior.

= How do I add social network icons? =

Use the native WordPress Social Icons block in Appearance > Widgets > Social networks · Header or Social networks · Footer. Then choose the content mode, placement and appearance from Appearance > Customize > Design > Social networks. Shared mode uses one block for both locations (Footer is the primary shared source, with Header as a backwards-compatible fallback). Independent mode lets Header and Footer use different Social Icons blocks. Lumen only styles and positions the WordPress core Social Icons block. It does not store social profile URLs in theme options and does not add social follow, like, share, feed, tracking, or API functionality. The links remain normal WordPress block/widget content, and the header and footer placements remain hidden by default.

= Does the theme import demo content? =

No. It does not import posts, pages, widgets, or remote content. Users create and manage their own content with WordPress.

== Changelog ==

= 1.4.109 =
* Keeps the provider-neutral Messaging bridge from narrowing extension-provided button sizes and makes floating-action compatibility guidance extension-neutral.
* Updates the optional extension bridge to provider-neutral Messaging API 1.6.0 while preserving the legacy WhatsApp compatibility projection; the Theme still does not configure or render Messaging on its own.
* Adds a discreet Customizer support link under Design > Help and documentation for the voluntary support page for Lumen Theme and Lumen Lite; it adds no setting, automatic remote request, or premium gating.
* Skips classic navigation and header-behavior JavaScript on the “Lumen: Landing sin cabecera” template, whose canvas markup intentionally has no Theme header or navigation.
* Moves invariant Customizer frontend contracts from per-request inline CSS into the existing cacheable Theme stylesheet while keeping settings-dependent CSS inline and Gutenberg editor output unchanged.
* Defers the classic navigation runtime in the document head while preserving the early navigation marker, localized strings, DOMContentLoaded initialization, and existing menu behavior.
* Makes the keyboard skip link fully own its visible focus geometry so it remains centered and unclipped across theme/plugin screen-reader styles and narrow viewports.
* Loads blog/archive/search, single-post, and comments presentation only on requests that use those native Theme contexts, reducing global frontend CSS without changing markup or visual contracts.
* Keeps mobile full-width Hero templates content-sized when more sections follow, while preserving viewport-height behavior for true Hero-only layouts.
* Keeps Gutenberg-only Section Base and Customizer preview selectors out of public frontend CSS while preserving the editor output and existing section contracts.
* Loads Hero/template CSS conditionally, keeping Hero-specific contracts out of the global stylesheet on pages that do not use a Lumen Hero while preserving Gutenberg editor fallbacks.
* Adds dedicated full-width and full-width Hero templates for single posts while preserving Lumen single-post metadata, featured-image, tags, navigation, comments, and sidebar contracts.
* Restores Elementor Gallery border-color rendering in both the front end and visual editor: explicit widget/Global Colors keep priority, while galleries without a saved border color fall back to Lumen's global border color token.
* Improves Lumen admin-notice contrast inside the Theme hub and keeps Previous/Next post navigation text aligned consistently, with equal-height mobile destination cards.
* Balances lateral and compact blog cards by centering media and content on the same vertical axis while preserving the selected image ratio and reducing unnecessary vertical body padding.
* Lets blog-card titles and excerpts use the full available card width on mobile before wrapping.
* Fixes premature title/excerpt wrapping in lateral and compact blog cards so text uses the available content column before moving to a new line.
* Fixes the custom read-more text color so it is no longer overridden by the global content-link color.
* Makes Square 1:1, Landscape 16:9, and Original featured-image modes work in desktop Media left and Compact list cards instead of stretching those images to the full card height.
* Adds editable latest-posts list title/description controls, with the description optionally empty and semantic H1 fallback when the separate blog intro is disabled.
* Adds a category visibility toggle for archive cards and lets Media left/Compact card titles and excerpts use the full content column width.
* Adds read-more style, shape, and optional background/text/border hover color controls while preserving the historical Lumen presentation as the default and keeping the existing show/hide switch.
* Refines compact-menu submenu-toggle alignment by assigning explicit grid rows to parent links, toggles and submenus, stretching the toggle to the parent row, and optically centering the CSS chevron.
* Centers compact-menu parent labels vertically against the submenu toggle without changing row width or toggle geometry.
* Adds a 0–100% opacity control for the optional submenu hover background, so the selected color can range from transparent to solid without affecting the submenu hover text.
* Aligns compact-menu parent separators as one continuous row divider across the link and submenu-toggle columns.
* Adds independent optional submenu hover text/background colors; leaving either field empty preserves that property instead of inheriting the main-menu hover color.
* Makes desktop submenu hover/focus backgrounds span the full row width while preserving the dropdown's vertical breathing room.
* Gives footer Social Icons more balanced vertical breathing room across footer density presets, while keeping a small safe inset even when the footer uses No spacing.
* Makes Header > Internal distribution visibly distinct between Left, Centered, and Separated, and adds a Full internal-width option for headers that should sit closer to the viewport edges.
* Fixes Social Icons sizing so the control changes the rendered SVG/icon size instead of only affecting the surrounding block, vertically centers the social row, and adds an explicit shared/independent content mode for header and footer.
* Fixes Social Icons presentation by removing stray list markers, makes empty optional color controls visibly show an unset state, and lets an enabled header/footer social region reuse the other Social Icons widget area when its own area is empty.
* Adds native Social Icons presentation areas for the header and footer, using WordPress widget/block content for profile links while Lumen controls placement, size, spacing, optional colors, footer alignment, and compact-header visibility.
* Keeps social data and functionality outside the theme: Lumen only presents the WordPress core Social Icons block and does not store profile URLs or add follow, like, share, feed, tracking, or API features.
* Adds a “No spacing” footer density option that removes the footer’s top separation and the vertical padding from its widget area while leaving copyright spacing and existing footer densities unchanged.
* Adds optional content-specific color controls for headings, buttons, Lumen boxes, and list bullets/icons.
* Keeps the new content controls empty by default, so upgrading does not recolor existing Gutenberg patterns or Elementor pages.
* Mirrors 16 stable Lumen-owned Global Colors into the active Elementor Kit: 10 base Theme colors plus six content-semantic colors, while preserving Elementor system colors and unrelated custom colors.
* Orders Lumen Global Colors as Base then Content in the Elementor picker and adds two compact Lumen sections to Site Settings for easier palette inspection.
* Uses configured Lumen heading, button background/hover/text, and Icon List colors as native inherited defaults for compatible Elementor widgets; a local color or another Elementor Global Color selected in the widget overrides the Lumen default.
* Keeps the Lumen Customizer as the one-way source of truth for those mirrored colors, never writes Elementor color changes back to Theme settings, and clears Elementor generated CSS only when mirrored Theme colors change.
* Keeps Elementor Canvas and Theme Builder documents isolated from Lumen content defaults, while Gutenberg explicit-color behavior remains unchanged.
* Adds the cw-content-box and cw-content-box--filled class contract for reusable box accents without changing existing pattern markup.
* Adds an opt-in “Full width” reading-width option for individual posts.
* In Full mode, the native entry header aligns with the full reading canvas instead of the legacy 48rem editorial cap.
* Keeps Standard, Narrow, Wide, sidebar behavior, page templates, and all existing defaults unchanged.
* Lets explicit full-width Gutenberg blocks and Elementor content use the available single-post width while ordinary blocks remain bounded by the site wide measure.

= 1.4.108 =
* Limits Gutenberg root block width rules to top-level blocks so nested pattern layouts keep their intended geometry.
* Restores the full-canvas post-Hero content contract for the Hero full-width template when no sidebar is selected.
* Mirrors Customizer typography, heading color, and link color choices in the Gutenberg editor without overriding explicit block styles.
* Includes the English theme description and complete resource licensing documentation introduced during the WordPress.org review.

= 1.4.107 =
* Increases the public theme version for the WordPress.org resubmission after reviewer-requested metadata and licensing corrections.
* Keeps the reviewer corrections from 1.4.106 with no additional functional changes.

= 1.4.106 =
* Writes the public theme description entirely in English for the WordPress.org Theme Directory review.
* Reconfirms that user-facing strings in PHP are internationalized with the creceweb-lumen text domain.
* Adds complete resource copyright, source, license type, and license URL details directly to readme.txt.
* Removes the duplicated top spacing through the existing default-template leading-Hero body contract.
* Clips full-bleed viewport overflow at the document body and site shell without changing pattern files.
* Removes external extension recommendations from the theme administration while preserving compatibility for extensions already installed by the user.
* Migrates the theme settings to the slug-prefixed single option without losing existing values.
* Corrects static-front-page template loading and adds content pagination to every page route.
* Improves JavaScript internationalization and cleans the bundled English translation catalog.
* Completes translation coverage for page-template names and visible fallback labels.
* Localizes the human-readable theme.json preset names without changing their stable slugs.
* Uses the WordPress admin-notices API for one-time reset feedback and keeps compatibility guidance as contextual page content.
* Preserves readable text contrast in reset feedback across the theme administration screen.
* Limits the Customizer return-target safeguard to the theme-upload flow owned by the theme.

= 1.4.101 =
* Reverts the rejected Theme-level Pro Hero geometry adapter introduced in 1.4.100.
* Returns the internal layout ownership of premium Heroes to Lumen Pro while preserving the top-bar, header-height, pagination, Theme Unit Test, and WordPress.org preparation changes.

= 1.4.100 =
* Restores the structural stage reserve for the Pro split Hero with feature rail in the Hero full-width template, keeping CTA buttons visible above the rail.
* Restores the approved lateral gutters and maximum width for the Pro mockup Hero in the Hero full-width template.

= 1.4.99 =
* Balances the primary logo and navigation row when the optional top bar is present while preserving combined fixed-header measurements for Lumen Heroes.
* Uses compact translated Previous/Next labels and a reduced page-number window so archive pagination fits on one mobile row without horizontal scrolling.

= 1.4.97 =
* Updates the administration header identity to Lumen by CreceWeb.
* Adds direct links to the institutional CreceWeb site and the dedicated Lumen Theme product page.

= 1.4.96 =
* Keeps Gutenberg Preformatted and Code blocks inside standard page layouts with local horizontal scrolling.
* Clears final floated media in pages before pagination, comments, and following layout elements.

= 1.4.95 =
* Updated the public Theme URI to the dedicated CreceWeb Lumen Theme product page.
* Kept the Author URI pointing to the institutional CreceWeb website.

= 1.4.94 =
* Corrects third-level desktop submenu positioning and focus access.
* Keeps the temporary page-menu fallback compact until a menu is assigned.
* Contains long titles, preformatted text, post-navigation labels, and video embeds within their layouts.
* Clears final floated media before tags and post navigation.

= 1.4.93 =
* Prepared the public WordPress.org distribution.
* Uses the directory-safe public name CreceWeb Lumen.
* Removes automatic sidebar widget creation and legacy theme-owned WhatsApp output.
* Removes inactive premium promotion and external upgrade links from the theme administration.
* Keeps compatibility bridges available only for extensions already installed by the user.
* Cleans development documentation from the public package and updates licensing information.
* Preserves layouts, headers, templates, Gutenberg styling, and saved theme settings.

== Upgrade Notice ==

= 1.4.109 =
* Updates the optional extension bridge for provider-neutral Messaging compatibility and includes the current blog/navigation, accessibility, performance, and Elementor refinements. The Theme still does not configure or render Messaging itself.

== Resources ==

CreceWeb Lumen theme code and design
Copyright 2026 CreceWeb.
Source: https://creceweb.com.ar/lumen-theme
License: GNU General Public License v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Theme screenshot and original illustration (screenshot.png)
Copyright 2026 CreceWeb.
Source: https://creceweb.com.ar/lumen-theme
Note: Original CreceWeb asset created specifically for CreceWeb Lumen and included in this theme package.
License: GNU General Public License v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Inline SVG geometric icons
Copyright 2026 CreceWeb.
Source: https://creceweb.com.ar/lumen-theme
Note: Original CreceWeb assets created specifically for CreceWeb Lumen and included inline in the theme PHP templates.
License: GNU General Public License v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Fonts
No font files or remote font services are bundled or loaded. The theme uses system font stacks.

Third-party libraries and assets
No third-party JavaScript, CSS, fonts, images, icon libraries, or other third-party assets are bundled with this theme. WordPress core-provided dependencies such as jQuery and Dashicons are referenced through WordPress APIs and are not bundled.
