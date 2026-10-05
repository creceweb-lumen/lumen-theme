<?php
/**
 * Optional self-hosted Inter typography support.
 *
 * The default system stacks never trigger a font request or remote download.
 * Inter is fetched server-side only after an administrator explicitly selects
 * it and publishes the Customizer, then stored through WordPress' upload API.
 *
 * @package CreceWebLumen
 */

namespace CreceWeb\Lumen;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Previous standalone cache option retained only for one-time migration. */
const LEGACY_INTER_FONT_CACHE_OPTION = 'creceweb_lumen_inter_font_cache';

/** Frontend endpoint token. */
const INTER_FONT_ENDPOINT_TOKEN = 'inter-v20';

/**
 * Returns the pinned Latin variable WOFF2 source used for Inter.
 *
 * @return string
 */
function get_inter_font_remote_url(): string {
	return 'https://fonts.gstatic.com/s/inter/v20/UcC73FwrK3iLTeHuS_nVMrMxCp50SjIa1ZL7W0Q5nw.woff2';
}

/**
 * Returns whether the selected typography uses Inter.
 *
 * @param array<string,mixed>|null $settings Optional settings override.
 * @return bool
 */
function is_inter_typography_selected( ?array $settings = null ): bool {
	$settings = null === $settings ? get_customizations() : $settings;
	$body     = isset( $settings['font_preset'] ) ? (string) $settings['font_preset'] : 'system-sans';
	$heading  = isset( $settings['heading_preset'] ) ? (string) $settings['heading_preset'] : 'inherit';

	return 'inter-local' === $body || 'inter-local' === $heading;
}

/**
 * Validates a WOFF2 response using its magic header and a conservative size.
 *
 * @param string $body Binary response body.
 * @return bool
 */
function validate_inter_font_binary( string $body ): bool {
	$length = strlen( $body );
	return $length >= 8 && $length <= 262144 && 'wOF2' === substr( $body, 0, 4 );
}

/**
 * Normalizes a cached path and guarantees it stays inside WordPress uploads.
 *
 * @param string $file Candidate file path.
 * @return string
 */
function validate_inter_cache_file( string $file ): string {
	if ( '' === $file || ! is_file( $file ) || filesize( $file ) < 8 ) {
		return '';
	}

	$uploads = wp_upload_dir();
	$basedir = empty( $uploads['basedir'] ) ? '' : realpath( (string) $uploads['basedir'] );
	$real    = realpath( $file );
	if ( false === $basedir || false === $real ) {
		return '';
	}

	$prefix = trailingslashit( $basedir );
	return str_starts_with( $real, $prefix ) ? $real : '';
}

/**
 * Returns the historical T1/T1.4 cache file when it still exists.
 *
 * This read-only fallback lets existing test installations upgrade without
 * downloading the same font again. New installs use WordPress' upload API.
 *
 * @return string
 */
function get_legacy_inter_font_file(): string {
	$uploads = wp_upload_dir();
	if ( ! empty( $uploads['error'] ) || empty( $uploads['basedir'] ) ) {
		return '';
	}

	$file = trailingslashit( (string) $uploads['basedir'] ) . 'creceweb-lumen/fonts/inter-latin-variable-v20.woff2';
	return validate_inter_cache_file( $file );
}

/**
 * Migrates the former standalone Inter cache option into Theme settings.
 *
 * The legacy option is deleted after the one-time read so current releases
 * keep all Theme-owned database settings inside creceweb_lumen_settings.
 *
 * @return void
 */
