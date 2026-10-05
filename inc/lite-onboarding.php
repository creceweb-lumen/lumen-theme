<?php
/**
 * Lumen Lite onboarding helpers.
 *
 * @package CreceWebLumen
 */

namespace CreceWeb\Lumen;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return the canonical WordPress.org plugin basename for Lumen Lite.
 *
 * @return string
 */
function get_lite_plugin_basename(): string {
	return 'creceweb-lumen-lite/creceweb-lumen-lite.php';
}

/**
 * Return the current dismissal schema for the Lumen Lite onboarding notice.
 *
 * The schema is bumped only when an unreleased notice contract must invalidate
 * a stale dismissal value. Normal Theme updates must keep this value stable so
 * a user's dismissal remains permanent.
 *
 * @return string
 */
function get_lite_onboarding_notice_schema(): string {
	return '2';
}

/**
 * Resolve the installed/active state of Lumen Lite and native WordPress actions.
 *
 * @return array{status:string,network_active:bool,can_install:bool,can_activate:bool,install_url:string,activate_url:string}
 */
function get_lite_onboarding_state(): array {
	if ( ! function_exists( 'get_plugins' ) || ! function_exists( 'is_plugin_active' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	$plugin         = get_lite_plugin_basename();
	$plugins        = get_plugins();
	$network_active = function_exists( 'is_plugin_active_for_network' ) && is_plugin_active_for_network( $plugin );
	$active         = $network_active || is_plugin_active( $plugin ) || defined( 'CRECEWEB_LUMEN_LITE_VERSION' );
	$installed      = $active || isset( $plugins[ $plugin ] );
	$can_install    = current_user_can( 'install_plugins' );
	$can_activate   = current_user_can( 'activate_plugins' );
	$install_url    = '';
	$activate_url   = '';

	if ( ! $installed && $can_install ) {
		$base_url    = is_multisite()
			? network_admin_url( 'update.php?action=install-plugin&plugin=creceweb-lumen-lite' )
			: admin_url( 'update.php?action=install-plugin&plugin=creceweb-lumen-lite' );
		$install_url = wp_nonce_url( $base_url, 'install-plugin_creceweb-lumen-lite' );
	}

	if ( $installed && ! $active && $can_activate ) {
		$activate_url = wp_nonce_url(
			admin_url( 'plugins.php?action=activate&plugin=' . rawurlencode( $plugin ) ),
			'activate-plugin_' . $plugin
		);
	}

	return array(
		'status'         => $active ? 'active' : ( $installed ? 'installed' : 'missing' ),
		'network_active' => $network_active,
		'can_install'    => $can_install,
		'can_activate'   => $can_activate,
		'install_url'    => $install_url,
		'activate_url'   => $activate_url,
	);
}

/**
 * Determine whether the Lumen Lite onboarding notice should be rendered.
 *
 * @param array  $state      Lumen Lite onboarding state.
 * @param string $screen_id  Current admin screen ID.
 * @param bool   $dismissed  Whether the current user dismissed the notice for this site.
 * @return bool
 */
function should_render_lite_onboarding_notice( array $state, string $screen_id, bool $dismissed ): bool {
	if ( 'themes' !== $screen_id || $dismissed || 'active' === ( $state['status'] ?? '' ) ) {
		return false;
	}

	if ( 'missing' === ( $state['status'] ?? '' ) ) {
		return ! empty( $state['install_url'] );
	}

	if ( 'installed' === ( $state['status'] ?? '' ) ) {
		return ! empty( $state['activate_url'] );
	}

	return false;
}

/**
 * Return whether the current user dismissed the Lumen Lite onboarding notice on this site.
 *
 * @return bool
 */
function is_lite_onboarding_notice_dismissed(): bool {
	$user_id = get_current_user_id();
	if ( $user_id <= 0 ) {
		return false;
	}

	return get_lite_onboarding_notice_schema() === (string) get_user_option( 'creceweb_lumen_lite_onboarding_notice_dismissed', $user_id );
}

/**
 * Return the nonce-protected URL used to dismiss the Lumen Lite onboarding notice.
 *
 * @return string
 */
function get_lite_onboarding_notice_dismiss_url(): string {
	return wp_nonce_url(
		admin_url( 'admin-post.php?action=creceweb_lumen_dismiss_lite_onboarding' ),
		'creceweb_lumen_dismiss_lite_onboarding'
	);
}

/**
 * Permanently dismiss the Lumen Lite onboarding notice for the current user on this site.
 *
 * @return void
 */
function handle_lite_onboarding_notice_dismissal(): void {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( 'No tenés permisos para ver estas opciones.', 'creceweb-lumen' ) );
	}

	check_admin_referer( 'creceweb_lumen_dismiss_lite_onboarding' );

	$user_id = get_current_user_id();
	if ( $user_id > 0 ) {
		update_user_option( $user_id, 'creceweb_lumen_lite_onboarding_notice_dismissed', get_lite_onboarding_notice_schema(), false );
	}

	wp_safe_redirect( admin_url( 'themes.php' ) );
	exit;
}
add_action( 'admin_post_creceweb_lumen_dismiss_lite_onboarding', __NAMESPACE__ . '\handle_lite_onboarding_notice_dismissal' );

