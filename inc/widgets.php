<?php
/**
 * Native widget areas for CreceWeb Lumen.
 *
 * @package CreceWebLumen
 */

namespace CreceWeb\Lumen;

const CRECEWEB_PRIMARY_SIDEBAR         = 'sidebar-1';
const CRECEWEB_TOP_BAR_WIDGET_AREA    = 'top-bar';
const CRECEWEB_FOOTER_BAR_WIDGET_AREA = 'footer-bar';
const CRECEWEB_FOOTER_WIDGET_COLUMNS  = array( 'footer-1', 'footer-2', 'footer-3', 'footer-4', 'footer-5' );
const CRECEWEB_HEADER_SOCIAL_WIDGET_AREA = 'social-header';
const CRECEWEB_FOOTER_SOCIAL_WIDGET_AREA = 'social-footer';

/**
 * @return array<string,string>
 */
function get_widget_area_markup(): array {
	return array(
		'before_widget' => '<aside id="%1$s" class="widget cw-widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title cw-widget__title">',
		'after_title'   => '</h2>',
	);
}

/**
 * Markup for social widget areas. A div is used instead of an aside because
 * these controls are part of the site header/footer navigation chrome.
 *
 * @return array<string,string>
 */
function get_social_widget_area_markup(): array {
	return array(
		'before_widget' => '<div id="%1$s" class="cw-social-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="screen-reader-text">',
		'after_title'   => '</h2>',
	);
}

/**
 * @return void
 */
function register_widget_areas(): void {
	$markup = get_widget_area_markup();

	register_sidebar( array_merge( $markup, array(
		'name'        => __( 'Barra lateral principal', 'creceweb-lumen' ),
		'id'          => CRECEWEB_PRIMARY_SIDEBAR,
		'description' => __( 'Se muestra en las pantallas donde activás una barra lateral desde Personalizar > Diseño > Diseño de pantalla.', 'creceweb-lumen' ),
	) ) );

	register_sidebar( array_merge( $markup, array(
		'name' => __( 'Barra superior', 'creceweb-lumen' ),
		'id' => CRECEWEB_TOP_BAR_WIDGET_AREA,
		'description' => __( 'Se muestra antes de la cabecera cuando contiene contenido.', 'creceweb-lumen' ),
	) ) );
	register_sidebar( array_merge( $markup, array(
		'name' => __( 'Fila completa del pie', 'creceweb-lumen' ),
		'id' => CRECEWEB_FOOTER_BAR_WIDGET_AREA,
		'description' => __( 'Fila de ancho completo para bloques o widgets del pie. Podés usarla para logo, navegación, contacto o llamadas a la acción.', 'creceweb-lumen' ),
	) ) );

	$social_markup = get_social_widget_area_markup();
	register_sidebar( array_merge( $social_markup, array(
		'name'        => __( 'Redes sociales · Cabecera', 'creceweb-lumen' ),
		'id'          => CRECEWEB_HEADER_SOCIAL_WIDGET_AREA,
		'description' => __( 'Agregá el bloque nativo Iconos sociales. Puede usarse de forma independiente o como respaldo del modo compartido. La ubicación y apariencia se configuran en Personalizar > Diseño > Redes sociales.', 'creceweb-lumen' ),
	) ) );
	register_sidebar( array_merge( $social_markup, array(
		'name'        => __( 'Redes sociales · Pie', 'creceweb-lumen' ),
		'id'          => CRECEWEB_FOOTER_SOCIAL_WIDGET_AREA,
		'description' => __( 'Agregá el bloque nativo Iconos sociales. En modo compartido, esta área alimenta cabecera y pie. La ubicación y apariencia se configuran en Personalizar > Diseño > Redes sociales.', 'creceweb-lumen' ),
	) ) );

	foreach ( CRECEWEB_FOOTER_WIDGET_COLUMNS as $index => $sidebar_id ) {
		register_sidebar( array_merge( $markup, array(
			/* translators: %d: Footer widget column number. */
			'name' => sprintf( __( 'Widget del pie de página %d', 'creceweb-lumen' ), $index + 1 ),
			'id' => $sidebar_id,
			/* translators: %d: Footer widget column number. */
			'description' => sprintf( __( 'Columna %d del pie de página. Se muestra solo cuando contiene contenido.', 'creceweb-lumen' ), $index + 1 ),
		) ) );
	}
}
add_action( 'widgets_init', __NAMESPACE__ . '\register_widget_areas' );

