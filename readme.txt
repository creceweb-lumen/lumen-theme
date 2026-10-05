=== CreceWeb Lumen ===
Contributors: creceweb
Requires at least: 6.6
Tested up to: 7.1
Requires PHP: 8.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A flexible classic-hybrid theme for professional sites, blogs, and landing pages.

== Description ==

CreceWeb Lumen provides a responsive presentation layer for professional websites, editorial blogs, and commercial landing pages while keeping content in WordPress.

* Flexible header with optional top bar, transparent/sticky/fixed behavior, and accessible desktop/compact navigation.
* Compact mobile menu with optional text/icon open action, site identity, Social Icons placement, and dedicated mobile colors.
* Native left, right, or disabled sidebar layouts. Empty widget areas do not reserve layout space.
* Footer built from WordPress widgets/blocks: one full-width row plus five widget columns, with Classic and Editorial presentation options.
* Editorial footer columns keep Theme-provided fallback content only until that same column receives a widget, so neighboring columns remain independent.
* Presentation support for the WordPress core Social Icons block, with shared or independent header/footer content modes plus placement, icon size, spacing, alignment, and optional colors.
* Unified typography controls for system fonts, optional locally cached Inter, and optional Google Fonts selected with explicit administrator opt-in.
* Full-width, Hero full-width, post full-width, post Hero full-width, and blank landing templates.
* Gutenberg editor styles, wide alignment, block styles, and synchronized Theme color/typography presentation.
* Elementor compatibility on individual pages/posts, including Theme Global Color mirroring and Gallery border fallback behavior without rewriting Elementor document data.
* Customizer controls for colors, typography, header/navigation, content/layout, blog cards, footer/social areas, responsive spacing, accessibility, and optional content-specific color tokens.
* Independent submenu hover text/background colors with optional 0–100% hover-background opacity.
* Optional back-to-top button with keyboard-accessible behavior.
* Translation-ready administration. The Theme source strings use one Spanish source language and include an English (en_US) catalog; WordPress language packs can provide additional locales.

The Theme works independently and does not require a plugin. Compatible extensions may use neutral Theme bridges for optional features without changing Theme ownership of templates or presentation.

== Installation ==

1. In WordPress, go to Appearance > Themes > Add New > Upload Theme.
2. Upload the Theme ZIP and activate CreceWeb Lumen.
3. Open Appearance > CreceWeb Lumen for the guided setup page.
4. Configure site identity, layout, header/navigation, typography, footer, and blog options from Appearance > Customize.
5. Add widgets/blocks only to the areas you want to display.

== Frequently Asked Questions ==

= Does the Theme require a plugin? =

No. CreceWeb Lumen works independently. Compatible extensions may add optional tools when they are installed and activated by the user.

= How does the mobile menu work? =

The compact navigation keeps the desktop menu content while adapting it to a drawer-style layout. You can choose an icon-only or text-plus-icon open action, optionally repeat the site identity and Social Icons inside the mobile panel, and configure mobile normal/hover/active colors independently from desktop navigation.

= How does the footer work? =

Build footer content from Appearance > Widgets. Lumen provides one full-width footer row plus five footer widget columns. Classic keeps a conventional column layout. Editorial gives the first column more visual weight and provides Theme fallback content in its first three columns until you add a widget to that same column. Replacing one Editorial column does not remove the fallback from neighboring columns.

= What typography options are available? =

System fonts require no font request. Inter Local is optional: after explicit administrator selection, WordPress downloads one Latin variable WOFF2 file server-side and stores it in WordPress uploads so visitors receive it from the site's own domain. Google Fonts are also optional; enabling the bundled local catalog does not contact Google, while selecting and using a Google family explicitly enables the remote Google Fonts stylesheet/font requests for that family.

= Does it support Gutenberg? =

Yes. The Theme includes editor styles, wide/full alignment support, block styles, and synchronized Theme typography/color presentation without overriding explicit block choices.

= Can I use Elementor? =

