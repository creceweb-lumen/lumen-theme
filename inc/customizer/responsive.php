<?php
/**
 * Responsive Customizer controls.
 *
 * Registers device-wide spacing settings.
 *
 * @package CreceWebLumen
 */

namespace CreceWeb\Lumen;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers Celular y tablet controls.
 *
 * @param \WP_Customize_Manager $wp_customize Manager.
 * @return void
 */
function register_responsive_controls( \WP_Customize_Manager $wp_customize ): void {
	// Diseño > Celular y tablet — márgenes generales.
	add_customizer_range( $wp_customize, 'tablet_gutter', 'creceweb_responsive', __( 'Padding lateral en tablet', 'creceweb-lumen' ), 10, 20, 64, 2, 'px', __( 'Se aplica a cabecera, contenido, footer y widgets entre 782 y 1024 px.', 'creceweb-lumen' ) );
	add_customizer_range( $wp_customize, 'mobile_gutter', 'creceweb_responsive', __( 'Padding lateral en diseño compacto', 'creceweb-lumen' ), 20, 12, 40, 2, 'px', __( 'Se aplica a cabecera, contenido, footer y widgets por debajo del ancho definido en Navegación.', 'creceweb-lumen' ) );
}
