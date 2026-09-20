<?php
/**
 * Customizer color groups.
 *
 * Provides shared color-group definitions for global, navigation and footer modules.
 *
 * @package CreceWebLumen
 */

namespace CreceWeb\Lumen;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns grouped color controls used by the guided Customizer.
 *
 * @return array<string, array<string, mixed>>
 */
function get_customizer_color_groups(): array {
	return array(
		'brand' => array(
			'title'       => __( 'Marca y superficies', 'creceweb-lumen' ),
			'description' => __( 'Definí los valores base. Los colores aplicados dentro de bloques o constructores prevalecen.', 'creceweb-lumen' ),
			'fields'      => array(
				'primary_color' => __( 'Color principal', 'creceweb-lumen' ),
				'accent_color' => __( 'Color de acción', 'creceweb-lumen' ),
				'accent_strong' => __( 'Color de acción activo', 'creceweb-lumen' ),
				'background_color' => __( 'Fondo general', 'creceweb-lumen' ),
				'surface_color' => __( 'Superficie', 'creceweb-lumen' ),
				'text_color' => __( 'Texto principal', 'creceweb-lumen' ),
				'heading_color' => __( 'Títulos', 'creceweb-lumen' ),
				'link_color' => __( 'Enlaces generales', 'creceweb-lumen' ),
				'text_muted_color' => __( 'Texto secundario', 'creceweb-lumen' ),
				'border_color' => __( 'Bordes', 'creceweb-lumen' ),
			),
		),
		'navigation' => array(
			'title'       => __( 'Navegación', 'creceweb-lumen' ),
			'description' => __( 'Colores de enlaces y estados del menú principal y de los submenús.', 'creceweb-lumen' ),
			'fields'      => array(
				'navigation_color' => __( 'Menú: normal', 'creceweb-lumen' ),
				'navigation_hover_color' => __( 'Menú: hover', 'creceweb-lumen' ),
				'navigation_active_color' => __( 'Menú: enlace activo', 'creceweb-lumen' ),
				'submenu_background_color' => __( 'Submenú: fondo', 'creceweb-lumen' ),
				'submenu_hover_text_color' => array(
					'label' => __( 'Submenú: texto hover', 'creceweb-lumen' ),
					'transport' => 'refresh',
				),
				'submenu_hover_background_color' => array(
					'label' => __( 'Submenú: fondo hover', 'creceweb-lumen' ),
					'transport' => 'refresh',
				),
			),
		),
		'actions' => array(
			'title'       => __( 'Botones y formularios', 'creceweb-lumen' ),
			'description' => __( 'Estados para acciones, campos y foco.', 'creceweb-lumen' ),
			'fields'      => array(
				'button_background_color' => __( 'Botones: fondo', 'creceweb-lumen' ),
				'button_hover_color' => __( 'Botones: hover', 'creceweb-lumen' ),
				'button_text_color' => __( 'Botones: texto', 'creceweb-lumen' ),
				'form_background_color' => __( 'Formularios: fondo', 'creceweb-lumen' ),
				'form_border_color' => __( 'Formularios: borde', 'creceweb-lumen' ),
				'form_focus_color' => __( 'Formularios: foco', 'creceweb-lumen' ),
			),
		),
		'footer' => array(
			'title'       => __( 'Footer y copyright', 'creceweb-lumen' ),
			'description' => __( 'Valores base del pie de página.', 'creceweb-lumen' ),
			'fields'      => array(
				'footer_background_color' => __( 'Footer: fondo', 'creceweb-lumen' ),
				'footer_text_color' => __( 'Footer: texto', 'creceweb-lumen' ),
				'footer_link_color' => __( 'Footer: enlaces', 'creceweb-lumen' ),
				'copyright_background_color' => __( 'Copyright: fondo', 'creceweb-lumen' ),
				'copyright_text_color' => __( 'Copyright: texto', 'creceweb-lumen' ),
			),
		),
	);
}

/**
 * Registers one grouped color block in the selected Customizer section.
 *
 * @param \WP_Customize_Manager $wp_customize Manager.
 * @param string                 $group_key Group key.
 * @param string                 $section Customizer section.
 * @param int                    $priority First priority.
 * @return void
 */
function register_customizer_color_group( \WP_Customize_Manager $wp_customize, string $group_key, string $section, int $priority ): void {
	$groups = get_customizer_color_groups();

	if ( ! isset( $groups[ $group_key ] ) ) {
		return;
	}

	$group = $groups[ $group_key ];
	add_customizer_note( $wp_customize, 'creceweb_colors_note_' . $group_key, $section, $group['title'], $group['description'], $priority );
	$priority++;

	foreach ( $group['fields'] as $key => $field ) {
		$label     = is_array( $field ) ? ( $field['label'] ?? '' ) : $field;
		$transport = is_array( $field ) ? ( $field['transport'] ?? 'postMessage' ) : 'postMessage';
		add_customizer_color( $wp_customize, $key, $section, $label, $priority, null, '', $transport );
		$priority++;
	}
}
