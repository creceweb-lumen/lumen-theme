<?php
/**
 * Accessibility Customizer controls.
 *
 * Registers focus and motion preferences.
 *
 * @package CreceWebLumen
 */

namespace CreceWeb\Lumen;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers Accesibilidad controls.
 *
 * @param \WP_Customize_Manager $wp_customize Manager.
 * @return void
 */
function register_accessibility_controls( \WP_Customize_Manager $wp_customize ): void {
	// Diseño > Accesibilidad.
	add_customizer_color( $wp_customize, 'focus_color', 'creceweb_accessibility', __( 'Color de foco visible', 'creceweb-lumen' ), 10 );
	add_customizer_select( $wp_customize, 'motion_preference', 'creceweb_accessibility', __( 'Movimiento', 'creceweb-lumen' ), array( 'system' => __( 'Respetar preferencia del sistema', 'creceweb-lumen' ), 'reduce' => __( 'Reducir siempre', 'creceweb-lumen' ) ), 20 );
}
