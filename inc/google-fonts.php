<?php
/**
 * Optional remote Google Fonts support.
 *
 * The Theme keeps a bundled Google Fonts metadata snapshot for Customizer
 * discovery and does not contact Google merely to display the catalog. The
 * snapshot is loaded into the existing family selectors only after an
 * administrator asks to see Google Fonts. Remote CSS is requested only after
 * a Google family is explicitly selected.
 *
 * @package CreceWebLumen
 */

namespace CreceWeb\Lumen;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Legacy T2 preset IDs retained for backwards compatibility.
 *
 * @return array<string,array{family:string,category:string,weights:array<int,int>}>
 */
function get_legacy_google_font_presets(): array {
	return array(
		'google-roboto'           => array( 'family' => 'Roboto', 'category' => 'sans-serif', 'weights' => array( 100, 300, 400, 500, 700, 900 ) ),
		'google-open-sans'        => array( 'family' => 'Open Sans', 'category' => 'sans-serif', 'weights' => array( 300, 400, 500, 600, 700, 800 ) ),
		'google-lato'             => array( 'family' => 'Lato', 'category' => 'sans-serif', 'weights' => array( 100, 300, 400, 700, 900 ) ),
		'google-montserrat'       => array( 'family' => 'Montserrat', 'category' => 'sans-serif', 'weights' => array( 100, 200, 300, 400, 500, 600, 700, 800, 900 ) ),
		'google-poppins'          => array( 'family' => 'Poppins', 'category' => 'sans-serif', 'weights' => array( 100, 200, 300, 400, 500, 600, 700, 800, 900 ) ),
		'google-nunito-sans'      => array( 'family' => 'Nunito Sans', 'category' => 'sans-serif', 'weights' => array( 200, 300, 400, 500, 600, 700, 800, 900 ) ),
		'google-source-sans-3'    => array( 'family' => 'Source Sans 3', 'category' => 'sans-serif', 'weights' => array( 200, 300, 400, 500, 600, 700, 800, 900 ) ),
		'google-raleway'          => array( 'family' => 'Raleway', 'category' => 'sans-serif', 'weights' => array( 100, 200, 300, 400, 500, 600, 700, 800, 900 ) ),
		'google-merriweather'     => array( 'family' => 'Merriweather', 'category' => 'serif', 'weights' => array( 300, 400, 700, 900 ) ),
		'google-playfair-display' => array( 'family' => 'Playfair Display', 'category' => 'serif', 'weights' => array( 400, 500, 600, 700, 800, 900 ) ),
	);
}

/**
 * Returns the local discovery catalog.
 *
 * This file is bundled with the Theme and is read only when the administrator
 * asks to see Google Fonts. Previously saved valid dynamic family values remain
 * supported even when they are not present in the bundled snapshot.
 *
 * @return array<int,array{family:string,category:string,weights:array<int,int>}>
 */
function get_google_fonts_catalog(): array {
	static $catalog = null;
	if ( null !== $catalog ) {
		return $catalog;
	}

	$catalog = array();
	$path    = CRECEWEB_LUMEN_DIR . '/assets/data/google-fonts-catalog.json';
	if ( ! is_readable( $path ) || ! function_exists( 'wp_json_file_decode' ) ) {
		return $catalog;
	}

	$data = wp_json_file_decode( $path, array( 'associative' => true ) );
	if ( ! is_array( $data ) || empty( $data['fonts'] ) || ! is_array( $data['fonts'] ) ) {
		return $catalog;
	}

	foreach ( $data['fonts'] as $font ) {
		if ( ! is_array( $font ) || empty( $font['family'] ) ) {
			continue;
		}
		$family = sanitize_google_font_family( (string) $font['family'] );
		if ( '' === $family ) {
			continue;
		}
		$weights = array();
		foreach ( (array) ( $font['weights'] ?? array() ) as $weight ) {
			$weight = absint( $weight );
			if ( $weight >= 100 && $weight <= 900 ) {
				$weights[] = $weight;
			}
		}
		$weights = array_values( array_unique( $weights ) );
		sort( $weights, SORT_NUMERIC );
		$category = sanitize_key( (string) ( $font['category'] ?? 'sans-serif' ) );
		$catalog[] = array(
			'family'   => $family,
			'category' => $category,
			'weights'  => $weights,
		);
	}

	return $catalog;
}

