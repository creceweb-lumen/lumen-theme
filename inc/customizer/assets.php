<?php
/**
 * Customizer assets.
 *
 * Loads preview and controls assets only in native Customizer contexts.
 *
 * @package CreceWebLumen
 */

namespace CreceWeb\Lumen;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueues the preview bridge for postMessage controls.
 *
 * @return void
 */
function enqueue_customizer_preview_script(): void {
	$relative_path = 'assets/js/customizer-preview.js';
	$absolute_path = CRECEWEB_LUMEN_DIR . '/' . $relative_path;
	wp_enqueue_script(
		'creceweb-lumen-customizer-preview',
		CRECEWEB_LUMEN_URI . '/' . $relative_path,
		array( 'customize-preview' ),
		file_exists( $absolute_path ) ? (string) filemtime( $absolute_path ) : get_version(),
		true
	);
	wp_localize_script(
		'creceweb-lumen-customizer-preview',
		'crecewebLumenCustomizerPreview',
		array(
			'settingPrefix' => CUSTOMIZATION_OPTION . '[',
		)
	);
}
add_action( 'customize_preview_init', __NAMESPACE__ . '\\enqueue_customizer_preview_script' );

/**
 * Enqueues controls-only Customizer assets.
 *
 * @return void
 */
function enqueue_customizer_control_assets(): void {
	$style_path = CRECEWEB_LUMEN_DIR . '/assets/css/customizer-controls.css';
	wp_enqueue_style(
		'creceweb-lumen-customizer-controls',
		CRECEWEB_LUMEN_URI . '/assets/css/customizer-controls.css',
		array( 'dashicons' ),
		file_exists( $style_path ) ? (string) filemtime( $style_path ) : get_version()
	);

	$script_path = CRECEWEB_LUMEN_DIR . '/assets/js/customizer-controls.js';
	wp_enqueue_script(
		'creceweb-lumen-customizer-controls',
		CRECEWEB_LUMEN_URI . '/assets/js/customizer-controls.js',
		array( 'customize-controls', 'jquery' ),
		file_exists( $script_path ) ? (string) filemtime( $script_path ) : get_version(),
		true
	);
	wp_localize_script(
		'creceweb-lumen-customizer-controls',
		'crecewebCustomizerConfig',
		array(
			'settingPrefix'             => CUSTOMIZATION_OPTION . '[',
			'presets'                   => get_design_presets(),
			'safeCustomizerReturnUrl' => admin_url( 'themes.php' ),
			'strings'                 => array(
				'customConfiguration' => __( 'Configuración visual personalizada. Tus valores actuales se mantienen hasta que elijas un estilo.', 'creceweb-lumen' ),
				'confirmPreset'       => __( 'Este estilo reemplazará los ajustes visuales globales de CreceWeb. No modificará contenido, páginas, menús ni widgets. ¿Querés aplicarlo?', 'creceweb-lumen' ),
			),
		)
	);
}
add_action( 'customize_controls_enqueue_scripts', __NAMESPACE__ . '\\enqueue_customizer_control_assets' );
