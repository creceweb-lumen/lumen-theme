<?php
/**
 * Global-style Customizer controls.
 *
 * Registers dimensions, typography, color tokens, buttons, forms and links.
 *
 * @package CreceWebLumen
 */

namespace CreceWeb\Lumen;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers Estilo global controls.
 *
 * @param \WP_Customize_Manager $wp_customize Manager.
 * @return void
 */
function register_global_style_controls( \WP_Customize_Manager $wp_customize ): void {
	// Diseño > Estilo global — anchos y ritmo.
	add_customizer_range( $wp_customize, 'content_width', 'creceweb_global_style', __( 'Ancho de lectura', 'creceweb-lumen' ), 10, 640, 1100, 20, 'px', __( 'Artículos, páginas y texto principal.', 'creceweb-lumen' ) );
	add_customizer_range( $wp_customize, 'wide_width', 'creceweb_global_style', __( 'Ancho de secciones', 'creceweb-lumen' ), 20, 960, 1600, 20, 'px', __( 'Hero, grids y contenido alineado como amplio.', 'creceweb-lumen' ) );
	add_customizer_choice_cards( $wp_customize, 'spacing_density', 'creceweb_global_style', __( 'Ritmo vertical', 'creceweb-lumen' ), array( 'compact' => array( 'label' => __( 'Compacto', 'creceweb-lumen' ), 'description' => __( 'Menos aire', 'creceweb-lumen' ) ), 'normal' => array( 'label' => __( 'Normal', 'creceweb-lumen' ), 'description' => __( 'Equilibrado', 'creceweb-lumen' ) ), 'spacious' => array( 'label' => __( 'Amplio', 'creceweb-lumen' ), 'description' => __( 'Más aire', 'creceweb-lumen' ) ) ), 40, 'density', __( 'Define el aire vertical entre las secciones del sitio. Compacto reduce los espacios, Normal mantiene el equilibrio y Amplio agrega más separación en páginas y secciones.', 'creceweb-lumen' ) );

	// Tipografía.
	add_customizer_select( $wp_customize, 'font_preset', 'creceweb_global_style', __( 'Familia de texto', 'creceweb-lumen' ), array( 'system-sans' => __( 'Sistema sans', 'creceweb-lumen' ), 'system-serif' => __( 'Sistema serif', 'creceweb-lumen' ), 'system-mono' => __( 'Sistema mono', 'creceweb-lumen' ) ), 10 );
	add_customizer_select( $wp_customize, 'heading_preset', 'creceweb_global_style', __( 'Familia de títulos', 'creceweb-lumen' ), array( 'inherit' => __( 'Igual al texto', 'creceweb-lumen' ), 'system-sans' => __( 'Sistema sans', 'creceweb-lumen' ), 'system-serif' => __( 'Sistema serif', 'creceweb-lumen' ) ), 20 );
	add_customizer_select( $wp_customize, 'font_scale', 'creceweb_global_style', __( 'Escala tipográfica', 'creceweb-lumen' ), array( 'compact' => __( 'Compacta', 'creceweb-lumen' ), 'standard' => __( 'Estándar', 'creceweb-lumen' ), 'comfortable' => __( 'Cómoda', 'creceweb-lumen' ) ), 30 );
	add_customizer_select( $wp_customize, 'heading_weight', 'creceweb_global_style', __( 'Peso de títulos', 'creceweb-lumen' ), array( '500' => '500', '600' => '600', '700' => '700', '800' => '800' ), 40 );

	// Diseño > Estilo global — botones, formularios y enlaces.
	add_customizer_select( $wp_customize, 'shape', 'creceweb_global_style', __( 'Forma de controles y cards', 'creceweb-lumen' ), array( 'square' => __( 'Recta', 'creceweb-lumen' ), 'soft' => __( 'Suave', 'creceweb-lumen' ), 'rounded' => __( 'Redondeada', 'creceweb-lumen' ) ), 10 );
	add_customizer_select( $wp_customize, 'button_style', 'creceweb_global_style', __( 'Estilo de botones', 'creceweb-lumen' ), array( 'solid' => __( 'Sólido', 'creceweb-lumen' ), 'soft' => __( 'Sólido con elevación', 'creceweb-lumen' ), 'outline' => __( 'Contorno', 'creceweb-lumen' ) ), 20 );
	add_customizer_range( $wp_customize, 'button_radius', 'creceweb_global_style', __( 'Radio de botones', 'creceweb-lumen' ), 30, 0, 48, 1, 'px' );
	add_customizer_range( $wp_customize, 'button_padding_y', 'creceweb_global_style', __( 'Padding vertical de botones', 'creceweb-lumen' ), 40, 8, 28, 1, 'px' );
	add_customizer_range( $wp_customize, 'button_padding_x', 'creceweb_global_style', __( 'Padding horizontal de botones', 'creceweb-lumen' ), 50, 12, 44, 1, 'px' );
	add_customizer_range( $wp_customize, 'form_radius', 'creceweb_global_style', __( 'Radio de formularios', 'creceweb-lumen' ), 60, 0, 36, 1, 'px' );
	add_customizer_select( $wp_customize, 'link_decoration', 'creceweb_global_style', __( 'Subrayado de enlaces', 'creceweb-lumen' ), array( 'always' => __( 'Siempre', 'creceweb-lumen' ), 'hover' => __( 'Solo al pasar', 'creceweb-lumen' ), 'underline' => __( 'Estándar', 'creceweb-lumen' ), 'never' => __( 'Nunca', 'creceweb-lumen' ) ), 70 );

	register_customizer_color_group( $wp_customize, 'brand', 'creceweb_global_style', 120 );

	add_customizer_note(
		$wp_customize,
		'creceweb_content_colors_note',
		'creceweb_global_style',
		__( 'Colores del contenido', 'creceweb-lumen' ),
		__( 'Son opcionales y se aplican solo al contenido de páginas y entradas. Si un campo queda vacío, Lumen no cambia el valor predeterminado de ese elemento. En Gutenberg, los colores explícitos de los bloques siguen teniendo prioridad. En Elementor, Lumen registra estos colores como Global Colors y, cuando están configurados, los usa como valores predeterminados de los widgets compatibles; un color local u otro Global Color elegido en Elementor puede reemplazarlos.', 'creceweb-lumen' ),
		180
	);
	add_customizer_color( $wp_customize, 'content_heading_color', 'creceweb_global_style', __( 'Títulos del contenido', 'creceweb-lumen' ), 181, null, __( 'Si se configura, Lumen lo ofrece a Elementor como Global Color y lo usa como valor predeterminado de los títulos; una elección local u otro Global Color en Elementor tiene prioridad.', 'creceweb-lumen' ), 'refresh' );
	add_customizer_color( $wp_customize, 'content_button_background_color', 'creceweb_global_style', __( 'Botones del contenido: fondo', 'creceweb-lumen' ), 182, null, __( 'Si se configura, Lumen lo ofrece a Elementor como Global Color y lo usa como fondo predeterminado de los botones; una elección local u otro Global Color en Elementor tiene prioridad.', 'creceweb-lumen' ), 'refresh' );
	add_customizer_color( $wp_customize, 'content_button_hover_color', 'creceweb_global_style', __( 'Botones del contenido: hover', 'creceweb-lumen' ), 183, null, __( 'Si se configura, Lumen lo ofrece a Elementor como Global Color y lo usa como fondo hover predeterminado de los botones; una elección local u otro Global Color en Elementor tiene prioridad.', 'creceweb-lumen' ), 'refresh' );
	add_customizer_color( $wp_customize, 'content_button_text_color', 'creceweb_global_style', __( 'Botones del contenido: texto', 'creceweb-lumen' ), 184, null, __( 'Si se configura, Lumen lo ofrece a Elementor como Global Color y lo usa como texto predeterminado de los botones; una elección local u otro Global Color en Elementor tiene prioridad.', 'creceweb-lumen' ), 'refresh' );
	add_customizer_color( $wp_customize, 'content_box_color', 'creceweb_global_style', __( 'Recuadros del contenido', 'creceweb-lumen' ), 185, null, __( 'Define el acento de las tarjetas Lumen y de elementos con la clase CSS cw-content-box. En Elementor, esa clase opta explícitamente por el color de Lumen. La variante cw-content-box--filled usa el color como fondo.', 'creceweb-lumen' ), 'refresh' );
	add_customizer_color( $wp_customize, 'content_bullet_color', 'creceweb_global_style', __( 'Viñetas e íconos de listas', 'creceweb-lumen' ), 186, null, __( 'Si se configura, Lumen lo ofrece a Elementor como Global Color y lo usa como color predeterminado de los íconos de Icon List; una elección local u otro Global Color en Elementor tiene prioridad. Las viñetas nativas de WordPress siguen usando el mismo token de Lumen.', 'creceweb-lumen' ), 'refresh' );

	register_customizer_color_group( $wp_customize, 'actions', 'creceweb_global_style', 220 );
}
