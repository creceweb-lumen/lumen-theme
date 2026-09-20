<?php
/**
 * Gutenberg block styles and starter patterns for CreceWeb Lumen.
 *
 * @package CreceWebLumen
 */

namespace CreceWeb\Lumen;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers lightweight block styles that reuse Lumen design tokens.
 *
 * @return void
 */
function register_lumen_block_styles(): void {
	if ( ! function_exists( 'register_block_style' ) ) {
		return;
	}

	register_block_style(
		'core/button',
		array(
			'name'         => 'lumen-soft',
			'label'        => __( 'Lumen suave', 'creceweb-lumen' ),
			'inline_style' => '.wp-block-button.is-style-lumen-soft .wp-block-button__link{background:var(--cw-color-surface,#fff);color:var(--cw-color-heading,#0f172a);border:1px solid var(--cw-color-border,#cbd5e1);box-shadow:0 12px 30px rgba(15,23,42,.08);} .wp-block-button.is-style-lumen-soft .wp-block-button__link:hover{color:var(--cw-color-accent,#059669);border-color:var(--cw-color-accent,#059669);}',
		)
	);

	register_block_style(
		'core/group',
		array(
			'name'         => 'lumen-card',
			'label'        => __( 'Tarjeta Lumen', 'creceweb-lumen' ),
			'inline_style' => '.wp-block-group.is-style-lumen-card{background:var(--cw-color-surface,#fff);border:1px solid var(--cw-content-box-color,var(--cw-color-border,#cbd5e1));border-radius:22px;box-shadow:0 18px 45px rgba(15,23,42,.08);padding:clamp(1.25rem,3vw,2.25rem);}',
		)
	);
}
add_action( 'init', __NAMESPACE__ . '\\register_lumen_block_styles' );

/**
 * Registers editable starter patterns.
 *
 * @return void
 */
function register_lumen_block_patterns(): void {
	if ( ! function_exists( 'register_block_pattern' ) ) {
		return;
	}

	// The Theme starter is a fallback. Hide it only when a compatible Lite
	// installation is ready to own the coordinated catalog.
	if ( function_exists( 'cw_lumen_lite_is_theme_compatible' ) && cw_lumen_lite_is_theme_compatible() ) {
		return;
	}

	if ( function_exists( 'register_block_pattern_category' ) ) {
		register_block_pattern_category(
			'creceweb-lumen',
			array( 'label' => __( 'CreceWeb Lumen', 'creceweb-lumen' ) )
		);
	}

	$heading = esc_html__( 'Construí una presencia clara, rápida y profesional', 'creceweb-lumen' );
	$text    = esc_html__( 'Usá este hero como punto de partida para una portada, una página institucional o una landing simple con Gutenberg.', 'creceweb-lumen' );
	$button  = esc_html__( 'Solicitar presupuesto', 'creceweb-lumen' );

	$content = '<!-- wp:cover {"dimRatio":0,"minHeight":560,"isDark":false,"align":"full","className":"cw-lumen-pattern-hero"} -->'
		. '<div class="wp-block-cover alignfull is-light cw-lumen-pattern-hero" style="min-height:560px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span><div class="wp-block-cover__inner-container">'
		. '<!-- wp:group {"layout":{"type":"constrained","contentSize":"760px"}} -->'
		. '<div class="wp-block-group">'
		. '<!-- wp:heading {"level":1,"textAlign":"center"} --><h1 class="wp-block-heading has-text-align-center">' . $heading . '</h1><!-- /wp:heading -->'
		. '<!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">' . $text . '</p><!-- /wp:paragraph -->'
		. '<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#contacto">' . $button . '</a></div><!-- /wp:button --></div><!-- /wp:buttons -->'
		. '</div><!-- /wp:group -->'
		. '</div></div><!-- /wp:cover -->';

	register_block_pattern(
		'creceweb-lumen/hero-cta',
		array(
			'title'       => __( 'Hero Lumen con CTA', 'creceweb-lumen' ),
			'description' => __( 'Hero editable de ancho completo con título, texto introductorio y botón principal.', 'creceweb-lumen' ),
			'categories'  => array( 'creceweb-lumen' ),
			'content'     => $content,
		)
	);
}
add_action( 'init', __NAMESPACE__ . '\\register_lumen_block_patterns' );
