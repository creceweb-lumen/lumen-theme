<?php
/**
 * Branding Customizer controls.
 *
 * Keeps native site identity controls and Lumen-specific display settings together.
 *
 * @package CreceWebLumen
 */

namespace CreceWeb\Lumen;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers Marca del sitio controls.
 *
 * @param \WP_Customize_Manager $wp_customize Manager.
 * @return void
 */
function register_branding_controls( \WP_Customize_Manager $wp_customize ): void {
	// Keep native site identity controls as the source of truth.
	$native_identity_priorities = array( 'custom_logo' => 10, 'blogname' => 30, 'blogdescription' => 50, 'display_header_text' => 60, 'site_icon' => 90 );
	foreach ( $native_identity_priorities as $control_id => $priority ) {
		$control = $wp_customize->get_control( $control_id );
		if ( $control ) {
			$control->priority = $priority;
		}
	}

	/* CreceWeb provides separate title and tagline toggles below. */
	$wp_customize->remove_control( 'display_header_text' );
	if ( $wp_customize->get_setting( 'custom_logo' ) ) {
		$wp_customize->get_setting( 'custom_logo' )->transport = 'refresh';
	}
	foreach ( array( 'blogname', 'blogdescription' ) as $identity_setting ) {
		$setting = $wp_customize->get_setting( $identity_setting );
		if ( $setting ) {
			$setting->transport = 'postMessage';
		}
	}

	$logo_width_setting = add_customizer_setting( $wp_customize, 'logo_width' );
	$wp_customize->add_control(
		new Logo_Width_Control(
			$wp_customize,
			$logo_width_setting,
			array(
				'label'       => __( 'Ancho del logo', 'creceweb-lumen' ),
				'description' => __( 'Se aplica en escritorio, tablet y celular. Podés crear una excepción desde Celular y tablet.', 'creceweb-lumen' ),
				'section'     => 'title_tagline',
				'priority'    => 12,
				'unit'        => 'px',
				'input_attrs' => array( 'min' => 24, 'max' => 320, 'step' => 1 ),
			)
		)
	);
	add_customizer_range( $wp_customize, 'site_title_size', 'title_tagline', __( 'Tamaño del título', 'creceweb-lumen' ), 32, 16, 42, 1, 'px' );
	add_customizer_checkbox( $wp_customize, 'hide_site_title', 'title_tagline', __( 'Esconder título del sitio', 'creceweb-lumen' ), 33, __( 'Oculta solo el título del sitio en la cabecera. No afecta el logo ni la descripción corta.', 'creceweb-lumen' ), null, 'postMessage' );
	add_customizer_range( $wp_customize, 'site_tagline_size', 'title_tagline', __( 'Tamaño de la descripción corta', 'creceweb-lumen' ), 52, 10, 24, 1, 'px' );
	add_customizer_checkbox( $wp_customize, 'hide_site_tagline', 'title_tagline', __( 'Esconder descripción corta', 'creceweb-lumen' ), 53, __( 'Oculta la descripción corta en la cabecera y el pie. No afecta el logo ni el título.', 'creceweb-lumen' ), null, 'postMessage' );

	add_customizer_select( $wp_customize, 'mobile_logo_width_mode', 'title_tagline', __( 'Logo en diseño compacto', 'creceweb-lumen' ), array( 'inherit' => __( 'Usar ancho de Marca del sitio', 'creceweb-lumen' ), 'custom' => __( 'Usar un ancho específico', 'creceweb-lumen' ) ), 30, __( 'Por defecto, el logo de tablet y celular usa el ancho definido en Diseño > Marca del sitio.', 'creceweb-lumen' ) );
	add_customizer_range( $wp_customize, 'mobile_logo_width', 'title_tagline', __( 'Ancho específico de logo', 'creceweb-lumen' ), 31, 24, 240, 1, 'px', __( 'Solo se aplica cuando elegís un ancho específico para el diseño compacto.', 'creceweb-lumen' ), __NAMESPACE__ . '\\is_mobile_logo_custom_width_active' );
	add_customizer_range( $wp_customize, 'mobile_site_title_size', 'title_tagline', __( 'Título del sitio en diseño compacto', 'creceweb-lumen' ), 40, 12, 36, 1, 'px' );
	add_customizer_range( $wp_customize, 'mobile_site_tagline_size', 'title_tagline', __( 'Descripción corta en diseño compacto', 'creceweb-lumen' ), 50, 10, 20, 1, 'px' );
}
