<?php
/**
 * Front-end assets.
 *
 * @package CreceWebLumen
 */

namespace CreceWeb\Lumen;

/**
 * Returns whether the current public request needs the conditional Hero CSS.
 *
 * Hero-specific page/post templates opt in explicitly. Standard and full-width
 * pages opt in only when their first visible full-width block is a Lumen Hero.
 * Other singular content and archives keep the base stylesheet only.
 *
 * @return bool
 */
function request_uses_hero_styles(): bool {
	if ( ! function_exists( 'is_singular' ) || ! is_singular() ) {
		return false;
	}

	$post_id = function_exists( 'get_queried_object_id' ) ? (int) get_queried_object_id() : 0;
	if ( $post_id <= 0 ) {
		return false;
	}

	$template = function_exists( 'get_page_template_slug' ) ? (string) get_page_template_slug( $post_id ) : '';
	if ( in_array( $template, array( 'page-templates/hero-full-width.php', 'page-templates/post-hero-full-width.php' ), true ) ) {
		return true;
	}

	$post_type = function_exists( 'get_post_type' ) ? (string) get_post_type( $post_id ) : '';
	if ( 'page' !== $post_type || ! in_array( $template, array( '', 'default', 'page-templates/full-width.php' ), true ) ) {
		return false;
	}

	$content = function_exists( 'get_post_field' ) ? (string) get_post_field( 'post_content', $post_id ) : '';
	if ( '' === trim( $content ) || ! function_exists( 'has_blocks' ) || ! has_blocks( $content ) || ! function_exists( 'parse_blocks' ) ) {
		return false;
	}

	$blocks = parse_blocks( $content );
	return is_array( $blocks ) && null !== get_leading_lumen_hero_block( $blocks );
}

/**
 * Returns whether the current request is a native post-list context.
 *
 * @return bool
 */
function request_uses_blog_archive_styles(): bool {
	return ( function_exists( 'is_home' ) && is_home() )
		|| ( function_exists( 'is_archive' ) && is_archive() )
		|| ( function_exists( 'is_search' ) && is_search() );
}

/**
 * Returns whether the current request uses the native single-post/CPT/attachment layout.
 *
 * @return bool
 */
function request_uses_single_styles(): bool {
	return ( function_exists( 'is_single' ) && is_single() )
		|| ( function_exists( 'is_attachment' ) && is_attachment() );
}

/**
 * Returns whether native comments can render for the queried singular object.
 *
 * Pages can render comments too, so this gate intentionally stays independent
 * from the single-post stylesheet.
 *
 * @return bool
 */
function request_uses_comments_styles(): bool {
	if ( ! function_exists( 'is_singular' ) || ! is_singular() ) {
		return false;
	}

	$post_id = function_exists( 'get_queried_object_id' ) ? (int) get_queried_object_id() : 0;
	if ( $post_id <= 0 ) {
		return false;
	}

	$comments_are_open = function_exists( 'comments_open' ) && comments_open( $post_id );
	$comment_count      = function_exists( 'get_comments_number' ) ? (int) get_comments_number( $post_id ) : 0;

	return $comments_are_open || $comment_count > 0;
}

/**
 * Returns whether the current request renders the Theme header/navigation shell.
 *
 * The Landing Canvas template intentionally omits the Theme header and footer,
 * so its navigation and header-behavior runtimes have no DOM contract to manage.
 *
 * @return bool
 */
function request_uses_header_scripts(): bool {
	return ! ( function_exists( 'is_page_template' ) && is_page_template( 'page-templates/landing-canvas.php' ) );
}

/**
 * Returns Theme-owned frontend module requirements for the current request.
 *
 * @return array<int,string>
 */
