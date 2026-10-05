<?php
/**
 * Footer Customizer controls.
 *
 * Registers widget layout, footer presentation, copyright and compact-footer settings.
 *
 * @package CreceWebLumen
 */

namespace CreceWeb\Lumen;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers Pie de página controls.
 *
 * @param \WP_Customize_Manager $wp_customize Manager.
 * @return void
 */
function register_footer_controls( \WP_Customize_Manager $wp_customize ): void {
	// Diseño > Pie de página. El contenido se construye solo con las áreas de widgets.
	add_customizer_note( $wp_customize, 'creceweb_footer_widgets_note', 'creceweb_footer', __( '1. Widgets del pie', 'creceweb-lumen' ), __( 'Armá el pie desde Apariencia > Widgets. La fila completa sirve para logo, navegación, datos de contacto o una llamada a la acción; las columnas se usan para información secundaria.', 'creceweb-lumen' ), 10, 'step' );
	add_customizer_select( $wp_customize, 'footer_layout_preset', 'creceweb_footer', __( 'Diseño del pie', 'creceweb-lumen' ), array( 'classic' => __( 'Clásico', 'creceweb-lumen' ), 'editorial' => __( 'Editorial en columnas', 'creceweb-lumen' ) ), 15, __( 'El diseño Editorial da más ancho a la primera columna. Sus tres primeras columnas mantienen contenido automático hasta que agregás widgets en esa misma columna; las demás no se modifican.', 'creceweb-lumen' ) );
	add_customizer_choice_cards( $wp_customize, 'footer_widget_columns', 'creceweb_footer', __( 'Columnas de widgets', 'creceweb-lumen' ), array( 'auto' => __( 'Automáticas', 'creceweb-lumen' ), '1' => __( '1 columna', 'creceweb-lumen' ), '2' => __( '2 columnas', 'creceweb-lumen' ), '3' => __( '3 columnas', 'creceweb-lumen' ), '4' => __( '4 columnas', 'creceweb-lumen' ), '5' => __( '5 columnas', 'creceweb-lumen' ) ), 20, 'columns', __( 'Define la distribución de las áreas Widget del pie de página 1 a 5 que tengan contenido.', 'creceweb-lumen' ) );
	add_customizer_range( $wp_customize, 'footer_widget_gap', 'creceweb_footer', __( 'Separación entre widgets', 'creceweb-lumen' ), 30, 12, 72, 2, 'px' );

	add_customizer_note( $wp_customize, 'creceweb_footer_appearance_note', 'creceweb_footer', __( '2. Apariencia del pie', 'creceweb-lumen' ), __( 'Estos valores se aplican a las áreas de widgets del pie y mantienen el mismo estilo general.', 'creceweb-lumen' ), 50, 'step' );
	add_customizer_select( $wp_customize, 'footer_tone', 'creceweb_footer', __( 'Tono', 'creceweb-lumen' ), array( 'surface' => __( 'Claro', 'creceweb-lumen' ), 'primary' => __( 'Principal oscuro', 'creceweb-lumen' ) ), 60 );
	add_customizer_range( $wp_customize, 'footer_padding', 'creceweb_footer', __( 'Espaciado vertical', 'creceweb-lumen' ), 70, 24, 112, 4, 'px' );
	add_customizer_select( $wp_customize, 'footer_density', 'creceweb_footer', __( 'Densidad predefinida', 'creceweb-lumen' ), array( 'compact' => __( 'Compacta', 'creceweb-lumen' ), 'normal' => __( 'Normal', 'creceweb-lumen' ), 'spacious' => __( 'Amplia', 'creceweb-lumen' ), 'none' => __( 'Sin espaciado', 'creceweb-lumen' ) ), 80, __( 'Ajusta el espaciado vertical seleccionado para un pie más compacto o más amplio, o lo elimina por completo.', 'creceweb-lumen' ) );

	add_customizer_note( $wp_customize, 'creceweb_footer_copyright_note', 'creceweb_footer', __( '3. Copyright', 'creceweb-lumen' ), __( 'Franja inferior opcional con el año y el nombre del sitio.', 'creceweb-lumen' ), 100, 'step' );
	add_customizer_checkbox( $wp_customize, 'show_copyright', 'creceweb_footer', __( 'Mostrar copyright', 'creceweb-lumen' ), 110 );

	add_customizer_choice_cards( $wp_customize, 'mobile_footer_columns', 'creceweb_footer', __( 'Columnas del pie en diseño compacto', 'creceweb-lumen' ), array( '1' => __( '1 columna', 'creceweb-lumen' ), '2' => __( '2 columnas', 'creceweb-lumen' ) ), 310, 'columns', __( 'Elegí 1 o 2 columnas para todo el diseño compacto. Usá 1 columna cuando los widgets tengan textos largos.', 'creceweb-lumen' ) );
	add_customizer_checkbox( $wp_customize, 'hide_footer_widgets_on_mobile', 'creceweb_footer', __( 'Ocultar widgets del pie', 'creceweb-lumen' ), 320, __( 'Oculta todas las áreas de widgets del pie de página.', 'creceweb-lumen' ), null, 'postMessage' );

	register_customizer_color_group( $wp_customize, 'footer', 'creceweb_footer', 200 );
}

/**
 * Forces a full Customizer preview refresh when footer widget placement changes.
 *
 * WordPress uses postMessage for sidebars_widgets whenever the theme supports
 * selective refresh. The Editorial footer derives automatic columns from the
 * combined state of several footer sidebars, so adding/removing/moving a widget
 * must refresh the complete preview to recompute neighbouring fallback columns.
 * Widget instance edits keep their native transport; only footer sidebar
 * assignment settings are changed here.
 *
 * @param array<string,mixed> $args       Customizer setting arguments.
 * @param string              $setting_id Customizer setting ID.
 * @return array<string,mixed>
 */
function filter_footer_widget_customizer_setting_args( array $args, string $setting_id ): array {
	$footer_sidebar_ids = array_merge( array( CRECEWEB_FOOTER_BAR_WIDGET_AREA ), CRECEWEB_FOOTER_WIDGET_COLUMNS );

	foreach ( $footer_sidebar_ids as $sidebar_id ) {
		if ( sprintf( 'sidebars_widgets[%s]', $sidebar_id ) === $setting_id ) {
			$args['transport'] = 'refresh';
			break;
		}
	}

	return $args;
}
add_filter( 'widget_customizer_setting_args', __NAMESPACE__ . '\\filter_footer_widget_customizer_setting_args', 10, 2 );
