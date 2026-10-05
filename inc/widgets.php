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
		'description' => __( 'Agregá el bloque nativo Iconos sociales. Puede usarse de forma independiente, dentro del menú móvil o como respaldo del modo compartido. La ubicación y apariencia se configuran en Personalizar > Diseño > Redes sociales.', 'creceweb-lumen' ),
	) ) );
	register_sidebar( array_merge( $social_markup, array(
		'name'        => __( 'Redes sociales · Pie', 'creceweb-lumen' ),
		'id'          => CRECEWEB_FOOTER_SOCIAL_WIDGET_AREA,
		'description' => __( 'Agregá el bloque nativo Iconos sociales. En modo compartido, esta área alimenta cabecera, pie y menú móvil. La ubicación y apariencia se configuran en Personalizar > Diseño > Redes sociales.', 'creceweb-lumen' ),
	) ) );

	foreach ( CRECEWEB_FOOTER_WIDGET_COLUMNS as $index => $sidebar_id ) {
		/* translators: %d: Footer widget column number. */
		$name = sprintf( __( 'Widget del pie de página %d', 'creceweb-lumen' ), $index + 1 );
		/* translators: %d: Footer widget column number. */
		$description = sprintf( __( 'Columna %d del pie de página. Se muestra solo cuando contiene contenido.', 'creceweb-lumen' ), $index + 1 );

		register_sidebar( array_merge( $markup, array(
			'name'        => $name,
			'id'          => $sidebar_id,
			'description' => $description,
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
 * Resolves the widget-area ID used as the sidebar for a template context.
 *
 * Theme owns the primary sidebar. Compatible extensions may supply another
 * already-registered widget area without changing Theme templates.
 *
 * @param string $context archive, single or page.
 * @return string
 */
function get_sidebar_id( string $context ): string {
	$sidebar_id = (string) apply_filters( 'creceweb_lumen_sidebar_id', CRECEWEB_PRIMARY_SIDEBAR, $context );
	$sidebar_id = sanitize_key( $sidebar_id );

	if ( '' === $sidebar_id ) {
		return CRECEWEB_PRIMARY_SIDEBAR;
	}

	global $wp_registered_sidebars;
	if ( is_array( $wp_registered_sidebars ) && isset( $wp_registered_sidebars[ $sidebar_id ] ) ) {
		return $sidebar_id;
	}

	return CRECEWEB_PRIMARY_SIDEBAR;
}

/**
 * Infers the sidebar context used by sidebar.php.
 *
 * @return string archive, single or page.
 */
function get_current_sidebar_context(): string {
	if ( is_singular( 'post' ) ) {
		return 'single';
	}

	if ( is_page() || is_front_page() ) {
		return 'page';
	}

	return 'archive';
}

/**
 * Returns the effective sidebar layout after checking whether its widget area has content.
 *
 * @param string $context archive, single or page.
 * @return string none, left or right.
 */
function get_effective_sidebar_layout( string $context ): string {
	$layout     = get_sidebar_layout( $context );
	$sidebar_id = get_sidebar_id( $context );

	return ( 'none' !== $layout && is_active_sidebar( $sidebar_id ) ) ? $layout : 'none';
}

/**
 * @param string $sidebar_id Registered sidebar ID.
 * @return string
 */
function render_widget_area( string $sidebar_id ): string {
	ob_start();
	$has_widgets = dynamic_sidebar( $sidebar_id );
	$widgets     = trim( (string) ob_get_clean() );

	// In the Customizer, WordPress wraps every dynamic_sidebar() call in
	// marker comments, even when the sidebar has no widgets. Those markers are
	// preview infrastructure, not user content, and must not suppress native
	// fallbacks that depend on an area being genuinely empty.
	$meaningful_widgets = preg_replace( '/<!--dynamic_sidebar_(?:before|after):.*?-->/s', '', $widgets );
	$meaningful_widgets = is_string( $meaningful_widgets ) ? trim( $meaningful_widgets ) : $widgets;

	return ( ! $has_widgets && '' === $meaningful_widgets ) ? '' : $widgets;
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
 * Renders Social Icons inside the compact primary navigation when enabled.
 *
 * Shared mode reuses the canonical shared source. Separate mode reuses the
 * header social area so URLs remain native WordPress widget/block content.
 * Wrapper IDs are removed because the same configured source may also appear
 * elsewhere in the document.
 *
 * @return string
 */
function render_mobile_menu_social_widgets(): string {
	$settings = get_customizations();
	if ( '1' !== (string) ( $settings['social_mobile_menu_enabled'] ?? '0' ) ) {
		return '';
	}

	$mode = (string) ( $settings['social_content_mode'] ?? 'shared' );

	if ( 'separate' === $mode ) {
		$source  = 'header-mobile-menu';
		$widgets = render_widget_area( CRECEWEB_HEADER_SOCIAL_WIDGET_AREA );
		if ( '' !== $widgets ) {
			$widgets = strip_social_widget_wrapper_ids( $widgets );
		}
	} else {
		$shared  = get_shared_social_widgets();
		$source  = $shared['source'] . '-mobile-menu';
		$widgets = $shared['markup'];
	}

	if ( '' === $widgets ) {
		return '';
	}

	return sprintf(
		'<div class="cw-social-region cw-social-region--mobile-menu" data-creceweb-social-mobile-menu="1" data-creceweb-social-source="%1$s" role="group" aria-label="%2$s"><span class="cw-social-region--mobile-menu__label">%3$s</span>%4$s</div>',
		esc_attr( $source ),
		esc_attr__( 'Redes sociales', 'creceweb-lumen' ),
		esc_html__( 'Redes', 'creceweb-lumen' ),
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
 * Returns a compact set of top-level navigation links for the editorial
 * footer fallback. The Theme reuses the assigned primary menu so choosing an
 * editorial footer never requires creating duplicate navigation content.
 *
 * @return array<int,array{title:string,url:string}>
 */
function get_editorial_footer_navigation_items(): array {
	$items     = array();
	$locations = get_nav_menu_locations();
	$menu_id   = isset( $locations[ CRECEWEB_PRIMARY_MENU_LOCATION ] ) ? absint( $locations[ CRECEWEB_PRIMARY_MENU_LOCATION ] ) : 0;

	if ( $menu_id > 0 ) {
		$menu_items = wp_get_nav_menu_items( $menu_id );
		if ( is_array( $menu_items ) ) {
			foreach ( $menu_items as $menu_item ) {
				if ( ! is_object( $menu_item ) || '0' !== (string) ( $menu_item->menu_item_parent ?? '0' ) ) {
					continue;
				}
				$title = isset( $menu_item->title ) ? trim( wp_strip_all_tags( (string) $menu_item->title ) ) : '';
				$url   = isset( $menu_item->url ) ? (string) $menu_item->url : '';
				if ( '' === $title || '' === $url ) {
					continue;
				}
				$items[] = array( 'title' => $title, 'url' => $url );
			}
		}
	}

	if ( empty( $items ) ) {
		$pages = get_pages(
			array(
				'number'      => 6,
				'post_status' => 'publish',
				'sort_column' => 'menu_order,post_title',
			)
		);
		foreach ( $pages as $page ) {
			if ( ! $page instanceof \WP_Post ) {
				continue;
			}
			$items[] = array(
				'title' => get_the_title( $page ),
				'url'   => get_permalink( $page ),
			);
		}
	}

	return array_slice( $items, 0, 8 );
}

/**
 * @param array<int,array{title:string,url:string}> $items Links to render.
 * @return string
 */
function render_editorial_footer_link_list( array $items ): string {
	if ( empty( $items ) ) {
		return '';
	}

	$links = '';
	foreach ( $items as $item ) {
		$title = isset( $item['title'] ) ? (string) $item['title'] : '';
		$url   = isset( $item['url'] ) ? (string) $item['url'] : '';
		if ( '' === $title || '' === $url ) {
			continue;
		}
		$links .= sprintf( '<li><a href="%1$s">%2$s</a></li>', esc_url( $url ), esc_html( $title ) );
	}

	return '' !== $links ? '<ul class="cw-footer-editorial__menu">' . $links . '</ul>' : '';
}

/**
 * Builds the three native Editorial fallback columns independently so each one
 * can be replaced by its matching WordPress widget area without suppressing the
 * other automatic columns.
 *
 * @return array<int,string> Column number => inner column markup.
 */
function get_editorial_footer_fallback_columns(): array {
	$name        = trim( (string) get_bloginfo( 'name' ) );
	$description = trim( (string) get_bloginfo( 'description' ) );
	$home_url    = home_url( '/' );
	$items       = get_editorial_footer_navigation_items();
	$split       = max( 1, (int) ceil( count( $items ) / 2 ) );
	$first       = array_slice( $items, 0, $split );
	$second      = array_slice( $items, $split );

	$identity = '<aside class="cw-widget cw-footer-editorial__identity">';
	if ( '' !== $name ) {
		$identity .= sprintf( '<p class="cw-footer-editorial__brand"><a href="%1$s">%2$s</a></p>', esc_url( $home_url ), esc_html( $name ) );
	}
	if ( '' !== $description ) {
		$identity .= sprintf( '<p class="cw-footer-editorial__description">%s</p>', esc_html( $description ) );
	}
	$identity .= '</aside>';

	$columns = array( 1 => $identity );

	$first_list = render_editorial_footer_link_list( $first );
	if ( '' !== $first_list ) {
		$columns[2] = '<aside class="cw-widget cw-footer-editorial__navigation"><h2 class="widget-title cw-widget__title">' . esc_html__( 'Navegación', 'creceweb-lumen' ) . '</h2>' . $first_list . '</aside>';
	}

	$second_list = render_editorial_footer_link_list( $second );
	if ( '' !== $second_list ) {
		$columns[3] = '<aside class="cw-widget cw-footer-editorial__navigation"><h2 class="widget-title cw-widget__title">' . esc_html__( 'Más', 'creceweb-lumen' ) . '</h2>' . $second_list . '</aside>';
	}

	return $columns;
}

/**
 * Renders useful default Editorial footer content when all three primary footer
 * columns are empty. The same column builders are also reused by the partial
 * fallback path in render_footer_widgets().
 *
 * @return string
 */
function render_editorial_footer_fallback(): string {
	$columns = array();
	foreach ( get_editorial_footer_fallback_columns() as $index => $content ) {
		$columns[] = sprintf( '<div class="cw-footer-widgets__column cw-footer-widgets__column--%1$d">%2$s</div>', $index, $content );
	}

	return sprintf(
		'<section class="cw-footer-widgets cw-footer-widgets--editorial-fallback" data-creceweb-footer-widgets="1" data-creceweb-footer-editorial-fallback="1" aria-label="%1$s"><div class="cw-footer-widgets__section cw-footer-widgets__section--columns"><div class="cw-footer-widgets__inner cw-footer-widgets__inner--columns">%2$s</div></div></section>',
		esc_attr__( 'Contenido del pie de página', 'creceweb-lumen' ),
		implode( '', $columns )
	);
}

/**
 * @return string
 */
function render_footer_widgets(): string {
	$general        = render_widget_area( CRECEWEB_FOOTER_BAR_WIDGET_AREA );
	$settings       = get_customizations();
	$is_editorial   = 'editorial' === (string) ( $settings['footer_layout_preset'] ?? 'classic' );
	$column_content = array();

	foreach ( CRECEWEB_FOOTER_WIDGET_COLUMNS as $index => $sidebar_id ) {
		$widgets = render_widget_area( $sidebar_id );
		if ( '' !== $widgets ) {
			$column_content[ $index + 1 ] = $widgets;
		}
	}

	if ( $is_editorial && '' === $general && empty( $column_content ) ) {
		return render_editorial_footer_fallback();
	}

	$uses_editorial_fallback = false;
	if ( $is_editorial ) {
		foreach ( get_editorial_footer_fallback_columns() as $index => $fallback ) {
			if ( ! isset( $column_content[ $index ] ) && '' !== $fallback ) {
				$column_content[ $index ] = $fallback;
				$uses_editorial_fallback = true;
			}
		}
		ksort( $column_content );
	}

	if ( '' === $general && empty( $column_content ) ) {
		return '';
	}

	$columns = array();
	foreach ( $column_content as $index => $content ) {
		$columns[] = sprintf( '<div class="cw-footer-widgets__column cw-footer-widgets__column--%1$d">%2$s</div>', $index, $content );
	}

	$markup = '';
	if ( '' !== $general ) {
		$markup .= sprintf( '<div class="cw-footer-widgets__section cw-footer-widgets__section--general"><div class="cw-footer-widgets__inner cw-footer-widgets__inner--single">%1$s</div></div>', $general );
	}
	if ( ! empty( $columns ) ) {
		$markup .= sprintf( '<div class="cw-footer-widgets__section cw-footer-widgets__section--columns"><div class="cw-footer-widgets__inner cw-footer-widgets__inner--columns">%1$s</div></div>', implode( '', $columns ) );
	}

	$classes       = 'cw-footer-widgets';
	$fallback_data = '';
	$aria_label    = esc_attr__( 'Widgets del pie de página', 'creceweb-lumen' );
	if ( $uses_editorial_fallback ) {
		$classes      .= ' cw-footer-widgets--editorial-fallback';
		$fallback_data = ' data-creceweb-footer-editorial-fallback="1"';
		$aria_label    = esc_attr__( 'Contenido del pie de página', 'creceweb-lumen' );
	}

	return sprintf( '<section class="%1$s" data-creceweb-footer-widgets="1"%2$s aria-label="%3$s">%4$s</section>', $classes, $fallback_data, $aria_label, $markup );
}
