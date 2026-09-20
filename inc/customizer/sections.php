<?php
/**
 * Customizer panel and sections.
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
 * Registers the CreceWeb Lumen design panel and its sections.
 *
 * @param \WP_Customize_Manager $wp_customize Manager.
 * @return void
 */
function register_design_panel_and_sections( \WP_Customize_Manager $wp_customize ): void {
	$wp_customize->add_panel(
		'creceweb_design',
		array(
			'title'       => __( 'Diseño', 'creceweb-lumen' ),
			'description' => __( 'Valores base del theme. Los estilos definidos en Gutenberg, Elementor u otro editor prevalecen en cada bloque o módulo.', 'creceweb-lumen' ),
			'priority'    => 25,
		)
	);

	// Keep Site Identity native, but group it with the CreceWeb Lumen editor.
	$identity_section = $wp_customize->get_section( 'title_tagline' );
	if ( $identity_section ) {
		$identity_section->panel       = 'creceweb_design';
		$identity_section->priority    = 5;
		$identity_section->title       = __( 'Marca del sitio', 'creceweb-lumen' );
		$identity_section->description = __( 'Logo, título y descripción corta. Cada elemento funciona de manera independiente.', 'creceweb-lumen' );
	}

	$sections = array(
		'creceweb_quick_start' => array(
			'title'       => __( 'Inicio rápido', 'creceweb-lumen' ),
			'description' => __( 'Elegí una base visual y definí si querés ver los ajustes detallados.', 'creceweb-lumen' ),
			'panel'       => 'creceweb_design',
			'priority'    => 1,
		),
		'creceweb_global_style' => array(
			'title'       => __( 'Estilo global', 'creceweb-lumen' ),
			'description' => __( 'Anchos, ritmo, colores, tipografías, botones y formularios del sitio.', 'creceweb-lumen' ),
			'panel'       => 'creceweb_design',
			'priority'    => 10,
		),
		'creceweb_header_navigation' => array(
			'title'       => __( 'Cabecera y navegación', 'creceweb-lumen' ),
			'description' => __( 'Estructura de cabecera, menú de escritorio, menú compacto y barra superior.', 'creceweb-lumen' ),
			'panel'       => 'creceweb_design',
			'priority'    => 15,
		),
		'creceweb_screen_layout' => array(
			'title'       => __( 'Diseño de pantalla', 'creceweb-lumen' ),
			'description' => __( 'Barras laterales y distribución de contenido para blog, entradas y páginas.', 'creceweb-lumen' ),
			'panel'       => 'creceweb_design',
			'priority'    => 18,
		),
		'creceweb_content_blog' => array(
			'title'       => __( 'Contenido y blog', 'creceweb-lumen' ),
			'description' => __( 'Configurá por separado los listados del blog, las tarjetas y la lectura de cada entrada.', 'creceweb-lumen' ),
			'panel'       => 'creceweb_design',
			'priority'    => 20,
		),
		'creceweb_footer' => array(
			'title'       => __( 'Pie de página', 'creceweb-lumen' ),
			'description' => __( 'Widgets, copyright y comportamiento del pie en celular. Podés construir logo, enlaces y menús desde Widgets.', 'creceweb-lumen' ),
			'panel'       => 'creceweb_design',
			'priority'    => 25,
		),
		'creceweb_social' => array(
			'title'       => __( 'Redes sociales', 'creceweb-lumen' ),
			'description' => __( 'Ubicación y apariencia del bloque nativo Iconos sociales en la cabecera y el pie. Las redes y sus URLs se administran desde Apariencia > Widgets.', 'creceweb-lumen' ),
			'panel'       => 'creceweb_design',
			'priority'    => 27,
		),
		'creceweb_responsive' => array(
			'title'       => __( 'Celular y tablet', 'creceweb-lumen' ),
			'description' => __( 'Márgenes y ajustes opcionales por dispositivo. Las opciones de menú, marca y pie se encuentran en sus áreas correspondientes.', 'creceweb-lumen' ),
			'panel'       => 'creceweb_design',
			'priority'    => 30,
		),
		'creceweb_floating_action' => array(
			'title'       => __( 'Botón Volver arriba', 'creceweb-lumen' ),
			'description' => __( 'Mostrá u ocultá el acceso para regresar al inicio de la página. Si otro plugin agrega una acción flotante en la misma esquina, revisá la ubicación para evitar superposiciones.', 'creceweb-lumen' ),
			'panel'       => 'creceweb_design',
			'priority'    => 35,
		),
		'creceweb_accessibility' => array(
			'title'       => __( 'Accesibilidad', 'creceweb-lumen' ),
			'description' => __( 'Foco visible y preferencias de movimiento para una experiencia más inclusiva.', 'creceweb-lumen' ),
			'panel'       => 'creceweb_design',
			'priority'    => 40,
		),
		'creceweb_help_documentation' => array(
			'title'       => __( 'Ayuda y documentación', 'creceweb-lumen' ),
			'description' => __( 'Accedé a la guía oficial de CreceWeb Lumen según el idioma de tu perfil.', 'creceweb-lumen' ),
			'panel'       => 'creceweb_design',
			'priority'    => 50,
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

	$wp_customize->add_control(
		new External_Link_Control(
			$wp_customize,
			'creceweb_help_support_link',
			array(
				'section'      => 'creceweb_help_documentation',
				'settings'     => array(),
				'label'        => __( 'Apoyar Theme + Lite', 'creceweb-lumen' ),
				'description'  => __( 'Tu aporte voluntario ayuda a sostener el desarrollo y mantenimiento de Lumen Theme y Lumen Lite.', 'creceweb-lumen' ),
				'url'          => get_support_url(),
				'button_label' => __( 'Abrir página de apoyo', 'creceweb-lumen' ),
				'icon'         => 'dashicons-heart',
				'priority'     => 20,
			)
		)
	);
}
