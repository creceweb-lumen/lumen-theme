<?php
/**
 * Native WordPress navigation for CreceWeb Lumen.
 *
 * @package CreceWebLumen
 */

namespace CreceWeb\Lumen;

const CRECEWEB_PRIMARY_MENU_LOCATION = 'primary';
// Legacy location retained for integrations; it is no longer registered or rendered by the theme.
const CRECEWEB_FOOTER_MENU_LOCATION  = 'footer';

/**
 * Registers the primary menu controlled from Appearance > Menus and the
 * native Customizer menu panel. Footer menus are built with widgets/blocks.
 *
 * @return void
 */
function register_navigation_locations(): void {
	register_nav_menus(
		array(
			CRECEWEB_PRIMARY_MENU_LOCATION => __( 'Menú principal', 'creceweb-lumen' ),
		)
	);
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\\register_navigation_locations', 20 );

/**
 * Returns the native menu rendered for one registered location.
 *
 * The primary menu includes the structural elements required by the mobile
 * overlay/drawer: a backdrop, a close action and a stable menu panel.
 *
 * @param string $location Menu location.
 * @return string
 */
function render_navigation( string $location = CRECEWEB_PRIMARY_MENU_LOCATION ): string {
	$location   = CRECEWEB_FOOTER_MENU_LOCATION === $location ? CRECEWEB_FOOTER_MENU_LOCATION : CRECEWEB_PRIMARY_MENU_LOCATION;
	$is_primary = CRECEWEB_PRIMARY_MENU_LOCATION === $location;
	$label      = $is_primary ? __( 'Navegación principal', 'creceweb-lumen' ) : __( 'Navegación del pie', 'creceweb-lumen' );
	$container  = 'cw-' . $location . '-menu-container';
	$menu_id    = 'cw-' . $location . '-menu';
	$classes    = $is_primary ? 'cw-classic-navigation cw-classic-navigation--primary' : 'cw-classic-navigation cw-classic-navigation--footer';

	$contextual_menu_id = get_contextual_menu_id( $location );

	$menu_markup = wp_nav_menu(
		array(
			'theme_location' => $location,
			'menu'           => $contextual_menu_id > 0 ? $contextual_menu_id : '',
			'container'      => false,
			'menu_class'     => 'cw-classic-navigation__menu',
			'menu_id'        => $menu_id,
			'fallback_cb'    => $is_primary ? __NAMESPACE__ . '\\render_page_menu_fallback' : false,
			'depth'          => 0,
			'echo'           => false,
		)
	);

	if ( ! is_string( $menu_markup ) || '' === trim( $menu_markup ) ) {
		return '';
	}

	$toggle   = '';
	$backdrop = '';
	$close    = '';

	if ( $is_primary ) {
		$toggle = sprintf(
			'<button class="cw-classic-navigation__toggle" type="button" aria-controls="%1$s" aria-expanded="false"><span class="screen-reader-text">%2$s</span><span aria-hidden="true">☰</span></button>',
			esc_attr( $container ),
			esc_html__( 'Abrir menú', 'creceweb-lumen' )
		);

		$backdrop = sprintf(
			'<button class="cw-classic-navigation__backdrop" type="button" tabindex="-1" aria-hidden="true" aria-label="%1$s"></button>',
			esc_attr__( 'Cerrar menú', 'creceweb-lumen' )
		);

		$close = sprintf(
			'<button class="cw-classic-navigation__close" type="button"><span class="screen-reader-text">%1$s</span><span aria-hidden="true">×</span></button>',
			esc_html__( 'Cerrar menú', 'creceweb-lumen' )
		);
	}

	$menu_panel = sprintf(
		'<div id="%1$s" class="cw-classic-navigation__menu-container">%2$s%3$s</div>',
		esc_attr( $container ),
		$close,
		$menu_markup
	);

	return sprintf(
		'<nav id="cw-%1$s-navigation" class="%2$s" aria-label="%3$s">%4$s%5$s%6$s</nav>',
		esc_attr( $location ),
		esc_attr( $classes ),
		esc_attr( $label ),
		$toggle,
		$backdrop,
		$menu_panel
	);
}

/**
 * Resolves a contextual navigation menu without exposing Lumen's native
 * wp_nav_menu() arguments to external code.
 *
 * Pro may select only the numeric ID of an existing WordPress menu. Lumen
 * retains its own accessible wrapper, walker behavior, fallback callback,
 * depth and echo settings. The old argument filter remains temporarily for
 * bridge compatibility, but only its menu key is read.
 *
 * @param string $location Registered menu location.
 * @return int
 */
function get_contextual_menu_id( string $location ): int {
	$menu_id = absint( apply_filters( 'creceweb_lumen_navigation_menu_id', 0, $location ) );

	/**
	 * Transitional bridge filter. Only the menu key is honored; all other
	 * native wp_nav_menu() arguments are deliberately ignored.
	 *
	 * @param array{menu:int} $proposed_menu Contextual menu proposal.
	 * @param string          $location      Registered menu location.
	 */
	$legacy_proposal = apply_filters( 'creceweb_lumen_navigation_args', array( 'menu' => $menu_id ), $location );
	if ( is_array( $legacy_proposal ) && array_key_exists( 'menu', $legacy_proposal ) ) {
		$menu_id = absint( $legacy_proposal['menu'] );
	}

	if ( $menu_id < 1 ) {
		return 0;
	}

	$menu = wp_get_nav_menu_object( $menu_id );
	if ( ! $menu || is_wp_error( $menu ) || empty( $menu->term_id ) ) {
		return 0;
	}

	return (int) $menu->term_id;
}

/**
 * Uses page links only as a safe temporary fallback when the primary menu was
 * not created yet. It preserves the same list markup required by the
 * responsive menu script, unlike WordPress' default wp_page_menu fallback.
 *
 * @param array<string,mixed> $args Navigation arguments.
 * @return string
 */
function render_page_menu_fallback( array $args = array() ): string {
	$items = wp_list_pages(
		array(
			'title_li'   => '',
			'echo'       => false,
			'depth'      => 1,
			'number'     => 4,
			'sort_column' => 'menu_order,post_title',
		)
	);

	if ( ! is_string( $items ) || '' === trim( $items ) ) {
		return '';
	}

	$home = sprintf(
		'<li class="menu-item menu-item-home"><a href="%1$s">%2$s</a></li>',
		esc_url( home_url( '/' ) ),
		esc_html__( 'Inicio', 'creceweb-lumen' )
	);

	return sprintf(
		'<ul id="%1$s" class="%2$s">%3$s%4$s</ul>',
		esc_attr( isset( $args['menu_id'] ) ? (string) $args['menu_id'] : 'cw-primary-menu' ),
		esc_attr( trim( ( isset( $args['menu_class'] ) ? (string) $args['menu_class'] : 'cw-classic-navigation__menu' ) . ' cw-classic-navigation__menu--fallback' ) ),
		$home,
		$items
	);
}