Yes. Elementor can be used on individual pages/posts. The Theme continues to control the global header, navigation, sidebars, and footer. Lumen mirrors its stable Theme colors into the active Elementor Kit as Global Colors and uses Theme values only as defaults/fallbacks; explicit Elementor choices keep priority. Elementor Canvas remains isolated from Theme content integrations.

= Can I customize the blog listing cards? =

Yes. Under Appearance > Customize > Content and layout > Blog and archives, you can edit the listing heading/description, control visible card information, choose supported featured-image ratios, and configure read-more presentation. List-style cards use the available text column while grid layouts keep consistent image placement.

= Can I customize submenu hover colors independently? =

Yes. Under Appearance > Customize > Header and navigation > Submenus, hover text and hover background are independent. The optional hover background includes a 0–100% opacity control. Leaving a hover color empty means Lumen does not force that property.

= How do optional content colors interact with Gutenberg and Elementor? =

Content-specific colors are opt-in. Explicit Gutenberg block colors continue to override Theme defaults. With Elementor active, Lumen mirrors its Theme-owned Global Colors into the active Elementor Kit. Local Elementor colors or another selected Global Color override the Theme default. Lumen does not rewrite page/post Elementor data.

= What does Full width change on individual posts? =

When no sidebar is selected, Full width expands the single-post reading canvas and the native entry header follows the same full canvas. Ordinary Gutenberg content still uses a readable measure unless a block explicitly requests full alignment. Standard, Narrow, and Wide keep their existing behavior.

= How do I add social network icons? =

Use the native WordPress Social Icons block in Appearance > Widgets > Social networks · Header or Social networks · Footer. Then choose content mode, placement and appearance from Appearance > Customize > Footer and social networks > Social networks. Lumen only presents the WordPress core block; it does not store profile URLs, add social tracking, or implement follow/share APIs.

= Does the Theme import demo content? =

No. The Theme does not import posts, pages, menus, widgets, or remote demo content.

= Does the Theme make remote requests by default? =

No font or catalog request is made by default. Remote font access happens only after explicit administrator selection of Inter Local or a Google Fonts family, as documented above and in the Resources section.

== Changelog ==

= 1.4.110 =
* Adds the human-readable source copy for the minified core stylesheet required for review while keeping the runtime bundle unchanged.
* Keeps Lumen Lite onboarding optional and limited to native WordPress.org install/activate actions, without an external promotional link.
* Refreshes public documentation and the bundled English translation metadata for this WordPress.org compliance release.

= 1.4.109 =
* Refines the compact/mobile navigation with optional text/icon open actions, reusable site identity, Social Icons support inside the drawer, full-row separators, and dedicated mobile colors.
* Adds Classic and Editorial footer presentation options, a full-width footer row, five widget columns, and per-column Editorial fallback behavior that remains independent while editing in the Customizer.
* Reorganizes the Customizer into clearer global design, header/navigation, content/layout, footer/social, accessibility, responsive, help, and support areas while preserving saved setting IDs.
* Expands typography with system fonts, explicit opt-in Inter Local caching, and optional Google Fonts from a bundled local catalog; no font request occurs by default.
* Keeps Inter cache metadata inside the existing single Theme settings option and migrates the former standalone cache key without changing the selected typography.
* Adds post full-width and post Hero full-width templates while preserving existing post metadata, featured images, tags, navigation, comments, and sidebar behavior.
* Improves blog/card presentation, submenu hover controls, header distribution, Social Icons rendering, responsive behavior, and accessible navigation states.
* Keeps Gutenberg editor presentation synchronized with Theme typography/colors and improves Elementor Global Color/Gallery compatibility without rewriting Elementor page data.
* Moves invariant frontend rules into cacheable Theme assets where appropriate while keeping settings-dependent output conditional.
* Extends neutral compatibility bridges for optional breadcrumbs, registered sidebars, portable configuration, typography extensions, and provider-neutral messaging without requiring or bundling plugin functionality.
* Updates documentation, resource licensing, template-name coverage, theme.json preset-name localization, and the bundled en_US translation catalog.

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
* Reverts the rejected Theme-level extension Hero geometry adapter introduced in 1.4.100.
* Returns internal layout ownership of extension-provided Hero variants to their owning extension while preserving the top-bar, header-height, pagination, Theme Unit Test, and WordPress.org preparation changes.

