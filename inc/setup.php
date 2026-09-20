<?php
/**
 * Theme setup and native WordPress supports.
 *
 * @package CreceWebLumen
 */

namespace CreceWeb\Lumen;

/**
 * Registers the classic-theme capabilities and Gutenberg compatibility.
 *
 * @return void
 */
function setup(): void {
	load_theme_textdomain( 'creceweb-lumen', CRECEWEB_LUMEN_DIR . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'custom-line-height' );
	add_theme_support( 'custom-spacing' );
	add_theme_support( 'appearance-tools' );
	add_theme_support( 'border' );
	add_theme_support( 'link-color' );
	add_theme_support( 'custom-logo', array( 'height' => 120, 'width' => 420, 'flex-height' => true, 'flex-width' => true ) );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style' ) );

	global $content_width;
	if ( ! isset( $content_width ) ) {
		$content_width = 1120;
	}

	add_editor_style( array( 'assets/css/editor.css', 'assets/css/sections.css', 'assets/css/sections-editor.css' ) );
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\setup' );
