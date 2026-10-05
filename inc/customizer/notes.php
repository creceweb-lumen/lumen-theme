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
	add_customizer_note( $wp_customize, 'creceweb_header_structure_note', 'creceweb_header_navigation', __( 'General', 'creceweb-lumen' ), __( 'Configurá el comportamiento, tono, espaciado y distribución general.', 'creceweb-lumen' ), 5 );
	add_customizer_note( $wp_customize, 'creceweb_header_menu_note', 'creceweb_navigation', __( 'General', 'creceweb-lumen' ), __( 'Definí la alineación, tipografía y cómo se adapta el menú en celular o tablet.', 'creceweb-lumen' ), 5 );
	add_customizer_note( $wp_customize, 'creceweb_header_topbar_note', 'creceweb_top_bar', __( 'General', 'creceweb-lumen' ), __( 'Se muestra únicamente cuando agregás contenido en Apariencia > Widgets > Barra Superior.', 'creceweb-lumen' ), 5 );
	add_customizer_note( $wp_customize, 'creceweb_footer_mobile_note', 'creceweb_footer', __( '4. Pie de página en celular', 'creceweb-lumen' ), __( 'Estas opciones se aplican solo en pantallas de hasta 781 px.', 'creceweb-lumen' ), 300, 'step' );
	add_customizer_note( $wp_customize, 'creceweb_responsive_note', 'creceweb_responsive', __( 'Espaciado lateral', 'creceweb-lumen' ), __( 'Estos valores mantienen alineados cabecera, contenido y pie. El logo y el menú responsive se configuran en sus secciones correspondientes.', 'creceweb-lumen' ), 5 );
	add_customizer_note( $wp_customize, 'creceweb_accessibility_note', 'creceweb_accessibility', __( 'Preferencias de accesibilidad', 'creceweb-lumen' ), __( 'Configurá el foco visible y el movimiento global. “Respetar preferencia del sistema” mantiene la elección de cada visitante.', 'creceweb-lumen' ), 5 );
}
