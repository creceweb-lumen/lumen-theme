<?php
/**
 * Lumen Pro integration bridge.
 *
 * Lumen owns the free theme experience. This file exposes stable,
 * no-op-by-default extension points so the separate Lumen Pro plugin can add
 * contextual capabilities without editing theme templates or free-theme data.
 *
 * @package CreceWebLumen
 */

namespace CreceWeb\Lumen;

/**
 * Returns whether a value is a valid Semantic Versioning 2.0.0 identifier.
 *
 * @param string $version Version to validate.
 * @return bool
 */
function is_valid_semver( string $version ): bool {
	$pattern = '/^(0|[1-9]\d*)\.(0|[1-9]\d*)\.(0|[1-9]\d*)(?:-(?:0|[1-9A-Za-z-][0-9A-Za-z-]*)(?:\.(?:0|[1-9A-Za-z-][0-9A-Za-z-]*))*)?(?:\+[0-9A-Za-z-]+(?:\.[0-9A-Za-z-]+)*)?$/D';

	return 1 === preg_match( $pattern, $version );
}

/**
 * Returns the bridge API version exported by the active Lumen theme.
 *
 * @return string
 */
function get_bridge_api_version(): string {
	return CRECEWEB_LUMEN_BRIDGE_API_VERSION;
}

/**
 * Returns the minimum canonical Pro version supported by this bridge.
 *
 * This value is theme-owned and intentionally cannot be overridden by plugin
 * filters. A plugin must never be able to mark itself compatible by lowering
 * Lumen's minimum requirement at runtime.
 *
 * @return string
 */
function get_minimum_pro_version(): string {
	return CRECEWEB_LUMEN_MINIMUM_PRO_VERSION;
}

/**
 * Returns the canonical Pro version declared by the plugin.
 *
 * Legacy identifiers are deliberately not used here. They may indicate that a
 * package is present, but only CRECEWEB_LUMEN_PRO_VERSION can activate the
 * hardened bridge.
 *
 * @return string
 */
function get_pro_version(): string {
	if ( ! defined( 'CRECEWEB_LUMEN_PRO_VERSION' ) ) {
		return '';
	}

	return trim( (string) constant( 'CRECEWEB_LUMEN_PRO_VERSION' ) );
}

/**
 * Returns the bridge API version declared by Lumen Pro.
 *
 * @return string
 */
function get_pro_bridge_api_version(): string {
	if ( ! defined( 'CRECEWEB_LUMEN_PRO_BRIDGE_API_VERSION' ) ) {
		return '';
	}

	return trim( (string) constant( 'CRECEWEB_LUMEN_PRO_BRIDGE_API_VERSION' ) );
}

/**
 * Returns whether a Lumen Pro-like package is present in the current request.
 *
 * A legacy package remains detectable so Lumen can communicate an
 * incompatibility safely. Detection alone never enables assets or modules.
 *
 * @return bool
 */
function is_pro_detected(): bool {
	return defined( 'CRECEWEB_LUMEN_PRO_VERSION' )
		|| defined( 'CRECEWEB_LUMEN_PRO_BRIDGE_API_VERSION' )
		|| defined( 'CRECEWEB_PRO_VERSION' )
		|| class_exists( '\\CreceWeb\\Pro\\Plugin' );
}

/**
 * Returns whether a Pro bridge API declaration is compatible with Lumen.
 *
 * Compatibility requires valid SemVer values, an identical major version and
 * a theme bridge API that is equal to or newer than the API requested by Pro.
 *
 * @param string $pro_api_version Pro bridge API version. Empty uses the declared value.
 * @return bool
 */
function is_pro_bridge_api_compatible( string $pro_api_version = '' ): bool {
	$lumen_api = get_bridge_api_version();
	$pro_api   = '' !== $pro_api_version ? $pro_api_version : get_pro_bridge_api_version();

	if ( ! is_valid_semver( $lumen_api ) || ! is_valid_semver( $pro_api ) ) {
		return false;
	}

	$lumen_major = (int) strtok( $lumen_api, '.' );
	$pro_major   = (int) strtok( $pro_api, '.' );

	return $lumen_major === $pro_major && version_compare( $lumen_api, $pro_api, '>=' );
}

/**
 * Returns machine-readable compatibility failures for an installed Pro package.
 *
 * The list is intentionally local: Lumen performs no license, network or
 * remote-code checks. Pro is considered active only when this list is empty.
 *
 * @return string[]
 */