function theme_needs_chrome_extras(): bool {
	$settings = get_customizations();

	/*
	 * Footer Presets FP1.2: the Editorial fallback renders Theme-owned footer
	 * columns even when no native footer sidebar is active. Those structural
	 * contracts live in the full chrome bundle, so selecting Editorial must
	 * opt into chrome_extras independently from widget activity.
	 */
	if ( 'editorial' === (string) ( $settings['footer_layout_preset'] ?? 'classic' ) ) {
		return true;
	}

	if (
		'1' === (string) ( $settings['top_bar_enabled'] ?? '0' )
		&& function_exists( 'is_active_sidebar' )
		&& is_active_sidebar( CRECEWEB_TOP_BAR_WIDGET_AREA )
	) {
		return true;
	}

	if ( function_exists( 'is_active_sidebar' ) ) {
		if ( is_active_sidebar( CRECEWEB_FOOTER_BAR_WIDGET_AREA ) ) {
			return true;
		}

		foreach ( CRECEWEB_FOOTER_WIDGET_COLUMNS as $sidebar_id ) {
			if ( is_active_sidebar( $sidebar_id ) ) {
				return true;
			}
		}
	}

	$header_visible = 'hidden' !== (string) ( $settings['social_header_position'] ?? 'hidden' );
	$footer_visible = 'hidden' !== (string) ( $settings['social_footer_position'] ?? 'hidden' );
	if ( ! $header_visible && ! $footer_visible ) {
		return false;
	}

	if ( ! function_exists( 'is_active_sidebar' ) ) {
		return false;
	}

	$mode = sanitize_key( (string) ( $settings['social_content_mode'] ?? 'shared' ) );
	if ( 'separate' === $mode ) {
		return ( $header_visible && is_active_sidebar( CRECEWEB_HEADER_SOCIAL_WIDGET_AREA ) )
			|| ( $footer_visible && is_active_sidebar( CRECEWEB_FOOTER_SOCIAL_WIDGET_AREA ) );
	}

	return is_active_sidebar( CRECEWEB_FOOTER_SOCIAL_WIDGET_AREA )
		|| is_active_sidebar( CRECEWEB_HEADER_SOCIAL_WIDGET_AREA );
}

/**
 * Returns Theme-owned frontend module requirements for the current request.
 *
 * Chrome Extras is a site-level capability and intentionally remains resolved
 * even on Landing Canvas so one site keeps a stable primary stylesheet URL.
 *
 * @return array<int,string>
 */
function get_theme_required_frontend_modules(): array {
	$modules = array();

	if ( theme_needs_chrome_extras() ) {
		$modules[] = 'chrome_extras';
	}

	if ( ! request_uses_header_scripts() ) {
		return $modules;
	}

	$settings = get_customizations();
	$behavior = sanitize_key( (string) ( $settings['header_behavior'] ?? 'static' ) );

	if ( in_array( $behavior, array( 'sticky', 'fixed' ), true ) || request_uses_hero_styles() ) {
		$modules[] = 'header_metrics';
	}

	return $modules;
}

/**
 * Returns the primary production stylesheet for the resolved module set.
 *
 * Sites without Chrome Extras use the reduced bundle. The complete minified
 * bundle is the safe fallback and remains mandatory whenever an extension
 * declares the public chrome_extras module.
 *
 * @param array<int,string> $required_modules Resolved frontend modules.
 * @return string
 */
function get_primary_stylesheet_path( array $required_modules ): string {
	$needs_chrome_extras = in_array( 'chrome_extras', $required_modules, true );

	if ( ! $needs_chrome_extras && file_exists( CRECEWEB_LUMEN_DIR . '/assets/css/lumen-core.min.css' ) ) {
		return 'assets/css/lumen-core.min.css';
	}

	if ( file_exists( CRECEWEB_LUMEN_DIR . '/assets/css/lumen.min.css' ) ) {
		return 'assets/css/lumen.min.css';
	}

	return 'assets/css/lumen.css';
}

/**
 * Returns a minified production asset when it exists, with source fallback.
 *
 * This keeps public handles and conditional loading unchanged while allowing
 * readable source assets to remain available for maintenance and editor use.
 *
 * @param string $relative_path Path relative to the Theme root.
 * @return string
 */
function get_preferred_minified_asset_path( string $relative_path ): string {
	$minified_path = preg_replace( '/\.(css|js)$/', '.min.$1', $relative_path );

	if ( ! is_string( $minified_path ) || $minified_path === $relative_path ) {
		return $relative_path;
	}

	return file_exists( CRECEWEB_LUMEN_DIR . '/' . $minified_path ) ? $minified_path : $relative_path;
}

/**
 * Enqueues one local Theme stylesheet when it exists.
 *
 * @param string $handle        WordPress style handle.
 * @param string $relative_path Path relative to the Theme root.
 * @return void
 */
function enqueue_local_style( string $handle, string $relative_path ): void {
	$absolute_path = CRECEWEB_LUMEN_DIR . '/' . $relative_path;
	if ( ! file_exists( $absolute_path ) ) {
		return;
	}

	wp_enqueue_style(
		$handle,
		CRECEWEB_LUMEN_URI . '/' . $relative_path,
		array( 'creceweb-lumen' ),
		(string) filemtime( $absolute_path )
	);
}