/**
 * Sanitizes a Google Fonts family name without imposing a product allowlist.
 *
 * @param string $family Raw family name.
 * @return string
 */
function sanitize_google_font_family( string $family ): string {
	$family = trim( wp_strip_all_tags( $family ) );
	$family = preg_replace( '/\s+/u', ' ', $family );
	if ( ! is_string( $family ) || '' === $family ) {
		return '';
	}

	// Google family names use letters, numbers, spaces and conservative punctuation.
	if ( ! preg_match( "/^[\p{L}\p{N} .&'()+_-]+$/u", $family ) ) {
		return '';
	}

	return $family;
}

/**
 * Converts a family name into the stable dynamic setting value.
 *
 * @param string $family Family name.
 * @return string
 */
function get_google_font_preset_id( string $family ): string {
	$family = sanitize_google_font_family( $family );
	return '' === $family ? '' : 'google:' . $family;
}

/**
 * Resolves a dynamic or legacy Google preset into a family name.
 *
 * @param string $preset Preset value.
 * @return string
 */
function get_google_font_family_from_preset( string $preset ): string {
	if ( 0 === strpos( $preset, 'google:' ) ) {
		return sanitize_google_font_family( substr( $preset, 7 ) );
	}

	$legacy = get_legacy_google_font_presets();
	return isset( $legacy[ $preset ] ) ? $legacy[ $preset ]['family'] : '';
}

/**
 * Returns whether a preset represents a Google Fonts family.
 *
 * @param string $preset Preset value.
 * @return bool
 */
function is_google_font_preset( string $preset ): bool {
	return '' !== get_google_font_family_from_preset( $preset );
}

/**
 * Looks up bundled metadata for a family when available.
 *
 * Unknown families remain valid; they simply use generic fallback metadata.
 *
 * @param string $family Family name.
 * @return array{family:string,category:string,weights:array<int,int>}|null
 */
function get_google_font_metadata( string $family ): ?array {
	$family = sanitize_google_font_family( $family );
	if ( '' === $family ) {
		return null;
	}

	foreach ( get_google_fonts_catalog() as $font ) {
		if ( 0 === strcasecmp( $font['family'], $family ) ) {
			return $font;
		}
	}

	foreach ( get_legacy_google_font_presets() as $font ) {
		if ( 0 === strcasecmp( $font['family'], $family ) ) {
			return $font;
		}
	}

	return null;
}

/**
 * Returns a CSS stack for a Google Fonts preset.
 *
 * @param string $preset Preset value.
 * @return string
 */
function get_google_font_stack( string $preset ): string {
	$family = get_google_font_family_from_preset( $preset );
	if ( '' === $family ) {
		return '';
	}

	$metadata = get_google_font_metadata( $family );
	$category = $metadata['category'] ?? 'sans-serif';
	$safe     = str_replace( array( '\\', "'" ), array( '\\\\', "\\'" ), $family );

	if ( 'serif' === $category ) {
		return "'{$safe}', ui-serif, Georgia, Cambria, 'Times New Roman', serif";
	}
	if ( 'monospace' === $category ) {
		return "'{$safe}', ui-monospace, SFMono-Regular, Menlo, Consolas, monospace";
	}
	return "'{$safe}', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif";
}

/**
 * Returns the closest available static weight for a catalogued family.
 *
 * @param int            $requested Requested weight.
 * @param array<int,int> $available Available weights.
 * @return int
 */
function get_closest_google_font_weight( int $requested, array $available ): int {
	if ( empty( $available ) ) {
		return 0;
	}
	$closest  = $available[0];
	$distance = abs( $requested - $closest );
	foreach ( $available as $weight ) {
		$current = abs( $requested - $weight );
		if ( $current < $distance ) {
			$closest  = $weight;
			$distance = $current;
		}
	}
	return $closest;
}

/**
 * Returns selected Google families and the exact weights to request.
 *
 * A family absent from the bundled discovery index remains usable. In that
 * case an empty weight list is returned and Google CSS2 is asked for the
 * family's default face rather than rejecting an unsupported explicit weight.
 *
 * @param array<string,mixed>|null $settings Optional settings override.
 * @return array<string,array<int,int>> Family name => weights.
 */
