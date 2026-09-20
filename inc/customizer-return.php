<?php
/**
 * Safe return target for the WordPress Customizer.
 *
 * @package CreceWebLumen
 */

namespace CreceWeb\Lumen;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Decodes a possibly nested URL value a limited number of times.
 *
 * WordPress' theme upload upgrader can pass the return target through more than
 * one query-string layer. Limiting the loop prevents malformed input from
 * causing unbounded work while still covering the theme upload flow.
 *
 * @param string $value Raw URL value.
 * @return string
 */
function decode_customizer_return_url( string $value ): string {
	$value = trim( $value );

	for ( $attempt = 0; $attempt < 3; $attempt++ ) {
		$decoded = rawurldecode( $value );
		if ( $decoded === $value ) {
			break;
		}
		$value = $decoded;
	}

	return $value;
}

/**
 * Returns whether a URL points to the theme-upload updater endpoint.
 *
 * Returning to that endpoint without the original multipart POST makes
 * WordPress' File_Upload_Upgrader stop with “Please select a file”.
 *
 * @param string $url Candidate return URL.
 * @return bool
 */
function is_unsafe_customizer_return_url( string $url ): bool {
	$url = decode_customizer_return_url( $url );
	if ( '' === $url ) {
		return false;
	}

	$parts = wp_parse_url( $url );
	if ( ! is_array( $parts ) ) {
		return false;
	}

	$path = isset( $parts['path'] ) ? (string) $parts['path'] : '';
	if ( 'update.php' !== basename( $path ) ) {
		return false;
	}

	$query = array();
	if ( isset( $parts['query'] ) ) {
		parse_str( (string) $parts['query'], $query );
	}

	$action = isset( $query['action'] ) && is_scalar( $query['action'] )
		? sanitize_key( (string) $query['action'] )
		: '';

	return 'upload-theme' === $action;
}

/**
 * Normalizes the explicit `return` request value before Customizer bootstrap.
 *
 * @return bool True when an unsafe return target was replaced.
 */
function normalize_customizer_return_target(): bool {
	global $pagenow;

	if ( 'customize.php' !== $pagenow || ! isset( $_REQUEST['return'] ) ) {
		return false;
	}

	$return_raw = wp_unslash( $_REQUEST['return'] );
	if ( ! is_string( $return_raw ) || ! is_unsafe_customizer_return_url( $return_raw ) ) {
		return false;
	}

	$safe_return        = admin_url( 'themes.php' );
	$_REQUEST['return'] = $safe_return;
	$_GET['return']     = $safe_return;

	return true;
}

/**
 * Revalidates the resolved return URL, including the HTTP referer fallback.
 *
 * @param \WP_Customize_Manager $wp_customize Customizer manager.
 * @return void
 */
function enforce_safe_customizer_return_url( \WP_Customize_Manager $wp_customize ): void {
	$return_url = $wp_customize->get_return_url();
	if ( is_unsafe_customizer_return_url( $return_url ) ) {
		$wp_customize->set_return_url( admin_url( 'themes.php' ) );
	}
}

normalize_customizer_return_target();
add_action( 'customize_register', __NAMESPACE__ . '\\enforce_safe_customizer_return_url', 1 );
