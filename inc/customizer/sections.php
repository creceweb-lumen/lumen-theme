<?php
/**
 * Customizer panels and sections.
 *
 * Registers the stable Customizer information architecture.
 *
 * @package CreceWebLumen
 */

namespace CreceWeb\Lumen;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the CreceWeb Lumen Customizer information architecture.
 *
 * The UI is split into native WordPress panels so global design tokens stay
 * separate from structural and contextual settings. Setting IDs remain stable;
 * only their administrative presentation changes.
 *
 * @param \WP_Customize_Manager $wp_customize Manager.
 * @return void
 */
function register_design_panel_and_sections( \WP_Customize_Manager $wp_customize ): void {
	$wp_customize->add_panel(
		'creceweb_design',
		array(
			'title'       => __( 'Opciones globales', 'creceweb-lumen' ),
			'description' => __( 'Colores, tipografía, medidas y componentes visuales compartidos por todo el sitio. Los estilos explícitos de Gutenberg o Elementor siguen teniendo prioridad.', 'creceweb-lumen' ),
			'priority'    => 25,
		)
	);

	$wp_customize->add_panel(
		'creceweb_header_panel',
		array(
			'title'       => __( 'Cabecera y navegación', 'creceweb-lumen' ),
			'description' => __( 'Estructura de cabecera, navegación principal, submenús y barra superior.', 'creceweb-lumen' ),
			'priority'    => 26,
		)
	);

	$wp_customize->add_panel(
		'creceweb_content_panel',
		array(
			'title'       => __( 'Contenido y estructura', 'creceweb-lumen' ),
			'description' => __( 'Barras laterales, listados del blog y presentación de las entradas individuales.', 'creceweb-lumen' ),
			'priority'    => 27,
		)
	);

	$wp_customize->add_panel(
		'creceweb_footer_panel',
		array(
			'title'       => __( 'Pie de página y redes', 'creceweb-lumen' ),
			'description' => __( 'Pie de página y presentación de las redes sociales nativas de WordPress.', 'creceweb-lumen' ),
			'priority'    => 28,
		)
	);

	$wp_customize->register_section_type( External_Link_Section::class );
	$wp_customize->add_section(
		new External_Link_Section(
			$wp_customize,
			'creceweb_support_link',
			array(
				'title'       => __( 'Apoyar Theme + Lite', 'creceweb-lumen' ),
				'description' => __( 'Tu aporte voluntario ayuda a sostener el desarrollo y mantenimiento de Lumen Theme y Lumen Lite.', 'creceweb-lumen' ),
				'url'         => get_support_url(),
				'link_label'  => __( 'Abrir página de apoyo', 'creceweb-lumen' ),
				'icon'        => 'dashicons-heart',
				'priority'    => 24,
			)
		)
	);

	// Keep Site Identity native, but group it with the global design system.
	$identity_section = $wp_customize->get_section( 'title_tagline' );
	if ( $identity_section ) {
		$identity_section->panel       = 'creceweb_design';
		$identity_section->priority    = 5;
		$identity_section->title       = __( 'Marca del sitio', 'creceweb-lumen' );
		$identity_section->description = __( 'Logo, título y descripción corta. Cada elemento funciona de manera independiente.', 'creceweb-lumen' );
	}

	$sections = array(
		// Diseño global.
		'creceweb_quick_start' => array(
			'title'       => __( 'Inicio rápido', 'creceweb-lumen' ),
			'description' => __( 'Elegí una base visual para empezar. Después podés ajustar cada área desde las secciones de Opciones globales.', 'creceweb-lumen' ),
			'panel'       => 'creceweb_design',
			'priority'    => 1,
		),
		'creceweb_global_layout' => array(
			'title'       => __( 'Layout y espaciado', 'creceweb-lumen' ),
			'description' => __( 'Anchos globales y ritmo vertical del contenido.', 'creceweb-lumen' ),
			'panel'       => 'creceweb_design',
			'priority'    => 10,
		),
		'creceweb_global_style' => array(
			'title'       => __( 'Colores', 'creceweb-lumen' ),
			'description' => __( 'Paleta global para marca, superficies, texto, enlaces y bordes.', 'creceweb-lumen' ),
			'panel'       => 'creceweb_design',
			'priority'    => 12,
		),
		'creceweb_global_typography' => array(
			'title'       => __( 'Tipografía', 'creceweb-lumen' ),
			'description' => __( 'Elegí las familias y el estilo tipográfico general del sitio desde un solo lugar.', 'creceweb-lumen' ),
			'panel'       => 'creceweb_design',
			'priority'    => 14,
		),
		'creceweb_global_buttons' => array(
			'title'       => __( 'Botones y acciones', 'creceweb-lumen' ),
			'description' => __( 'Estilo compartido por botones y acciones compatibles de Lumen.', 'creceweb-lumen' ),
			'panel'       => 'creceweb_design',
			'priority'    => 16,
		),
		'creceweb_global_forms' => array(
			'title'       => __( 'Formularios y controles', 'creceweb-lumen' ),
			'description' => __( 'Estilo compartido por campos, selects y controles compatibles de Lumen.', 'creceweb-lumen' ),
			'panel'       => 'creceweb_design',
			'priority'    => 18,
		),
		'creceweb_global_content' => array(
			'title'       => __( 'Elementos de contenido', 'creceweb-lumen' ),
			'description' => __( 'Forma general, enlaces y colores opcionales para elementos de páginas y entradas.', 'creceweb-lumen' ),
			'panel'       => 'creceweb_design',
			'priority'    => 20,
		),
		'creceweb_responsive' => array(
			'title'       => __( 'Espaciado en celular y tablet', 'creceweb-lumen' ),
			'description' => __( 'Márgenes laterales para mantener alineados cabecera, contenido y pie en pantallas compactas.', 'creceweb-lumen' ),
			'panel'       => 'creceweb_design',
			'priority'    => 30,
		),
		'creceweb_accessibility' => array(
			'title'       => __( 'Accesibilidad', 'creceweb-lumen' ),
			'description' => __( 'Foco visible y preferencias de movimiento para una experiencia más inclusiva.', 'creceweb-lumen' ),
			'panel'       => 'creceweb_design',
			'priority'    => 35,
		),
		'creceweb_floating_action' => array(
			'title'       => __( 'Volver arriba', 'creceweb-lumen' ),
			'description' => __( 'Mostrá u ocultá el acceso para regresar al inicio de la página. Si otro plugin agrega una acción flotante en la misma esquina, revisá la ubicación para evitar superposiciones.', 'creceweb-lumen' ),
			'panel'       => 'creceweb_design',
			'priority'    => 40,
		),
		'creceweb_help_documentation' => array(
			'title'       => __( 'Ayuda y documentación', 'creceweb-lumen' ),
			'description' => __( 'Accedé a la guía oficial de CreceWeb Lumen según el idioma de tu perfil.', 'creceweb-lumen' ),
			'priority'    => 29,
		),

		// Cabecera y navegación.
		'creceweb_header_navigation' => array(
			'title'       => __( 'Cabecera', 'creceweb-lumen' ),
			'description' => __( 'Ancho, comportamiento, tono, densidad y distribución de la cabecera.', 'creceweb-lumen' ),
			'panel'       => 'creceweb_header_panel',
			'priority'    => 10,
		),
		'creceweb_navigation' => array(
			'title'       => __( 'Navegación', 'creceweb-lumen' ),
			'description' => __( 'Alineación, tipografía, separación y comportamiento del menú principal y compacto.', 'creceweb-lumen' ),
			'panel'       => 'creceweb_header_panel',
			'priority'    => 20,
		),
		'creceweb_submenus' => array(
			'title'       => __( 'Submenús', 'creceweb-lumen' ),
			'description' => __( 'Fondo y estados hover de los submenús.', 'creceweb-lumen' ),
			'panel'       => 'creceweb_header_panel',
			'priority'    => 30,
		),
		'creceweb_top_bar' => array(
			'title'       => __( 'Barra superior', 'creceweb-lumen' ),
			'description' => __( 'Visibilidad, ancho, alineación, espaciado y colores de la barra superior.', 'creceweb-lumen' ),
			'panel'       => 'creceweb_header_panel',
			'priority'    => 40,
		),

		// Contenido y estructura.
		'creceweb_screen_layout' => array(
			'title'       => __( 'Barras laterales', 'creceweb-lumen' ),
			'description' => __( 'Elegí dónde mostrar la barra lateral y su ancho en blog, entradas y páginas.', 'creceweb-lumen' ),
			'panel'       => 'creceweb_content_panel',
			'priority'    => 10,
		),
		'creceweb_content_blog' => array(
			'title'       => __( 'Blog y archivos', 'creceweb-lumen' ),
			'description' => __( 'Cabecera del blog, listados, tarjetas y acción “Seguir leyendo”.', 'creceweb-lumen' ),
			'panel'       => 'creceweb_content_panel',
			'priority'    => 20,
		),
		'creceweb_single_post' => array(
			'title'       => __( 'Entrada individual', 'creceweb-lumen' ),
			'description' => __( 'Ancho de lectura, encabezado, imagen destacada e información de cada entrada.', 'creceweb-lumen' ),
			'panel'       => 'creceweb_content_panel',
			'priority'    => 30,
		),

		// Pie y redes sociales.
		'creceweb_footer' => array(
			'title'       => __( 'Pie de página', 'creceweb-lumen' ),
			'description' => __( 'Widgets, copyright y comportamiento del pie en celular. Podés construir logo, enlaces y menús desde Widgets.', 'creceweb-lumen' ),
			'panel'       => 'creceweb_footer_panel',
			'priority'    => 10,
		),
		'creceweb_social' => array(
			'title'       => __( 'Redes sociales', 'creceweb-lumen' ),
			'description' => __( 'Ubicación y apariencia del bloque nativo Iconos sociales en la cabecera y el pie. Las redes y sus URLs se administran desde Apariencia > Widgets.', 'creceweb-lumen' ),
			'panel'       => 'creceweb_footer_panel',
			'priority'    => 20,
		),
	);

	foreach ( $sections as $section_id => $args ) {
		$wp_customize->add_section( $section_id, $args );
	}

	$wp_customize->add_control(
		new External_Link_Control(
			$wp_customize,
			'creceweb_help_documentation_link',
			array(
				'section'      => 'creceweb_help_documentation',
				'settings'     => array(),
				'label'        => __( 'Ayuda y documentación', 'creceweb-lumen' ),
				'description'  => __( 'Consultá instrucciones de configuración, recomendaciones y respuestas a las preguntas frecuentes del theme.', 'creceweb-lumen' ),
				'url'          => get_documentation_url(),
				'button_label' => __( 'Abrir ayuda y documentación', 'creceweb-lumen' ),
				'priority'     => 10,
			)
		)
	);
}
