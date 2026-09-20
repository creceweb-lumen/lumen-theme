<?php
/**
 * Social Icons presentation controls.
 *
 * The theme owns placement and presentation only. Social profiles and URLs are
 * stored as native WordPress block/widget content in the dedicated widget areas.
 *
 * @package CreceWebLumen
 */

namespace CreceWeb\Lumen;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns whether the header social area is enabled in the Customizer.
 *
 * @param \WP_Customize_Control $control Current control.
 * @return bool
 */
function is_social_header_active( \WP_Customize_Control $control ): bool {
	$setting = $control->manager->get_setting( get_customizer_setting_id( 'social_header_position' ) );
	return $setting && 'hidden' !== (string) $setting->value();
}

/**
 * Returns whether the footer social area is enabled in the Customizer.
 *
 * @param \WP_Customize_Control $control Current control.
 * @return bool
 */
function is_social_footer_active( \WP_Customize_Control $control ): bool {
	$setting = $control->manager->get_setting( get_customizer_setting_id( 'social_footer_position' ) );
	return $setting && 'hidden' !== (string) $setting->value();
}

/**
 * Registers social presentation controls.
 *
 * @param \WP_Customize_Manager $wp_customize Manager.
 * @return void
 */
function register_social_controls( \WP_Customize_Manager $wp_customize ): void {
	add_customizer_note(
		$wp_customize,
		'creceweb_social_source_note',
		'creceweb_social',
		__( 'Contenido de las redes', 'creceweb-lumen' ),
		__( 'Las redes y sus URLs permanecen en el bloque nativo “Iconos sociales” de WordPress. Elegí si querés compartir un mismo bloque entre cabecera y pie o administrar bloques independientes.', 'creceweb-lumen' ),
		5,
		'step'
	);
	add_customizer_select(
		$wp_customize,
		'social_content_mode',
		'creceweb_social',
		__( 'Contenido de cabecera y pie', 'creceweb-lumen' ),
		array(
			'shared'   => __( 'Un solo bloque compartido', 'creceweb-lumen' ),
			'separate' => __( 'Bloques independientes', 'creceweb-lumen' ),
		),
		10,
		__( 'Compartido: el área “Redes sociales · Pie” alimenta ambas ubicaciones; si está vacía, se usa “Redes sociales · Cabecera”. Independiente: cada ubicación usa exclusivamente su propia área.', 'creceweb-lumen' ),
		'refresh'
	);

	add_customizer_note( $wp_customize, 'creceweb_social_header_note', 'creceweb_social', __( '1. Cabecera', 'creceweb-lumen' ), __( 'Elegí dónde aparece el área social de cabecera.', 'creceweb-lumen' ), 20, 'step' );
	add_customizer_select(
		$wp_customize,
		'social_header_position',
		'creceweb_social',
		__( 'Ubicación en la cabecera', 'creceweb-lumen' ),
		array(
			'hidden'            => __( 'Oculta', 'creceweb-lumen' ),
			'before_navigation' => __( 'Antes del menú', 'creceweb-lumen' ),
			'after_navigation'  => __( 'Después del menú', 'creceweb-lumen' ),
		),
		30,
		__( 'La ubicación se aplica al área “Redes sociales · Cabecera”.', 'creceweb-lumen' ),
		'refresh'
	);
	add_customizer_checkbox( $wp_customize, 'social_header_mobile_enabled', 'creceweb_social', __( 'Mostrar también en diseño compacto', 'creceweb-lumen' ), 40, __( 'Si queda desactivado, los iconos de cabecera se ocultan al activarse el menú compacto.', 'creceweb-lumen' ), __NAMESPACE__ . '\\is_social_header_active', 'refresh' );
	add_customizer_color( $wp_customize, 'social_header_color', 'creceweb_social', __( 'Color de iconos en cabecera', 'creceweb-lumen' ), 50, __NAMESPACE__ . '\\is_social_header_active', __( 'Opcional. Si queda vacío, se respetan los colores definidos por el bloque Iconos sociales.', 'creceweb-lumen' ), 'refresh' );
	add_customizer_color( $wp_customize, 'social_header_hover_color', 'creceweb_social', __( 'Color hover en cabecera', 'creceweb-lumen' ), 60, __NAMESPACE__ . '\\is_social_header_active', __( 'Opcional. Se aplica al pasar el puntero o enfocar un enlace social.', 'creceweb-lumen' ), 'refresh' );

	add_customizer_note( $wp_customize, 'creceweb_social_footer_note', 'creceweb_social', __( '2. Pie de página', 'creceweb-lumen' ), __( 'Elegí dónde aparece el área social del pie. El contenido puede ser compartido con la cabecera o independiente según la opción anterior.', 'creceweb-lumen' ), 80, 'step' );
	add_customizer_select(
		$wp_customize,
		'social_footer_position',
		'creceweb_social',
		__( 'Ubicación en el pie', 'creceweb-lumen' ),
		array(
			'hidden'         => __( 'Oculta', 'creceweb-lumen' ),
			'before_widgets' => __( 'Antes de los widgets', 'creceweb-lumen' ),
			'after_widgets'  => __( 'Después de los widgets', 'creceweb-lumen' ),
		),
		90,
		__( 'La ubicación se aplica al área “Redes sociales · Pie”.', 'creceweb-lumen' ),
		'refresh'
	);
	add_customizer_select(
		$wp_customize,
		'social_footer_alignment',
		'creceweb_social',
		__( 'Alineación en el pie', 'creceweb-lumen' ),
		array(
			'left'   => __( 'Izquierda', 'creceweb-lumen' ),
			'center' => __( 'Centro', 'creceweb-lumen' ),
			'right'  => __( 'Derecha', 'creceweb-lumen' ),
		),
		100,
		'',
		'refresh',
		__NAMESPACE__ . '\\is_social_footer_active'
	);
	add_customizer_color( $wp_customize, 'social_footer_color', 'creceweb_social', __( 'Color de iconos en el pie', 'creceweb-lumen' ), 110, __NAMESPACE__ . '\\is_social_footer_active', __( 'Opcional. Si queda vacío, se respetan los colores definidos por el bloque Iconos sociales.', 'creceweb-lumen' ), 'refresh' );
	add_customizer_color( $wp_customize, 'social_footer_hover_color', 'creceweb_social', __( 'Color hover en el pie', 'creceweb-lumen' ), 120, __NAMESPACE__ . '\\is_social_footer_active', __( 'Opcional. Se aplica al pasar el puntero o enfocar un enlace social.', 'creceweb-lumen' ), 'refresh' );

	add_customizer_note( $wp_customize, 'creceweb_social_common_note', 'creceweb_social', __( '3. Apariencia común', 'creceweb-lumen' ), __( 'Tamaño real de los iconos y separación compartidos por las áreas sociales de cabecera y pie.', 'creceweb-lumen' ), 140, 'step' );
	add_customizer_range( $wp_customize, 'social_icon_size', 'creceweb_social', __( 'Tamaño de iconos', 'creceweb-lumen' ), 150, 16, 40, 1, 'px' );
	add_customizer_range( $wp_customize, 'social_icon_gap', 'creceweb_social', __( 'Separación entre iconos', 'creceweb-lumen' ), 160, 4, 24, 1, 'px' );
}