function maybe_migrate_inter_font_cache_option(): void {
	$missing = '__creceweb_lumen_inter_cache_missing__';
	$legacy  = get_option( LEGACY_INTER_FONT_CACHE_OPTION, $missing );
	if ( $missing === $legacy ) {
		return;
	}

	if ( is_array( $legacy ) && ! empty( $legacy['file'] ) ) {
		$file = validate_inter_cache_file( (string) $legacy['file'] );
		if ( '' !== $file ) {
			set_customization_internal_value( 'inter_font_cache_file', $file );
		}
	}

	delete_option( LEGACY_INTER_FONT_CACHE_OPTION );
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\\maybe_migrate_inter_font_cache_option', 25 );


/**
 * Returns the cached Inter font file, if available.
 *
 * @return string
 */
function get_inter_font_cache_file(): string {
	$cached_file = get_customization_internal_value( 'inter_font_cache_file' );
	if ( '' !== $cached_file ) {
		$file = validate_inter_cache_file( $cached_file );
		if ( '' !== $file ) {
			return $file;
		}
	}

	return get_legacy_inter_font_file();
}

/**
 * Downloads Inter into WordPress uploads after an explicit Customizer save.
 *
 * All persistence goes through wp_upload_bits(). There are no direct file
 * write calls in Theme code and no filesystem work on normal frontend/admin
 * requests.
 *
 * @return bool
 */
function cache_inter_font_after_explicit_selection(): bool {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return false;
	}

	if ( '' !== get_inter_font_cache_file() ) {
		return true;
	}

	$response = wp_safe_remote_get(
		get_inter_font_remote_url(),
		array(
			'timeout'             => 15,
			'redirection'         => 2,
			'limit_response_size' => 262144,
			'user-agent'          => 'WordPress/CreceWeb-Lumen ' . get_version(),
		)
	);

	if ( is_wp_error( $response ) || 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
		return false;
	}

	$body = (string) wp_remote_retrieve_body( $response );
	if ( ! validate_inter_font_binary( $body ) ) {
		return false;
	}

	$upload = wp_upload_bits( 'creceweb-lumen-inter-latin-variable-v20.woff2', null, $body );
	if ( ! is_array( $upload ) || ! empty( $upload['error'] ) || empty( $upload['file'] ) ) {
		return false;
	}

	$file = validate_inter_cache_file( (string) $upload['file'] );
	if ( '' === $file ) {
		return false;
	}

	set_customization_internal_value( 'inter_font_cache_file', $file );

	return true;
}

/**
 * Caches Inter only when a Customizer publish leaves Inter selected.
 *
 * @param \WP_Customize_Manager $wp_customize Active Customizer manager.
 * @return void
 */
function maybe_cache_inter_after_customizer_save( \WP_Customize_Manager $wp_customize ): void {
	unset( $wp_customize );
	if ( is_inter_typography_selected() ) {
		cache_inter_font_after_explicit_selection();
	}
}
add_action( 'customize_save_after', __NAMESPACE__ . '\\maybe_cache_inter_after_customizer_save', 20, 1 );

/**
 * Returns the same-origin URL used by the conditional @font-face rule.
 *
 * The response is served by WordPress only when Inter is actually requested,
 * allowing Lumen to set an immutable one-year browser cache without writing
 * server configuration files.
 *
 * @return string
 */
function get_inter_font_endpoint_url(): string {
	return add_query_arg( 'cw_lumen_font', INTER_FONT_ENDPOINT_TOKEN, home_url( '/' ) );
}

/**
 * Serves the cached Inter WOFF2 with explicit long-lived browser caching.
 *
 * This route performs read-only access to a file already created by the
 * WordPress upload API. System-font pages never request this endpoint.
 *
 * @return void
 */
function maybe_serve_inter_font(): void {
	$token = isset( $_GET['cw_lumen_font'] ) ? sanitize_key( wp_unslash( $_GET['cw_lumen_font'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Public immutable font asset endpoint.
	if ( INTER_FONT_ENDPOINT_TOKEN !== $token ) {
		return;
	}

	$file = get_inter_font_cache_file();
	if ( '' === $file ) {
		status_header( 404 );
		exit;
	}

	$body = file_get_contents( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Read-only Theme-owned cached font created by wp_upload_bits().
	if ( false === $body || ! validate_inter_font_binary( $body ) ) {
		status_header( 404 );
		exit;
	}

	if ( function_exists( 'header_remove' ) ) {
		header_remove( 'Cache-Control' );
		header_remove( 'Expires' );
		header_remove( 'Pragma' );
	}

	status_header( 200 );
	header( 'Content-Type: font/woff2' );
	header( 'Content-Length: ' . strlen( $body ) );
	header( 'Cache-Control: public, max-age=31536000, immutable' );
	header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + YEAR_IN_SECONDS ) . ' GMT' );
	header( 'X-Content-Type-Options: nosniff' );
	header( 'Content-Disposition: inline; filename="inter-latin-variable-v20.woff2"' );

	echo $body; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Validated binary WOFF2 response.
	exit;
}
add_action( 'template_redirect', __NAMESPACE__ . '\\maybe_serve_inter_font', 0 );

/**
 * Returns the conditional @font-face declaration for cached Inter.
 *
 * With system fonts selected this exits before cache lookup, keeping the
 * default frontend path free of font I/O and font requests.
 *
 * @return string
 */
function get_inter_font_face_css(): string {
	if ( ! is_inter_typography_selected() ) {
		return '';
	}

	if ( '' === get_inter_font_cache_file() ) {
		return '';
	}

	return "@font-face{font-family:'Inter';font-style:normal;font-weight:100 900;font-display:swap;src:url('" . esc_url( get_inter_font_endpoint_url() ) . "') format('woff2')}";
}
