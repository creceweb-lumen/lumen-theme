<?php
/**
 * Customizer module loader.
 *
 * The guided Customizer is split by functional area under /inc/customizer.
 * This loader remains as the stable entry point used by functions.php.
 *
 * @package CreceWebLumen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$creceweb_lumen_customizer_modules = array(
	'inc/customizer/helpers.php',
	'inc/customizer/colors.php',
	'inc/customizer/sections.php',
	'inc/customizer/quick-start.php',
	'inc/customizer/branding.php',
	'inc/customizer/global-style.php',
	'inc/customizer/header-navigation.php',
	'inc/customizer/screen-layout.php',
	'inc/customizer/content-blog.php',
	'inc/customizer/footer.php',
	'inc/customizer/social.php',
	'inc/customizer/responsive.php',
	'inc/customizer/floating-action.php',
	'inc/customizer/accessibility.php',
	'inc/customizer/notes.php',
	'inc/customizer/finalize.php',
	'inc/customizer/assets.php',
	'inc/customizer/register.php',
);

foreach ( $creceweb_lumen_customizer_modules as $creceweb_lumen_customizer_module ) {
	$creceweb_lumen_customizer_path = CRECEWEB_LUMEN_DIR . '/' . $creceweb_lumen_customizer_module;

	if ( file_exists( $creceweb_lumen_customizer_path ) ) {
		require_once $creceweb_lumen_customizer_path;
	}
}