function get_pro_compatibility_issues(): array {
	if ( ! is_pro_detected() ) {
		return array();
	}

	$issues  = array();
	$version = get_pro_version();
	$pro_api = get_pro_bridge_api_version();

	if ( ! defined( 'CRECEWEB_LUMEN_PRO_VERSION' ) ) {
		$issues[] = 'missing_canonical_version';
	} elseif ( '' === $version || ! is_valid_semver( $version ) ) {
		$issues[] = 'invalid_pro_version';
	} elseif ( version_compare( $version, get_minimum_pro_version(), '<' ) ) {
		$issues[] = 'unsupported_pro_version';
	}

	if ( ! defined( 'CRECEWEB_LUMEN_PRO_BRIDGE_API_VERSION' ) ) {
		$issues[] = 'missing_bridge_api';
	} elseif ( '' === $pro_api || ! is_valid_semver( $pro_api ) ) {
		$issues[] = 'invalid_bridge_api';
	} elseif ( ! is_pro_bridge_api_compatible( $pro_api ) ) {
		$issues[] = 'incompatible_bridge_api';
	}

	if ( defined( 'CRECEWEB_LUMEN_PRO_REQUIRED_LITE_VERSION' ) ) {
		$required_lite_version = trim( (string) constant( 'CRECEWEB_LUMEN_PRO_REQUIRED_LITE_VERSION' ) );
		$lite_version = defined( 'CRECEWEB_LUMEN_LITE_VERSION' ) ? trim( (string) constant( 'CRECEWEB_LUMEN_LITE_VERSION' ) ) : '';
		if ( '' === $lite_version ) {
			$issues[] = 'pro_requires_lite';
		} elseif ( is_valid_semver( $required_lite_version ) && ( ! is_valid_semver( $lite_version ) || version_compare( $lite_version, $required_lite_version, '<' ) ) ) {
			$issues[] = 'pro_requires_newer_lite';
		}
	}
	if ( defined( 'CRECEWEB_LUMEN_PRO_REQUIRED_LITE_API_VERSION' ) ) {
		$required_lite_api = trim( (string) constant( 'CRECEWEB_LUMEN_PRO_REQUIRED_LITE_API_VERSION' ) );
		$lite_api = defined( 'CRECEWEB_LUMEN_LITE_API_VERSION' ) ? trim( (string) constant( 'CRECEWEB_LUMEN_LITE_API_VERSION' ) ) : '';
		if ( '' === $lite_api || ! is_valid_semver( $lite_api ) || ! is_valid_semver( $required_lite_api ) || (int) strtok( $lite_api, '.' ) !== (int) strtok( $required_lite_api, '.' ) || version_compare( $lite_api, $required_lite_api, '<' ) ) {
			$issues[] = 'pro_requires_compatible_lite_api';
		}
	}

	if ( defined( 'CRECEWEB_LUMEN_PRO_REQUIRED_THEME_VERSION' ) ) {
		$required_theme_version = trim( (string) constant( 'CRECEWEB_LUMEN_PRO_REQUIRED_THEME_VERSION' ) );
		if ( is_valid_semver( $required_theme_version ) && version_compare( get_version(), $required_theme_version, '<' ) ) {
			$issues[] = 'pro_requires_newer_theme';
		}
	}

	return $issues;
}

/**
 * Returns the Pro bridge status.
 *
 * Status values are intentionally limited to presentation and integration:
 * inactive, active or incompatible. License state remains owned by Pro.
 * No filter can upgrade an incompatible package to active.
 *
 * @return string
 */
function get_pro_status(): string {
	if ( ! is_pro_detected() ) {
		return 'inactive';
	}

	return empty( get_pro_compatibility_issues() ) ? 'active' : 'incompatible';
}

/**
 * Returns whether a compatible Lumen Pro package is active.
 *
 * @return bool
 */
function is_pro_active(): bool {
	return 'active' === get_pro_status();
}

/**
 * Returns the license-management URL when Pro is active.
 *
 * @return string
 */
function get_pro_manage_url(): string {
	$url = admin_url( 'themes.php?page=creceweb-lumen-pro' );

	return (string) apply_filters( 'creceweb_lumen_pro_manage_url', $url );
}

/**
 * Returns whether the current template should render Lumen's automatic entry title.
 *
 * The free theme defaults to showing page titles. A compatible Pro package can
 * choose a safe boolean state through the bridge; it cannot alter title markup,
 * inject content, or influence templates while incompatible.
 *
 * @param string $context Template context, currently page.
 * @param int    $post_id Current post identifier.
 * @return bool
 */
function is_entry_title_visible( string $context = 'page', int $post_id = 0 ): bool {
	$visible = true;

	if ( ! is_pro_active() ) {
		return $visible;
	}

	$context = sanitize_key( $context );
	$post_id = absint( $post_id );

	return (bool) apply_filters( 'creceweb_lumen_entry_title_visible', $visible, $context, $post_id );
}

/**
 * Runs the front-end asset bridge only while compatible Pro is active.
 *
 * Pro hooks into this action and declares creceweb-lumen as a stylesheet
 * dependency. No styles or scripts are added by this bridge when Pro is absent
 * or incompatible.
 *
 * @return void
 */
function enqueue_pro_assets(): void {
	if ( ! is_pro_active() ) {
		return;
	}

	do_action( 'creceweb_lumen_enqueue_pro_assets', get_version() );
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_pro_assets', 30 );

/**
 * Runs the admin asset bridge only while compatible Pro is active.
 *
 * @param string $hook_suffix Current admin screen hook suffix.
 * @return void
 */
function enqueue_pro_admin_assets( string $hook_suffix ): void {
	if ( ! is_pro_active() ) {
		return;
	}

	do_action( 'creceweb_lumen_enqueue_pro_admin_assets', $hook_suffix, get_version() );
}
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\enqueue_pro_admin_assets', 30 );

/**
 * Runs the Customizer asset bridge only while compatible Pro is active.
 *
 * @return void
 */
function enqueue_pro_customizer_assets(): void {
	if ( ! is_pro_active() ) {
		return;
	}

	do_action( 'creceweb_lumen_enqueue_pro_customizer_assets', get_version() );
}
add_action( 'customize_controls_enqueue_scripts', __NAMESPACE__ . '\\enqueue_pro_customizer_assets', 30 );
