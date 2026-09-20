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
	$relative_path = 'assets/css/lumen.css';
	$absolute_path = CRECEWEB_LUMEN_DIR . '/' . $relative_path;
	wp_enqueue_style( 'creceweb-lumen', CRECEWEB_LUMEN_URI . '/' . $relative_path, array(), file_exists( $absolute_path ) ? (string) filemtime( $absolute_path ) : get_version() );

	if ( request_uses_blog_archive_styles() ) {
		enqueue_local_style( 'creceweb-lumen-blog-archive', 'assets/css/blog-archive.css' );
	}

	if ( request_uses_single_styles() ) {
		enqueue_local_style( 'creceweb-lumen-single', 'assets/css/single.css' );
	}

	if ( request_uses_comments_styles() ) {
		enqueue_local_style( 'creceweb-lumen-comments', 'assets/css/comments.css' );
	}

	if ( request_uses_hero_styles() ) {
		$hero_relative_path = 'assets/css/hero.css';
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

	$sections_relative_path = 'assets/css/sections.css';
	$sections_absolute_path = CRECEWEB_LUMEN_DIR . '/' . $sections_relative_path;
	if ( file_exists( $sections_absolute_path ) ) {
		wp_enqueue_style(
			'creceweb-lumen-sections',
			CRECEWEB_LUMEN_URI . '/' . $sections_relative_path,
			array( 'creceweb-lumen' ),
			(string) filemtime( $sections_absolute_path )
		);
	}

	if ( request_uses_header_scripts() ) {
		$navigation_relative_path = 'assets/js/classic-navigation.js';
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

		$header_relative_path = 'assets/js/header-behavior.js';
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

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_styles' );
