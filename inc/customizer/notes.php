<?php
/**
 * Customizer section notes.
 *
 * Adds explanatory notes that keep guided sections readable.
 *
 * @package CreceWebLumen
 */

namespace CreceWeb\Lumen;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers explanatory notes for grouped Customizer sections.
 *
 * @param \WP_Customize_Manager $wp_customize Manager.
 * @return void
 */
function register_customizer_section_notes( \WP_Customize_Manager $wp_customize ): void {
	// Grouping notes keep the merged sections readable for non-technical users.
	add_customizer_note( $wp_customize, 'creceweb_global_style_dimensions_note', 'creceweb_global_style', __( 'Espaciado y ancho', 'creceweb-lumen' ), __( 'Definí primero la lectura, las secciones amplias y el aire entre bloques.', 'creceweb-lumen' ), 5 );
	add_customizer_note( $wp_customize, 'creceweb_global_style_type_note', 'creceweb_global_style', __( 'Tipografía', 'creceweb-lumen' ), __( 'Elegí familias y escala globales para mantener una lectura consistente.', 'creceweb-lumen' ), 75 );
	add_customizer_note( $wp_customize, 'creceweb_global_style_controls_note', 'creceweb_global_style', __( 'Botones y formularios', 'creceweb-lumen' ), __( 'Ajustes visuales compartidos por botones, campos y enlaces.', 'creceweb-lumen' ), 105 );
	add_customizer_note( $wp_customize, 'creceweb_header_structure_note', 'creceweb_header_navigation', __( 'Estructura de cabecera', 'creceweb-lumen' ), __( 'Configurá el comportamiento, tono, espaciado y distribución general.', 'creceweb-lumen' ), 5 );
	add_customizer_note( $wp_customize, 'creceweb_header_menu_note', 'creceweb_header_navigation', __( 'Menú de escritorio y diseño compacto', 'creceweb-lumen' ), __( 'Definí la alineación del menú y cómo se adapta en celular o tablet.', 'creceweb-lumen' ), 70 );
	add_customizer_note( $wp_customize, 'creceweb_header_topbar_note', 'creceweb_header_navigation', __( 'Barra superior', 'creceweb-lumen' ), __( 'Se muestra únicamente cuando agregás contenido en Apariencia > Widgets > Barra Superior.', 'creceweb-lumen' ), 110 );
	add_customizer_note( $wp_customize, 'creceweb_footer_mobile_note', 'creceweb_footer', __( '4. Pie de página en celular', 'creceweb-lumen' ), __( 'Estas opciones se aplican solo en pantallas de hasta 781 px.', 'creceweb-lumen' ), 300, 'step' );
	add_customizer_note( $wp_customize, 'creceweb_responsive_note', 'creceweb_responsive', __( 'Márgenes por dispositivo', 'creceweb-lumen' ), __( 'Estos valores mantienen alineados cabecera, contenido y pie. Los overrides de logo y menú están en sus secciones correspondientes.', 'creceweb-lumen' ), 5 );
	add_customizer_note( $wp_customize, 'creceweb_accessibility_note', 'creceweb_accessibility', __( 'Ajustes detallados', 'creceweb-lumen' ), __( 'Activá “Mostrar ajustes detallados” en Inicio rápido para modificar foco visible y movimiento.', 'creceweb-lumen' ), 5 );
}
