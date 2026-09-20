<?php
/**
 * Editor-only integration points.
 *
 * @package CreceWebLumen
 */

namespace CreceWeb\Lumen;

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
		'css' => get_customization_css(),
	);

	return $settings;
}
add_filter( 'block_editor_settings_all', __NAMESPACE__ . '\\add_editor_customization_styles', 100, 2 );