/**
 * Creates the native Widgets panel only when WordPress has not registered it.
 *
 * @param \WP_Customize_Manager $wp_customize Customizer manager.
 * @return void
 */
function ensure_widgets_customizer_panel( \WP_Customize_Manager $wp_customize ): void {
	if ( $wp_customize->get_panel( 'widgets' ) ) {
		return;
	}

	$wp_customize->add_panel(
		new \WP_Customize_Panel(
			$wp_customize,
			'widgets',
			array(
				'title'       => __( 'Widgets', 'creceweb-lumen' ),
				'description' => __( 'Administrá las áreas de widgets del sitio.', 'creceweb-lumen' ),
				'priority'    => 110,
				'active_callback' => '__return_true',
			)
		)
	);
}

/**
 * Ensures the primary sidebar is always available inside Personalizar > Widgets.
 *
 * WordPress normally registers both the Sidebar Section and Widget Area Control.
 * Previous versions only recreated the section when the native registration was
 * unavailable, which left it empty and therefore hidden. This fallback restores
 * the complete native pair so the area remains visible and editable.
 *
 * @param \WP_Customize_Manager $wp_customize Customizer manager.
 * @return void
 */
function ensure_primary_sidebar_customizer_section( \WP_Customize_Manager $wp_customize ): void {
	ensure_widgets_customizer_panel( $wp_customize );

	$section_id = 'sidebar-widgets-' . CRECEWEB_PRIMARY_SIDEBAR;
	$control_id = 'widget_area-' . CRECEWEB_PRIMARY_SIDEBAR;
	$section    = $wp_customize->get_section( $section_id );

	if ( $section ) {
		$section->title       = __( 'Barra lateral principal', 'creceweb-lumen' );
		$section->description = __( 'Agregá widgets para las pantallas que usan barra lateral.', 'creceweb-lumen' );
		$section->priority    = 10;
		$section->panel       = 'widgets';
	} elseif ( class_exists( '\WP_Customize_Sidebar_Section' ) ) {
		$wp_customize->add_section(
			new \WP_Customize_Sidebar_Section(
				$wp_customize,
				$section_id,
				array(
					'title'       => __( 'Barra lateral principal', 'creceweb-lumen' ),
					'description' => __( 'Agregá widgets para las pantallas que usan barra lateral.', 'creceweb-lumen' ),
					'panel'       => 'widgets',
					'priority'    => 10,
					'sidebar_id'  => CRECEWEB_PRIMARY_SIDEBAR,
				)
			)
		);
	} else {
		$wp_customize->add_section(
			$section_id,
			array(
				'title'       => __( 'Barra lateral principal', 'creceweb-lumen' ),
				'description' => __( 'Agregá widgets para las pantallas que usan barra lateral.', 'creceweb-lumen' ),
				'panel'       => 'widgets',
				'priority'    => 10,
			)
		);
	}

	if ( ! $wp_customize->get_control( $control_id ) && class_exists( '\WP_Widget_Area_Customize_Control' ) ) {
		$wp_customize->add_control(
			new \WP_Widget_Area_Customize_Control(
				$wp_customize,
				$control_id,
				array(
					'section'    => $section_id,
					'sidebar_id' => CRECEWEB_PRIMARY_SIDEBAR,
					'priority'   => 10,
				)
			)
		);
	}
}
add_action( 'customize_register', __NAMESPACE__ . '\ensure_primary_sidebar_customizer_section', 999 );

/**
 * Resolves the requested screen sidebar layout for a template context.
 *
 * Context-specific values can inherit the global decision. A sidebar never
 * reserves empty space when its native widget area has no content.
 *
 * @param string $context archive, single or page.
 * @return string none, left or right.
 */