/**
 * @return void
 */
function enqueue_styles(): void {
	$required_modules = get_required_frontend_modules();
	$relative_path    = get_primary_stylesheet_path( $required_modules );
	$absolute_path    = CRECEWEB_LUMEN_DIR . '/' . $relative_path;
	$style_deps       = array();
	$google_fonts_url = should_enqueue_remote_google_fonts() ? get_google_fonts_stylesheet_url() : '';

	if ( '' !== $google_fonts_url ) {
		wp_enqueue_style( 'creceweb-lumen-google-fonts', $google_fonts_url, array(), null );
		$style_deps[] = 'creceweb-lumen-google-fonts';
	}

	wp_enqueue_style( 'creceweb-lumen', CRECEWEB_LUMEN_URI . '/' . $relative_path, $style_deps, file_exists( $absolute_path ) ? (string) filemtime( $absolute_path ) : get_version() );

	$inter_font_css = get_inter_font_face_css();
	if ( '' !== $inter_font_css ) {
		wp_add_inline_style( 'creceweb-lumen', $inter_font_css );
	}

	if ( request_uses_blog_archive_styles() ) {
		enqueue_local_style( 'creceweb-lumen-blog-archive', get_preferred_minified_asset_path( 'assets/css/blog-archive.css' ) );
	}

	if ( request_uses_single_styles() ) {
		enqueue_local_style( 'creceweb-lumen-single', get_preferred_minified_asset_path( 'assets/css/single.css' ) );
	}

	if ( request_uses_comments_styles() ) {
		enqueue_local_style( 'creceweb-lumen-comments', get_preferred_minified_asset_path( 'assets/css/comments.css' ) );
	}

	if ( request_uses_hero_styles() ) {
		$hero_relative_path = get_preferred_minified_asset_path( 'assets/css/hero.css' );
		$hero_absolute_path = CRECEWEB_LUMEN_DIR . '/' . $hero_relative_path;
		if ( file_exists( $hero_absolute_path ) ) {
			wp_enqueue_style(
				'creceweb-lumen-hero',
				CRECEWEB_LUMEN_URI . '/' . $hero_relative_path,
				array( 'creceweb-lumen' ),
				(string) filemtime( $hero_absolute_path )
			);
		}
	}

	if ( in_array( 'sections', $required_modules, true ) ) {
		enqueue_local_style( 'creceweb-lumen-sections', get_preferred_minified_asset_path( 'assets/css/sections.css' ) );
	}

	if ( request_uses_header_scripts() ) {
		$navigation_relative_path = file_exists( CRECEWEB_LUMEN_DIR . '/assets/js/classic-navigation.min.js' )
			? 'assets/js/classic-navigation.min.js'
			: 'assets/js/classic-navigation.js';
		$navigation_absolute_path = CRECEWEB_LUMEN_DIR . '/' . $navigation_relative_path;
		if ( file_exists( $navigation_absolute_path ) ) {
			wp_enqueue_script(
				'creceweb-lumen-navigation',
				CRECEWEB_LUMEN_URI . '/' . $navigation_relative_path,
				array(),
				(string) filemtime( $navigation_absolute_path ),
				array(
					'strategy'  => 'defer',
					'in_footer' => false,
				)
			);
			wp_add_inline_script(
				'creceweb-lumen-navigation',
				'document.documentElement.classList.add("cw-navigation-js");',
				'before'
			);
			wp_localize_script(
				'creceweb-lumen-navigation',
				'crecewebLumenNavigation',
				array(
					/* translators: %s: Menu item label. */
					'openSubmenuLabel' => __( 'Abrir submenú de %s', 'creceweb-lumen' ),
					'unnamedItem'      => __( 'este elemento', 'creceweb-lumen' ),
					'mainMenuLabel'    => __( 'Menú principal', 'creceweb-lumen' ),
				)
			);
		}

		if ( in_array( 'header_metrics', $required_modules, true ) ) {
			$header_relative_path = get_preferred_minified_asset_path( 'assets/js/header-behavior.js' );
			$header_absolute_path = CRECEWEB_LUMEN_DIR . '/' . $header_relative_path;
			if ( file_exists( $header_absolute_path ) ) {
				wp_enqueue_script(
					'creceweb-lumen-header',
					CRECEWEB_LUMEN_URI . '/' . $header_relative_path,
					array(),
					(string) filemtime( $header_absolute_path ),
					true
				);
			}
		}
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_styles' );