function get_selected_google_font_requirements( ?array $settings = null ): array {
	$settings       = null === $settings ? get_customizations() : $settings;
	$body_preset    = isset( $settings['font_preset'] ) ? (string) $settings['font_preset'] : 'system-sans';
	$heading_preset = isset( $settings['heading_preset'] ) ? (string) $settings['heading_preset'] : 'inherit';
	$heading_weight = isset( $settings['heading_weight'] ) ? absint( $settings['heading_weight'] ) : 700;
	$heading_weight = in_array( $heading_weight, array( 500, 600, 700, 800 ), true ) ? $heading_weight : 700;
	$requirements   = array();

	$body_family = get_google_font_family_from_preset( $body_preset );
	if ( '' !== $body_family ) {
		$metadata = get_google_font_metadata( $body_family );
		if ( $metadata && ! empty( $metadata['weights'] ) ) {
			$requirements[ $body_family ] = array(
				get_closest_google_font_weight( 400, $metadata['weights'] ),
				get_closest_google_font_weight( 700, $metadata['weights'] ),
			);
			if ( 'inherit' === $heading_preset ) {
				$requirements[ $body_family ][] = get_closest_google_font_weight( $heading_weight, $metadata['weights'] );
			}
		} else {
			$requirements[ $body_family ] = array();
		}
	}

	$heading_family = get_google_font_family_from_preset( $heading_preset );
	if ( '' !== $heading_family ) {
		$metadata = get_google_font_metadata( $heading_family );
		if ( ! isset( $requirements[ $heading_family ] ) ) {
			$requirements[ $heading_family ] = array();
		}
		if ( $metadata && ! empty( $metadata['weights'] ) ) {
			$requirements[ $heading_family ][] = get_closest_google_font_weight( $heading_weight, $metadata['weights'] );
		}
	}

	foreach ( $requirements as $family => $weights ) {
		$weights = array_values( array_filter( array_unique( array_map( 'absint', $weights ) ) ) );
		sort( $weights, SORT_NUMERIC );
		$requirements[ $family ] = $weights;
	}

	ksort( $requirements, SORT_NATURAL | SORT_FLAG_CASE );
	return $requirements;
}

/**
 * Encodes one CSS2 family query while preserving Google Fonts syntax tokens.
 *
 * @param string         $family Family name.
 * @param array<int,int> $weights Required font weights.
 * @return string
 */
function encode_google_font_family_query( string $family, array $weights ): string {
	$value = $family;
	if ( ! empty( $weights ) ) {
		$value .= ':wght@' . implode( ';', $weights );
	}
	$value = rawurlencode( $value );
	return str_replace( array( '%20', '%3A', '%40', '%3B' ), array( '+', ':', '@', ';' ), $value );
}

/**
 * Returns one conditional Google Fonts CSS2 stylesheet URL.
 *
 * Empty means the current typography uses no remote Google Font.
 *
 * @param array<string,mixed>|null $settings Optional settings override.
 * @return string
 */
function get_google_fonts_stylesheet_url( ?array $settings = null ): string {
	$requirements = get_selected_google_font_requirements( $settings );
	if ( empty( $requirements ) ) {
		return '';
	}

	$parts = array();
	foreach ( $requirements as $family => $weights ) {
		$parts[] = 'family=' . encode_google_font_family_query( $family, $weights );
	}

	return esc_url_raw( 'https://fonts.googleapis.com/css2?' . implode( '&', $parts ) . '&display=swap' );
}

/**
 * Returns whether Theme remote delivery should be used for selected Google Fonts.
 *
 * Pro can later replace this delivery layer with local files while preserving
 * the same public family settings.
 *
 * @return bool
 */
function should_enqueue_remote_google_fonts(): bool {
	$requirements = get_selected_google_font_requirements();
	if ( empty( $requirements ) ) {
		return false;
	}

	/**
	 * Filters whether selected Google Fonts are delivered remotely by Theme.
	 *
	 * @param bool                         $enabled Remote delivery state.
	 * @param array<string,array<int,int>> $requirements Selected family requirements.
	 */
	return (bool) apply_filters( 'creceweb_lumen_remote_google_fonts_enabled', true, $requirements );
}