= 1.4.100 =
* Restores the structural stage reserve for a compatible split Hero with feature rail in the Hero full-width template, keeping CTA buttons visible above the rail.
* Restores the approved lateral gutters and maximum width for a compatible mockup Hero in the Hero full-width template.

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
* Removes inactive extension promotion and external upgrade links from the theme administration.
* Keeps compatibility bridges available only for extensions already installed by the user.
* Cleans development documentation from the public package and updates licensing information.
* Preserves layouts, headers, templates, Gutenberg styling, and saved theme settings.

== Upgrade Notice ==

= 1.4.110 =
* WordPress.org compliance cleanup: includes the readable core CSS source and keeps the optional Lumen Lite onboarding neutral, with no frontend behavior changes.

= 1.4.109 =
* Refreshes mobile navigation, footer layouts, typography, editor/Elementor integration, and translation coverage while preserving existing settings and keeping remote font use opt-in.

== Resources ==

CreceWeb Lumen Theme code and design
Copyright 2026 CreceWeb.
Source: https://creceweb.com.ar/lumen-theme
License: GNU General Public License v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Theme screenshot and original illustration (screenshot.png)
Copyright 2026 CreceWeb.
Source: https://creceweb.com.ar/lumen-theme
Note: Original CreceWeb asset created specifically for CreceWeb Lumen.
License: GNU General Public License v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Lumen Lite compatibility logo (assets/images/lumen-lite-logo.webp)
Copyright 2026 CreceWeb.
Source: https://creceweb.com.ar/lumen-lite
Note: Original CreceWeb asset used only in the Theme administration/onboarding interface.
License: GNU General Public License v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Inline SVG geometric icons
Copyright 2026 CreceWeb.
Source: https://creceweb.com.ar/lumen-theme
Note: Original CreceWeb assets included inline in Theme PHP templates.
License: GNU General Public License v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Fonts
By default the Theme uses system font stacks and loads no font files. Inter Local is optional: after explicit administrator selection, WordPress downloads one Latin variable WOFF2 file server-side from Google Fonts and stores it through the WordPress uploads API; visitors then receive that cached file from the site's own domain. Google Fonts are also optional and remote only after explicit administrator selection of a family. The bundled Google Fonts catalog is metadata only and does not contact Google when displayed.

Inter
Copyright 2020 The Inter Project Authors (https://github.com/rsms/inter).
Source: https://github.com/rsms/inter
Local font source used after opt-in: https://fonts.gstatic.com/
Bundled license text: assets/licenses/inter-OFL-1.1.txt
License: SIL Open Font License 1.1
License URI: https://openfontlicense.org/

Google Fonts catalog metadata
Source: https://fonts.google.com/
File: assets/data/google-fonts-catalog.json
Use: Bundled metadata only (family names, categories and available weights) for the Customizer selector. It contains no font binaries, CSS, or executable code and makes no external request when displayed.

Google Fonts remote service
Service: https://fonts.google.com/
Stylesheet endpoint: https://fonts.googleapis.com/css2
Font files: https://fonts.gstatic.com/
Use: Optional and conditional on explicit administrator selection of a Google Fonts family. Showing the bundled catalog metadata does not contact Google.
Terms: https://developers.google.com/fonts/terms
Privacy: https://policies.google.com/privacy

Theme CSS source mapping
The runtime file assets/css/lumen-core.min.css has its human-readable source counterpart at assets/css/lumen-core.css. The Theme continues serving the minified runtime file; the source copy is included for review and redistribution requirements.

Third-party libraries and assets
No third-party JavaScript, CSS, images, icon libraries, or font binaries are bundled with this Theme. WordPress core-provided dependencies such as jQuery and Dashicons are referenced through WordPress APIs and are not bundled.
