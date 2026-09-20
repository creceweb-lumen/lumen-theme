<?php
/**
 * Quick-start Customizer controls.
 *
 * Registers visual presets and detailed-mode controls.
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
	// Inicio rápido: presets y modo de profundidad del panel.
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
	add_customizer_checkbox( $wp_customize, 'show_advanced_controls', 'creceweb_quick_start', __( 'Mostrar ajustes detallados', 'creceweb-lumen' ), 20, __( 'Revela tamaños finos, estados especiales y preferencias técnicas dentro de cada área, sin ocultar secciones del menú.', 'creceweb-lumen' ), null, 'postMessage' );
}
