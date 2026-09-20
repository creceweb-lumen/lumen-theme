<?php
/**
 * Header and navigation Customizer controls.
 *
 * Registers top-bar, header, navigation and compact-menu settings.
 *
 * @package CreceWebLumen
 */

namespace CreceWeb\Lumen;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers Cabecera y navegación controls.
 *
 * @param \WP_Customize_Manager $wp_customize Manager.
 * @return void
 */
function register_header_navigation_controls( \WP_Customize_Manager $wp_customize ): void {
	// Diseño > Cabecera y navegación — barra superior.
	add_customizer_checkbox( $wp_customize, 'top_bar_enabled', 'creceweb_header_navigation', __( 'Mostrar Barra Superior', 'creceweb-lumen' ), 10, __( 'Se muestra solo si hay bloques agregados en Apariencia > Widgets > Barra Superior.', 'creceweb-lumen' ) );
	add_customizer_select( $wp_customize, 'top_bar_tone', 'creceweb_header_navigation', __( 'Color de fondo', 'creceweb-lumen' ), array( 'inherit' => __( 'Igual a la cabecera', 'creceweb-lumen' ), 'surface' => __( 'Claro', 'creceweb-lumen' ), 'primary' => __( 'Principal oscuro', 'creceweb-lumen' ), 'custom' => __( 'Personalizado', 'creceweb-lumen' ) ), 20, '', 'postMessage', __NAMESPACE__ . '\\is_top_bar_enabled_active' );
	add_customizer_select( $wp_customize, 'top_bar_width', 'creceweb_header_navigation', __( 'Ancho interno', 'creceweb-lumen' ), array( 'full' => __( 'Completo', 'creceweb-lumen' ), 'content' => __( 'Lectura', 'creceweb-lumen' ), 'wide' => __( 'Amplio', 'creceweb-lumen' ) ), 30, '', 'postMessage', __NAMESPACE__ . '\\is_top_bar_enabled_active' );
	add_customizer_select( $wp_customize, 'top_bar_alignment', 'creceweb_header_navigation', __( 'Alineación', 'creceweb-lumen' ), array( 'left' => __( 'Izquierda', 'creceweb-lumen' ), 'center' => __( 'Centro', 'creceweb-lumen' ), 'right' => __( 'Derecha', 'creceweb-lumen' ) ), 40, '', 'postMessage', __NAMESPACE__ . '\\is_top_bar_enabled_active' );
	add_customizer_range( $wp_customize, 'top_bar_padding', 'creceweb_header_navigation', __( 'Espaciado vertical', 'creceweb-lumen' ), 50, 6, 32, 1, 'px', '', __NAMESPACE__ . '\\is_top_bar_enabled_active' );
	add_customizer_color( $wp_customize, 'top_bar_background_color', 'creceweb_header_navigation', __( 'Fondo personalizado', 'creceweb-lumen' ), 21, __NAMESPACE__ . '\\is_top_bar_custom_tone_active' );
	add_customizer_color( $wp_customize, 'top_bar_text_color', 'creceweb_header_navigation', __( 'Texto personalizado', 'creceweb-lumen' ), 22, __NAMESPACE__ . '\\is_top_bar_custom_tone_active' );
	add_customizer_color( $wp_customize, 'top_bar_link_color', 'creceweb_header_navigation', __( 'Enlaces personalizados', 'creceweb-lumen' ), 23, __NAMESPACE__ . '\\is_top_bar_custom_tone_active' );

	// Diseño > Cabecera y navegación — estructura de cabecera.
	add_customizer_select( $wp_customize, 'header_width', 'creceweb_header_navigation', __( 'Ancho interno', 'creceweb-lumen' ), array( 'content' => __( 'Lectura', 'creceweb-lumen' ), 'wide' => __( 'Amplio', 'creceweb-lumen' ), 'max' => __( 'Máximo', 'creceweb-lumen' ), 'full' => __( 'Completo', 'creceweb-lumen' ) ), 10 );
	add_customizer_select( $wp_customize, 'header_behavior', 'creceweb_header_navigation', __( 'Comportamiento', 'creceweb-lumen' ), array( 'static' => __( 'Normal', 'creceweb-lumen' ), 'sticky' => __( 'Fijo al desplazarse', 'creceweb-lumen' ), 'fixed' => __( 'Fijo desde el inicio', 'creceweb-lumen' ) ), 20, __( 'Normal: la cabecera se desplaza junto con el contenido. Fijo al desplazarse: se fija al llegar arriba. Fijo desde el inicio: permanece visible arriba desde la carga.', 'creceweb-lumen' ) );
	add_customizer_select( $wp_customize, 'header_sticky_shadow', 'creceweb_header_navigation', __( 'Sombra al fijar', 'creceweb-lumen' ), array( 'none' => __( 'Sin sombra', 'creceweb-lumen' ), 'subtle' => __( 'Sutil', 'creceweb-lumen' ), 'strong' => __( 'Marcada', 'creceweb-lumen' ) ), 25, __( 'Se aplica cuando elegís una cabecera fija.', 'creceweb-lumen' ), 'postMessage', __NAMESPACE__ . '\\is_sticky_header_active' );
	add_customizer_select( $wp_customize, 'header_tone', 'creceweb_header_navigation', __( 'Tono', 'creceweb-lumen' ), array( 'surface' => __( 'Claro', 'creceweb-lumen' ), 'primary' => __( 'Principal oscuro', 'creceweb-lumen' ) ), 30 );
	add_customizer_range( $wp_customize, 'header_padding', 'creceweb_header_navigation', __( 'Espaciado vertical', 'creceweb-lumen' ), 40, 12, 56, 1, 'px' );
	add_customizer_select( $wp_customize, 'header_density', 'creceweb_header_navigation', __( 'Densidad predefinida', 'creceweb-lumen' ), array( 'compact' => __( 'Compacta', 'creceweb-lumen' ), 'normal' => __( 'Normal', 'creceweb-lumen' ), 'spacious' => __( 'Amplia', 'creceweb-lumen' ) ), 50, __( 'Ajusta el espaciado vertical seleccionado para una cabecera más compacta o más amplia.', 'creceweb-lumen' ) );
	add_customizer_select( $wp_customize, 'header_divider', 'creceweb_header_navigation', __( 'Separador inferior', 'creceweb-lumen' ), array( 'visible' => __( 'Visible', 'creceweb-lumen' ), 'hidden' => __( 'Oculto', 'creceweb-lumen' ) ), 60 );
	add_customizer_choice_cards( $wp_customize, 'header_alignment', 'creceweb_header_navigation', __( 'Distribución interna', 'creceweb-lumen' ), array( 'left' => __( 'Izquierda', 'creceweb-lumen' ), 'center' => __( 'Centrada', 'creceweb-lumen' ), 'space-between' => __( 'Separada', 'creceweb-lumen' ) ), 70, 'layout' );

	// Diseño > Cabecera y navegación — menú.
	add_customizer_choice_cards( $wp_customize, 'navigation_align', 'creceweb_header_navigation', __( 'Alineación del menú', 'creceweb-lumen' ), array( 'left' => __( 'Izquierda', 'creceweb-lumen' ), 'center' => __( 'Centro', 'creceweb-lumen' ), 'right' => __( 'Derecha', 'creceweb-lumen' ) ), 10, 'alignment' );
	add_customizer_select( $wp_customize, 'navigation_gap', 'creceweb_header_navigation', __( 'Separación entre enlaces', 'creceweb-lumen' ), array( 'compact' => __( 'Compacta', 'creceweb-lumen' ), 'normal' => __( 'Normal', 'creceweb-lumen' ), 'spacious' => __( 'Amplia', 'creceweb-lumen' ) ), 20 );
	add_customizer_range( $wp_customize, 'navigation_font_size', 'creceweb_header_navigation', __( 'Tamaño del menú', 'creceweb-lumen' ), 30, 12, 22, 1, 'px' );
	add_customizer_select( $wp_customize, 'navigation_weight', 'creceweb_header_navigation', __( 'Peso del menú', 'creceweb-lumen' ), array( '400' => '400', '500' => '500', '600' => '600', '650' => '650', '700' => '700' ), 40 );
	add_customizer_select( $wp_customize, 'navigation_transform', 'creceweb_header_navigation', __( 'Mayúsculas', 'creceweb-lumen' ), array( 'none' => __( 'Normal', 'creceweb-lumen' ), 'uppercase' => __( 'Mayúsculas', 'creceweb-lumen' ) ), 50 );
	add_customizer_select( $wp_customize, 'mobile_menu_style', 'creceweb_header_navigation', __( 'Menú móvil', 'creceweb-lumen' ), array( 'overlay' => __( 'Pantalla completa', 'creceweb-lumen' ), 'drawer' => __( 'Panel lateral', 'creceweb-lumen' ) ), 60 );
	add_customizer_select( $wp_customize, 'mobile_menu_breakpoint', 'creceweb_header_navigation', __( 'Activar diseño compacto', 'creceweb-lumen' ), array( '781' => __( 'Hasta 781 px', 'creceweb-lumen' ), '960' => __( 'Hasta 960 px', 'creceweb-lumen' ), '1024' => __( 'Hasta 1024 px', 'creceweb-lumen' ) ), 70, __( 'Define desde qué ancho se activa el menú móvil y las adaptaciones compactas de cabecera, contenido y footer.', 'creceweb-lumen' ), 'refresh' );


	add_customizer_range( $wp_customize, 'mobile_navigation_font_size', 'creceweb_header_navigation', __( 'Tamaño del menú en diseño compacto', 'creceweb-lumen' ), 60, 12, 22, 1, 'px' );

	register_customizer_color_group( $wp_customize, 'navigation', 'creceweb_header_navigation', 180 );
	add_customizer_range( $wp_customize, 'submenu_hover_background_opacity', 'creceweb_header_navigation', __( 'Submenú: opacidad del fondo hover', 'creceweb-lumen' ), 187, 0, 100, 1, '%', __( '100% usa el color sólido; 0% lo hace totalmente transparente. Solo se aplica cuando definís “Submenú: fondo hover”.', 'creceweb-lumen' ) );
}
