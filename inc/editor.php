<?php
/**
 * Editor-only integration points.
 *
 * @package CreceWebLumen
 */

namespace CreceWeb\Lumen;


/**
 * Loads the selected remote Google Fonts stylesheet in block-editor contexts.
 *
 * @return void
 */
function enqueue_editor_google_fonts(): void {
	if ( ! is_admin() || ! should_enqueue_remote_google_fonts() ) {
		return;
	}

	$url = get_google_fonts_stylesheet_url();
	if ( '' !== $url ) {
		wp_enqueue_style( 'creceweb-lumen-google-fonts', $url, array(), null );
	}
}
add_action( 'enqueue_block_assets', __NAMESPACE__ . '\enqueue_editor_google_fonts', 5 );

/**
 * Adds a body class that helps blocks identify the recommended Lumen environment.
 *
 * @param string $classes Existing editor body classes.
 * @return string
 */
function add_editor_body_class( string $classes ): string {
	return $classes . ' cw-editor';
}
add_filter( 'admin_body_class', __NAMESPACE__ . '\\add_editor_body_class' );

/**
 * Mirrors Appearance settings inside post and block-editor previews.
 *
 * @param array<string, mixed> $settings Editor settings.
 * @param mixed                $context  Editor context.
 * @return array<string, mixed>
 */
function add_editor_customization_styles( array $settings, $context ): array {
	if ( ! isset( $settings['styles'] ) || ! is_array( $settings['styles'] ) ) {
		$settings['styles'] = array();
	}

	$settings['styles'][] = array(
		'css' => get_inter_font_face_css() . get_customization_css(),
	);

	return $settings;
}
add_filter( 'block_editor_settings_all', __NAMESPACE__ . '\\add_editor_customization_styles', 100, 2 );
