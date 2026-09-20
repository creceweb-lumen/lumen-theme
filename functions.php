<?php
/**
 * CreceWeb Lumen bootstrap.
 *
 * @package CreceWebLumen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Public theme identity.
 */
define( 'CRECEWEB_LUMEN_VERSION', '1.4.109' );
define( 'CRECEWEB_LUMEN_BRIDGE_API_VERSION', '1.6.0' );
define( 'CRECEWEB_LUMEN_MINIMUM_PRO_VERSION', '1.9.17' );
define( 'CRECEWEB_LUMEN_DIR', get_template_directory() );
define( 'CRECEWEB_LUMEN_URI', get_template_directory_uri() );

$creceweb_lumen_includes = array(
	'inc/helpers.php',
	'inc/customization.php',
	'inc/pro-bridge.php',
	'inc/extension-bridge.php',
	'inc/appearance.php',
	'inc/theme-details.php',
	'inc/navigation.php',
	'inc/widgets.php',
	'inc/customizer-return.php',
	'inc/customizer.php',
	'inc/setup.php',
	'inc/enqueue.php',
	'inc/editor.php',
	'inc/block-library.php',
	'inc/accessibility.php',
	'inc/integrations.php',
);

foreach ( $creceweb_lumen_includes as $creceweb_lumen_file ) {
	$creceweb_lumen_path = CRECEWEB_LUMEN_DIR . '/' . $creceweb_lumen_file;

	if ( file_exists( $creceweb_lumen_path ) ) {
		require_once $creceweb_lumen_path;
	}
}