/**
 * Load the small layout fix used by the Lumen Lite notice on Appearance > Themes.
 *
 * @param string $hook_suffix Current admin page hook.
 * @return void
 */
function enqueue_lite_onboarding_notice_assets( string $hook_suffix ): void {
	if ( 'themes.php' !== $hook_suffix ) {
		return;
	}

	$relative_path = 'assets/css/lite-onboarding.css';
	$absolute_path = CRECEWEB_LUMEN_DIR . '/' . $relative_path;

	wp_enqueue_style(
		'creceweb-lumen-lite-onboarding',
		CRECEWEB_LUMEN_URI . '/' . $relative_path,
		array(),
		file_exists( $absolute_path ) ? (string) filemtime( $absolute_path ) : get_version()
	);
}
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\enqueue_lite_onboarding_notice_assets' );

/**
 * Render a dismissible Lumen Lite recommendation on Appearance > Themes.
 *
 * @return void
 */
function render_lite_onboarding_admin_notice(): void {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$screen    = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	$screen_id = $screen && isset( $screen->id ) ? (string) $screen->id : '';
	if ( 'themes' !== $screen_id || is_lite_onboarding_notice_dismissed() ) {
		return;
	}

	$state = get_lite_onboarding_state();
	if ( ! should_render_lite_onboarding_notice( $state, $screen_id, false ) ) {
		return;
	}

	$is_installed = 'installed' === $state['status'];
	$primary_url  = $is_installed ? (string) $state['activate_url'] : (string) $state['install_url'];
	$primary_text = $is_installed ? __( 'Activar Lumen Lite', 'creceweb-lumen' ) : __( 'Instalar Lumen Lite', 'creceweb-lumen' );
	?>
	<div class="notice notice-info cw-lumen-lite-onboarding" role="status" aria-live="polite">
		<a class="notice-dismiss" href="<?php echo esc_url( get_lite_onboarding_notice_dismiss_url() ); ?>">
			<span class="screen-reader-text"><?php esc_html_e( 'Descartar este aviso', 'creceweb-lumen' ); ?></span>
		</a>
		<div class="cw-lumen-lite-onboarding__inner">
			<div class="cw-lumen-lite-onboarding__brand" aria-hidden="true">
				<img class="cw-lumen-lite-onboarding__brand-image" src="<?php echo esc_url( CRECEWEB_LUMEN_URI . '/assets/images/lumen-lite-logo.webp' ); ?>" alt="" />
			</div>
			<div class="cw-lumen-lite-onboarding__content">
				<p class="cw-lumen-lite-onboarding__title"><strong><?php esc_html_e( 'Complemento opcional: Lumen Lite', 'creceweb-lumen' ); ?></strong></p>
				<p class="cw-lumen-lite-onboarding__description"><?php esc_html_e( 'Lumen Lite es un complemento opcional para CreceWeb Lumen. Agrega una biblioteca de patrones y herramientas para contenido, menú, mensajería y rendimiento.', 'creceweb-lumen' ); ?></p>
				<div class="cw-lumen-lite-onboarding__actions">
					<a class="button button-primary" href="<?php echo esc_url( $primary_url ); ?>"><?php echo esc_html( $primary_text ); ?></a>
				</div>
			</div>
		</div>
	</div>
	<?php
}
add_action( 'admin_notices', __NAMESPACE__ . '\render_lite_onboarding_admin_notice' );
