<?php
/**
 * Global-design Customizer controls.
 *
 * Registers layout, typography, color tokens, buttons, forms and content styles.
 *
 * @package CreceWebLumen
 */

namespace CreceWeb\Lumen;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers global design controls without changing the underlying setting IDs.
 *
 * @param \WP_Customize_Manager $wp_customize Manager.
 * @return void
 */
function register_global_style_controls( \WP_Customize_Manager $wp_customize ): void {
	// Opciones globales > Layout y espaciado.
	add_customizer_range( $wp_customize, 'content_width', 'creceweb_global_layout', __( 'Ancho de lectura', 'creceweb-lumen' ), 10, 640, 1100, 20, 'px', __( 'Artículos, páginas y texto principal.', 'creceweb-lumen' ) );
	add_customizer_range( $wp_customize, 'wide_width', 'creceweb_global_layout', __( 'Ancho de secciones', 'creceweb-lumen' ), 20, 960, 1600, 20, 'px', __( 'Hero, grids y contenido alineado como amplio.', 'creceweb-lumen' ) );
	add_customizer_choice_cards( $wp_customize, 'spacing_density', 'creceweb_global_layout', __( 'Ritmo vertical', 'creceweb-lumen' ), array( 'compact' => array( 'label' => __( 'Compacto', 'creceweb-lumen' ), 'description' => __( 'Menos aire', 'creceweb-lumen' ) ), 'normal' => array( 'label' => __( 'Normal', 'creceweb-lumen' ), 'description' => __( 'Equilibrado', 'creceweb-lumen' ) ), 'spacious' => array( 'label' => __( 'Amplio', 'creceweb-lumen' ), 'description' => __( 'Más aire', 'creceweb-lumen' ) ) ), 30, 'density', __( 'Define el aire vertical entre las secciones del sitio. Compacto reduce los espacios, Normal mantiene el equilibrio y Amplio agrega más separación en páginas y secciones.', 'creceweb-lumen' ) );

	// Opciones globales > Colores.
	register_customizer_color_group( $wp_customize, 'brand', 'creceweb_global_style', 10 );

	// Opciones globales > Tipografía. Una sola experiencia para sistema, Inter y Google Fonts.
	$font_setting_id    = add_customizer_setting( $wp_customize, 'font_preset', 'refresh' );
	$heading_setting_id = add_customizer_setting( $wp_customize, 'heading_preset', 'refresh' );

	add_customizer_note(
		$wp_customize,
		'creceweb_global_typography_fonts_note',
		'creceweb_global_typography',
		__( 'Familias tipográficas', 'creceweb-lumen' ),
		__( 'Elegí la familia para el texto y para los títulos. Las fuentes del sistema e Inter están disponibles de forma directa.', 'creceweb-lumen' ),
		5
	);

	$body_font_choices = array(
		'system' => array(
			'label'   => __( 'Fuentes del sistema', 'creceweb-lumen' ),
			'choices' => array(
				'system-sans'  => __( 'Sistema sans · máximo rendimiento', 'creceweb-lumen' ),
				'system-serif' => __( 'Sistema serif', 'creceweb-lumen' ),
				'system-mono'  => __( 'Sistema mono', 'creceweb-lumen' ),
			),
		),
		'lumen' => array(
			'label'   => __( 'Incluida en Lumen', 'creceweb-lumen' ),
			'choices' => array(
				'inter-local' => __( 'Inter · local', 'creceweb-lumen' ),
			),
		),
	);

	$heading_font_choices = array(
		'general' => array(
			'label'   => __( 'General', 'creceweb-lumen' ),
			'choices' => array(
				'inherit' => __( 'Igual al texto', 'creceweb-lumen' ),
			),
		),
		'system' => array(
			'label'   => __( 'Fuentes del sistema', 'creceweb-lumen' ),
			'choices' => array(
				'system-sans'  => __( 'Sistema sans · máximo rendimiento', 'creceweb-lumen' ),
				'system-serif' => __( 'Sistema serif', 'creceweb-lumen' ),
			),
		),
		'lumen' => array(
			'label'   => __( 'Incluida en Lumen', 'creceweb-lumen' ),
			'choices' => array(
				'inter-local' => __( 'Inter · local', 'creceweb-lumen' ),
			),
		),
	);

	/**
	 * Filters the grouped typography choices shown by Lumen's unified selectors.
	 *
	 * Extensions may add locally managed families without replacing the Theme
	 * controls or introducing a second selector.
	 *
	 * @param array<string,array{label:string,choices:array<string,string>}> $choices Grouped choices.
	 * @param string                                                         $context `body` or `heading`.
	 */
	$filtered_body_choices = apply_filters( 'creceweb_lumen_typography_font_choices', $body_font_choices, 'body' );
	if ( is_array( $filtered_body_choices ) ) {
		$body_font_choices = $filtered_body_choices;
	}
	$filtered_heading_choices = apply_filters( 'creceweb_lumen_typography_font_choices', $heading_font_choices, 'heading' );
	if ( is_array( $filtered_heading_choices ) ) {
		$heading_font_choices = $filtered_heading_choices;
	}


	$wp_customize->add_control(
		new Google_Font_Catalog_Control(
			$wp_customize,
			'creceweb_google_fonts_catalog',
			array(
				'settings'    => array( 'body' => $font_setting_id, 'heading' => $heading_setting_id ),
				'section'     => 'creceweb_global_typography',
				'priority'    => 8,
				'catalog_url' => CRECEWEB_LUMEN_URI . '/assets/data/google-fonts-catalog.json',
			)
		)
	);

	$wp_customize->add_control(
		new Font_Source_Select_Control(
			$wp_customize,
			'creceweb_local_font_body',
			array(
				'settings'    => $font_setting_id,
				'section'     => 'creceweb_global_typography',
				'label'       => __( 'Familia de texto', 'creceweb-lumen' ),
				'description' => __( 'Se aplica al texto general del sitio.', 'creceweb-lumen' ),
				'priority'    => 10,
				'choices'     => $body_font_choices,
			)
		)
	);

	$wp_customize->add_control(
		new Font_Source_Select_Control(
			$wp_customize,
			'creceweb_local_font_heading',
			array(
				'settings'    => $heading_setting_id,
				'section'     => 'creceweb_global_typography',
				'label'       => __( 'Familia de títulos', 'creceweb-lumen' ),
				'description' => __( 'Podés mantener la misma familia del texto o elegir otra.', 'creceweb-lumen' ),
				'priority'    => 20,
				'choices'     => $heading_font_choices,
			)
		)
	);

	add_customizer_select( $wp_customize, 'font_scale', 'creceweb_global_typography', __( 'Escala tipográfica', 'creceweb-lumen' ), array( 'compact' => __( 'Compacta', 'creceweb-lumen' ), 'standard' => __( 'Estándar', 'creceweb-lumen' ), 'comfortable' => __( 'Cómoda', 'creceweb-lumen' ) ), 30 );
	add_customizer_select( $wp_customize, 'heading_weight', 'creceweb_global_typography', __( 'Peso de títulos', 'creceweb-lumen' ), array( '500' => '500', '600' => '600', '700' => '700', '800' => '800' ), 40 );

	// Opciones globales > Botones y acciones — General / Diseño.
	add_customizer_note( $wp_customize, 'creceweb_global_buttons_general_note', 'creceweb_global_buttons', __( 'General', 'creceweb-lumen' ), __( 'Definí la forma y el espaciado base de botones y acciones compatibles de Lumen.', 'creceweb-lumen' ), 5 );
	add_customizer_select( $wp_customize, 'button_style', 'creceweb_global_buttons', __( 'Estilo', 'creceweb-lumen' ), array( 'solid' => __( 'Sólido', 'creceweb-lumen' ), 'soft' => __( 'Sólido con elevación', 'creceweb-lumen' ), 'outline' => __( 'Contorno', 'creceweb-lumen' ) ), 10 );
	add_customizer_range( $wp_customize, 'button_radius', 'creceweb_global_buttons', __( 'Radio', 'creceweb-lumen' ), 20, 0, 48, 1, 'px' );
	add_customizer_range( $wp_customize, 'button_padding_y', 'creceweb_global_buttons', __( 'Padding vertical', 'creceweb-lumen' ), 30, 8, 28, 1, 'px' );
	add_customizer_range( $wp_customize, 'button_padding_x', 'creceweb_global_buttons', __( 'Padding horizontal', 'creceweb-lumen' ), 40, 12, 44, 1, 'px' );
	register_customizer_color_group( $wp_customize, 'buttons', 'creceweb_global_buttons', 60 );

	// Opciones globales > Formularios y controles — General / Diseño.
	add_customizer_note( $wp_customize, 'creceweb_global_forms_general_note', 'creceweb_global_forms', __( 'General', 'creceweb-lumen' ), __( 'Campos, selects y controles compatibles de Lumen heredan estos valores sin forzar estilos sobre formularios de terceros.', 'creceweb-lumen' ), 5 );
	add_customizer_range( $wp_customize, 'form_radius', 'creceweb_global_forms', __( 'Radio', 'creceweb-lumen' ), 10, 0, 36, 1, 'px' );
	register_customizer_color_group( $wp_customize, 'forms', 'creceweb_global_forms', 30 );

	// Opciones globales > Elementos de contenido.
	add_customizer_note( $wp_customize, 'creceweb_global_content_general_note', 'creceweb_global_content', __( 'General', 'creceweb-lumen' ), __( 'Ajustes compartidos por controles, tarjetas y enlaces del Theme.', 'creceweb-lumen' ), 5 );
	add_customizer_select( $wp_customize, 'shape', 'creceweb_global_content', __( 'Forma de controles y cards', 'creceweb-lumen' ), array( 'square' => __( 'Recta', 'creceweb-lumen' ), 'soft' => __( 'Suave', 'creceweb-lumen' ), 'rounded' => __( 'Redondeada', 'creceweb-lumen' ) ), 10 );
	add_customizer_select( $wp_customize, 'link_decoration', 'creceweb_global_content', __( 'Subrayado de enlaces', 'creceweb-lumen' ), array( 'always' => __( 'Siempre', 'creceweb-lumen' ), 'hover' => __( 'Solo al pasar', 'creceweb-lumen' ), 'underline' => __( 'Estándar', 'creceweb-lumen' ), 'never' => __( 'Nunca', 'creceweb-lumen' ) ), 20 );

	add_customizer_note(
		$wp_customize,
		'creceweb_content_colors_note',
		'creceweb_global_content',
		__( 'Colores del contenido', 'creceweb-lumen' ),
		__( 'Son opcionales y se aplican solo al contenido de páginas y entradas. Si un campo queda vacío, Lumen no cambia el valor predeterminado de ese elemento. En Gutenberg, los colores explícitos de los bloques siguen teniendo prioridad. En Elementor, Lumen registra estos colores como Global Colors y, cuando están configurados, los usa como valores predeterminados de los widgets compatibles; un color local u otro Global Color elegido en Elementor puede reemplazarlos.', 'creceweb-lumen' ),
		40
	);
	add_customizer_color( $wp_customize, 'content_heading_color', 'creceweb_global_content', __( 'Títulos del contenido', 'creceweb-lumen' ), 41, null, __( 'Si se configura, Lumen lo ofrece a Elementor como Global Color y lo usa como valor predeterminado de los títulos; una elección local u otro Global Color en Elementor tiene prioridad.', 'creceweb-lumen' ), 'refresh' );
	add_customizer_color( $wp_customize, 'content_button_background_color', 'creceweb_global_content', __( 'Botones del contenido: fondo', 'creceweb-lumen' ), 42, null, __( 'Si se configura, Lumen lo ofrece a Elementor como Global Color y lo usa como fondo predeterminado de los botones; una elección local u otro Global Color en Elementor tiene prioridad.', 'creceweb-lumen' ), 'refresh' );
	add_customizer_color( $wp_customize, 'content_button_hover_color', 'creceweb_global_content', __( 'Botones del contenido: hover', 'creceweb-lumen' ), 43, null, __( 'Si se configura, Lumen lo ofrece a Elementor como Global Color y lo usa como fondo hover predeterminado de los botones; una elección local u otro Global Color en Elementor tiene prioridad.', 'creceweb-lumen' ), 'refresh' );
	add_customizer_color( $wp_customize, 'content_button_text_color', 'creceweb_global_content', __( 'Botones del contenido: texto', 'creceweb-lumen' ), 44, null, __( 'Si se configura, Lumen lo ofrece a Elementor como Global Color y lo usa como texto predeterminado de los botones; una elección local u otro Global Color en Elementor tiene prioridad.', 'creceweb-lumen' ), 'refresh' );
	add_customizer_color( $wp_customize, 'content_box_color', 'creceweb_global_content', __( 'Recuadros del contenido', 'creceweb-lumen' ), 45, null, __( 'Define el acento de las tarjetas Lumen y de elementos con la clase CSS cw-content-box. En Elementor, esa clase opta explícitamente por el color de Lumen. La variante cw-content-box--filled usa el color como fondo.', 'creceweb-lumen' ), 'refresh' );
	add_customizer_color( $wp_customize, 'content_bullet_color', 'creceweb_global_content', __( 'Viñetas e íconos de listas', 'creceweb-lumen' ), 46, null, __( 'Si se configura, Lumen lo ofrece a Elementor como Global Color y lo usa como color predeterminado de los íconos de Icon List; una elección local u otro Global Color en Elementor tiene prioridad. Las viñetas nativas de WordPress siguen usando el mismo token de Lumen.', 'creceweb-lumen' ), 'refresh' );
}
