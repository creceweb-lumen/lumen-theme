<?php
/**
 * Theme Details metadata localization.
 *
 * @package CreceWebLumen
 */

namespace CreceWeb\Lumen;

/**
 * Localizes CreceWeb Lumen metadata in Appearance > Themes > Theme Details.
 *
 * WordPress reads stylesheet headers before the theme text domain is available,
 * so the Description value from style.css cannot be translated there directly.
 * This filter replaces that rendered value after WordPress has loaded the
 * active theme and its translations.
 *
 * @param array<string, array<string, mixed>> $prepared_themes Theme data for JavaScript.
 * @return array<string, array<string, mixed>>
 */
function localize_theme_details_for_js( array $prepared_themes ): array {
	$theme_slug = get_template();

	if ( ! isset( $prepared_themes[ $theme_slug ] ) || ! is_array( $prepared_themes[ $theme_slug ] ) ) {
		return $prepared_themes;
	}

	$prepared_themes[ $theme_slug ]['name'] = esc_html__( 'CreceWeb Lumen', 'creceweb-lumen' );
	$prepared_themes[ $theme_slug ]['description'] = esc_html__(
		'Tema clásico híbrido para sitios profesionales, blogs y páginas comerciales. Incluye cabecera flexible, navegación accesible, barras laterales, áreas de widgets, plantillas de ancho completo, estilos para Gutenberg y compatibilidad cuidada con Elementor.',
		'creceweb-lumen'
	);

	return $prepared_themes;
}
add_filter( 'wp_prepare_themes_for_js', __NAMESPACE__ . '\localize_theme_details_for_js' );
