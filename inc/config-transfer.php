<?php
/**
 * Portable Theme configuration contribution for Lumen Lite tools.
 *
 * @package CreceWebLumen
 */

namespace CreceWeb\Lumen;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers Theme-owned portable settings without depending on Lumen Lite.
 *
 * @param array<string,array<string,mixed>> $sections Existing transfer sections.
 * @return array<string,array<string,mixed>>
 */
function register_config_transfer_section( array $sections ): array {
	$sections['theme'] = array(
		'label'       => __( 'Lumen Theme', 'creceweb-lumen' ),
		'owner'       => 'theme',
		'schema'      => 1,
		'version'     => defined( 'CRECEWEB_LUMEN_VERSION' ) ? (string) CRECEWEB_LUMEN_VERSION : '',
		'description' => __( 'Ajustes del Personalizador para diseño, estructura, color, tipografía, cabecera, blog y pie de página.', 'creceweb-lumen' ),
		'export'      => __NAMESPACE__ . '\\export_portable_config',
		'import'      => __NAMESPACE__ . '\\import_portable_config',
	);

	return $sections;
}
add_filter( 'creceweb_lumen_config_transfer_sections', __NAMESPACE__ . '\\register_config_transfer_section' );

/**
 * @return array<string,string>
 */
function export_portable_config(): array {
	return get_customizations();
}

/**
 * @param array<string,mixed> $data Imported Theme section.
 * @return true|\WP_Error
 */
function import_portable_config( array $data ) {
	unset( $data[ CUSTOMIZATION_INTERNAL_KEY ] );
	$clean = sanitize_customizations( $data );
	if ( empty( $clean ) ) {
		return new \WP_Error( 'lumen_theme_config_empty', __( 'La sección del tema no contenía ajustes válidos de Lumen.', 'creceweb-lumen' ) );
	}

	update_option( CUSTOMIZATION_OPTION, $clean, true );

	return true;
}
