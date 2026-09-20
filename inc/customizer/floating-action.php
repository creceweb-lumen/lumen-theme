<?php
/**
 * Floating-action Customizer controls.
 *
 * Registers the back-to-top control group.
 *
 * @package CreceWebLumen
 */

namespace CreceWeb\Lumen;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers Botón Volver arriba controls.
 *
 * @param \WP_Customize_Manager $wp_customize Manager.
 * @return void
 */
function register_floating_action_controls( \WP_Customize_Manager $wp_customize ): void {
	// Diseño > Botón Volver arriba.
	$choices = array(
		'none'        => __( 'No mostrar botón', 'creceweb-lumen' ),
		'back_to_top' => __( 'Volver arriba', 'creceweb-lumen' ),
	);

	add_customizer_select( $wp_customize, 'floating_action', 'creceweb_floating_action', __( 'Estado del botón', 'creceweb-lumen' ), $choices, 10, __( 'CreceWeb Lumen administra únicamente el botón Volver arriba. Si otro plugin agrega una acción flotante en la misma esquina, podés desactivar este botón para evitar superposiciones.', 'creceweb-lumen' ), 'refresh' );
	add_customizer_color( $wp_customize, 'floating_action_background_color', 'creceweb_floating_action', __( 'Color de fondo', 'creceweb-lumen' ), 15, __NAMESPACE__ . '\\is_floating_action_active' );
	add_customizer_color( $wp_customize, 'floating_action_icon_color', 'creceweb_floating_action', __( 'Color del ícono', 'creceweb-lumen' ), 16, __NAMESPACE__ . '\\is_floating_action_active' );
	add_customizer_range( $wp_customize, 'floating_action_size', 'creceweb_floating_action', __( 'Tamaño del botón', 'creceweb-lumen' ), 17, 40, 80, 1, 'px', __( 'El ícono se ajusta proporcionalmente. Para interacción táctil se recomiendan 44 px o más.', 'creceweb-lumen' ), __NAMESPACE__ . '\\is_floating_action_active' );
}