function get_sidebar_layout( string $context ): string {
	$settings = get_customizations();
	$global   = (string) ( $settings['sidebar_layout'] ?? 'right' );
	$map      = array(
		'archive' => 'archive_sidebar_layout',
		'single'  => 'single_sidebar_layout',
		'page'    => 'page_sidebar_layout',
	);
	$key      = $map[ $context ] ?? '';
	$layout   = $key ? (string) ( $settings[ $key ] ?? 'inherit' ) : 'inherit';

	if ( 'inherit' === $layout ) {
		$layout = $global;
	}

	/**
	 * Filters the requested sidebar layout for the current template context.
	 *
	 * Pro can resolve a contextual sidebar choice here. The effective layout
	 * still checks the native widget area, so empty sidebars reserve no space.
	 *
	 * @param string $layout  Requested layout: none, left or right.
	 * @param string $context Template context: archive, single or page.
	 */
	$layout = apply_filters( 'creceweb_lumen_sidebar_layout', $layout, $context );

	return in_array( $layout, array( 'left', 'right' ), true ) ? $layout : 'none';
}

/**
 * Returns the effective sidebar layout after checking whether it has widgets.
 *
 * @param string $context archive, single or page.
 * @return string none, left or right.
 */
function get_effective_sidebar_layout( string $context ): string {
	$layout = get_sidebar_layout( $context );
	return ( 'none' !== $layout && is_active_sidebar( CRECEWEB_PRIMARY_SIDEBAR ) ) ? $layout : 'none';
}

/**
 * @param string $sidebar_id Registered sidebar ID.
 * @return string
 */
function render_widget_area( string $sidebar_id ): string {
	ob_start();
	$has_widgets = dynamic_sidebar( $sidebar_id );
	$widgets = trim( (string) ob_get_clean() );
	return ( ! $has_widgets && '' === $widgets ) ? '' : $widgets;
}


/**
 * Removes only Lumen widget wrapper IDs from fallback Social Icons markup.
 *
 * The native Social Icons block itself does not require those wrapper IDs. This
 * keeps the HTML valid when one configured widget area is intentionally reused
 * in both the header and footer.
 *
 * @param string $markup Rendered widget-area markup.
 * @return string
 */
function strip_social_widget_wrapper_ids( string $markup ): string {
	return (string) preg_replace(
		'/<div\s+id="[^"]+"\s+class="cw-social-widget\s+/i',
		'<div class="cw-social-widget ',
		$markup
	);
}


/**
 * Returns the single shared Social Icons source used by both theme locations.
 *
 * The footer area is the canonical shared source because social links most
 * commonly live there. For backwards compatibility, the header area is used
 * when the footer area is empty. Wrapper IDs are removed because shared mode
 * may render the same native block twice in one document.
 *
 * @return array{markup:string,source:string}
 */
function get_shared_social_widgets(): array {
	$widgets = render_widget_area( CRECEWEB_FOOTER_SOCIAL_WIDGET_AREA );
	$source  = 'footer-shared';

	if ( '' === $widgets ) {
		$widgets = render_widget_area( CRECEWEB_HEADER_SOCIAL_WIDGET_AREA );
		$source  = 'header-shared';
	}

	if ( '' !== $widgets ) {
		$widgets = strip_social_widget_wrapper_ids( $widgets );
	}

	return array(
		'markup' => $widgets,
		'source' => $source,
	);
}

/**
 * Renders the dedicated header Social Icons area when it is enabled and has content.
 *
 * @return string
 */
function render_header_social_widgets(): string {
	$settings = get_customizations();
	$position = (string) ( $settings['social_header_position'] ?? 'hidden' );
	if ( 'hidden' === $position ) {
		return '';
	}

	$mode = (string) ( $settings['social_content_mode'] ?? 'shared' );

	if ( 'separate' === $mode ) {
		$source  = 'header';
		$widgets = render_widget_area( CRECEWEB_HEADER_SOCIAL_WIDGET_AREA );
	} else {
		$shared  = get_shared_social_widgets();
		$source  = $shared['source'];
		$widgets = $shared['markup'];
	}

	if ( '' === $widgets ) {
		return '';
	}

	$position_class = 'before_navigation' === $position ? 'cw-social-region--header-before' : 'cw-social-region--header-after';

	return sprintf(
		'<nav class="cw-social-region cw-social-region--header %1$s" data-creceweb-social-header="1" data-creceweb-social-source="%2$s" aria-label="%3$s">%4$s</nav>',
		esc_attr( $position_class ),
		esc_attr( $source ),
		esc_attr__( 'Redes sociales', 'creceweb-lumen' ),
		$widgets
	);
}

