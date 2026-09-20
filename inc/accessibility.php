<?php
/**
 * Accessibility enhancements that do not alter user content.
 *
 * @package CreceWebLumen
 */

namespace CreceWeb\Lumen;

/**
 * Outputs a keyboard-visible skip link before the page content.
 *
 * @return void
 */
function render_skip_link(): void {
	printf(
		'<a class="cw-skip-link screen-reader-text" href="#cw-main-content">%s</a>',
		esc_html__( 'Saltar al contenido principal', 'creceweb-lumen' )
	);
}
add_action( 'wp_body_open', __NAMESPACE__ . '\\render_skip_link', 5 );

/**
 * Outputs the optional back-to-top control.
 *
 * @return void
 */
function render_floating_action(): void {
	$config = get_floating_action_config();
	$action = (string) ( $config['action'] ?? 'back_to_top' );

	// The landing canvas is intentionally free of theme navigation controls.
	if ( 'back_to_top' === $action && is_page_template( 'page-templates/landing-canvas.php' ) ) {
		return;
	}

	if ( 'back_to_top' === $action ) {
		printf(
			'<span id="cw-page-top" class="cw-page-top-anchor" tabindex="-1"></span><a class="cw-floating-action cw-floating-action--back-to-top cw-back-to-top" href="#cw-page-top" aria-label="%1$s"><svg aria-hidden="true" viewBox="0 0 24 24" focusable="false"><path d="M12 4.5 5.5 11l1.06 1.06 4.69-4.69V19.5h1.5V7.37l4.69 4.69L18.5 11 12 4.5Z" fill="currentColor"/></svg><span class="screen-reader-text">%2$s</span></a>',
			esc_attr__( 'Volver arriba', 'creceweb-lumen' ),
			esc_html__( 'Volver arriba', 'creceweb-lumen' )
		);
		return;
	}
}
add_action( 'wp_body_open', __NAMESPACE__ . '\\render_floating_action', 20 );
