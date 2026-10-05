<?php
/**
 * Quick-start Customizer controls.
 *
 * Registers visual presets.
 *
 * @package CreceWebLumen
 */

namespace CreceWeb\Lumen;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the Inicio rápido controls.
 *
 * @param \WP_Customize_Manager $wp_customize Manager.
 * @return void
 */
function register_quick_start_controls( \WP_Customize_Manager $wp_customize ): void {
	// Inicio rápido: preset visual.
	$preset_setting = add_customizer_setting( $wp_customize, 'design_preset', 'refresh' );
	$wp_customize->add_control(
		new Design_Preset_Control(
			$wp_customize,
			$preset_setting,
			array(
				'label'       => __( 'Elegí un estilo inicial', 'creceweb-lumen' ),
				'description' => __( 'Elegí una base visual para el sitio. Podés ajustar cada valor después sin cambiar contenido, menús ni widgets.', 'creceweb-lumen' ),
				'section'     => 'creceweb_quick_start',
				'priority'    => 10,
			)
		)
	);
	// Compatibilidad: el setting histórico permanece registrado, pero G2.2 ya no lo usa para ocultar controles.
	add_customizer_setting( $wp_customize, 'show_advanced_controls', 'postMessage' );
}