/**
 * Renders the dedicated footer Social Icons area when it is enabled and has content.
 *
 * @return string
 */
function render_footer_social_widgets(): string {
	$settings = get_customizations();
	$position = (string) ( $settings['social_footer_position'] ?? 'hidden' );
	if ( 'hidden' === $position ) {
		return '';
	}

	$mode = (string) ( $settings['social_content_mode'] ?? 'shared' );

	if ( 'separate' === $mode ) {
		$source  = 'footer';
		$widgets = render_widget_area( CRECEWEB_FOOTER_SOCIAL_WIDGET_AREA );
	} else {
		$shared  = get_shared_social_widgets();
		$source  = $shared['source'];
		$widgets = $shared['markup'];
	}

	if ( '' === $widgets ) {
		return '';
	}

	$alignment = (string) ( $settings['social_footer_alignment'] ?? 'center' );
	if ( ! in_array( $alignment, array( 'left', 'center', 'right' ), true ) ) {
		$alignment = 'center';
	}

	return sprintf(
		'<nav class="cw-social-region cw-social-region--footer cw-social-region--footer-%1$s" data-creceweb-social-footer="1" data-creceweb-social-source="%2$s" aria-label="%3$s">%4$s</nav>',
		esc_attr( $alignment ),
		esc_attr( $source ),
		esc_attr__( 'Redes sociales', 'creceweb-lumen' ),
		$widgets
	);
}

/**
 * @return string
 */
function render_top_bar_widgets(): string {
	$settings = get_customizations();
	if ( '1' !== (string) ( $settings['top_bar_enabled'] ?? '0' ) ) {
		return '';
	}
	$widgets = render_widget_area( CRECEWEB_TOP_BAR_WIDGET_AREA );
	if ( '' === $widgets ) {
		return '';
	}

	/**
	 * Filters extra markup appended inside the native Lumen top bar.
	 *
	 * This runs only when the top bar is enabled and has widget content, so
	 * integrations can use the area without creating a new bar.
	 *
	 * @param string $extra      Extra escaped markup to append.
	 * @param string $sidebar_id Top-bar widget area id.
	 */
	$extra = apply_filters( 'creceweb_lumen_top_bar_widgets_extra', '', CRECEWEB_TOP_BAR_WIDGET_AREA );
	$extra = is_string( $extra ) ? $extra : '';

	return sprintf(
		'<section class="cw-top-bar-widgets" data-creceweb-top-bar-widgets="1" aria-label="%1$s"><div class="cw-top-bar-widgets__inner wp-block-group">%2$s%3$s</div></section>',
		esc_attr__( 'Barra superior', 'creceweb-lumen' ),
		$widgets,
		$extra
	);
}

/**
 * @return string
 */
function render_footer_widgets(): string {
	$general = render_widget_area( CRECEWEB_FOOTER_BAR_WIDGET_AREA );
	$columns = array();
	foreach ( CRECEWEB_FOOTER_WIDGET_COLUMNS as $index => $sidebar_id ) {
		$widgets = render_widget_area( $sidebar_id );
		if ( '' !== $widgets ) {
			$columns[] = sprintf( '<div class="cw-footer-widgets__column cw-footer-widgets__column--%1$d">%2$s</div>', $index + 1, $widgets );
		}
	}
	if ( '' === $general && empty( $columns ) ) {
		return '';
	}
	$markup = '';
	if ( '' !== $general ) {
		$markup .= sprintf( '<div class="cw-footer-widgets__section cw-footer-widgets__section--general"><div class="cw-footer-widgets__inner cw-footer-widgets__inner--single">%1$s</div></div>', $general );
	}
	if ( ! empty( $columns ) ) {
		$markup .= sprintf( '<div class="cw-footer-widgets__section cw-footer-widgets__section--columns"><div class="cw-footer-widgets__inner cw-footer-widgets__inner--columns">%1$s</div></div>', implode( '', $columns ) );
	}
	return sprintf( '<section class="cw-footer-widgets" data-creceweb-footer-widgets="1" aria-label="%1$s">%2$s</section>', esc_attr__( 'Widgets del pie de página', 'creceweb-lumen' ), $markup );
}
