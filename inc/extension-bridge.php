<?php
/**
 * Public extension points for the Lumen administration and global utilities.
 *
 * @package CreceWebLumen
 */

namespace CreceWeb\Lumen;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns the registered sections shown inside Appearance > CreceWeb Lumen.
 *
 * @return array<string,array{label:string,callback:callable,capability:string,priority:int,owner:string}>
 */
function get_admin_sections(): array {
	$raw = apply_filters( 'creceweb_lumen_admin_sections', array() );
	$raw = is_array( $raw ) ? $raw : array();
	$sections = array();

	foreach ( $raw as $id => $definition ) {
		$id = sanitize_key( (string) $id );
		if ( '' === $id || 'theme' === $id || ! is_array( $definition ) ) {
			continue;
		}

		$callback = $definition['callback'] ?? null;
		if ( ! is_callable( $callback ) ) {
			continue;
		}

		$label = isset( $definition['label'] ) ? sanitize_text_field( (string) $definition['label'] ) : '';
		if ( '' === $label ) {
			continue;
		}

		$capability = isset( $definition['capability'] ) ? sanitize_key( (string) $definition['capability'] ) : 'edit_theme_options';
		$capability = '' !== $capability ? $capability : 'edit_theme_options';
		$priority   = isset( $definition['priority'] ) ? (int) $definition['priority'] : 50;
		$owner      = isset( $definition['owner'] ) ? sanitize_key( (string) $definition['owner'] ) : 'extension';
		$owner      = in_array( $owner, array( 'lite', 'pro', 'extension' ), true ) ? $owner : 'extension';

		$sections[ $id ] = array(
			'label'      => $label,
			'callback'   => $callback,
			'capability' => $capability,
			'priority'   => $priority,
			'owner'      => $owner,
		);
	}

	uasort(
		$sections,
		static function ( array $left, array $right ): int {
			return $left['priority'] <=> $right['priority'];
		}
	);

	return $sections;
}

/**
 * Returns the requested administration section.
 *
 * @return string
 */
function get_requested_admin_section(): string {
	$requested = isset( $_GET['section'] ) ? sanitize_key( wp_unslash( $_GET['section'] ) ) : 'theme'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only navigation.
	$sections  = get_admin_sections();

	return 'theme' === $requested || isset( $sections[ $requested ] ) ? $requested : 'theme';
}

/**
 * Returns a URL for one section of the shared Lumen administration page.
 *
 * @param string               $section Section identifier.
 * @param array<string,string> $arguments Optional query arguments.
 * @return string
 */
function get_admin_section_url( string $section = 'theme', array $arguments = array() ): string {
	$section = sanitize_key( $section );
	$url = get_hub_url();

	if ( '' !== $section && 'theme' !== $section ) {
		$url = add_query_arg( 'section', $section, $url );
	}

	foreach ( $arguments as $key => $value ) {
		$key = sanitize_key( (string) $key );
		if ( '' !== $key && is_scalar( $value ) ) {
			$url = add_query_arg( $key, sanitize_text_field( (string) $value ), $url );
		}
	}

	return $url;
}

/**
 * Renders the shared section navigation.
 *
 * @param string $active Active section.
 * @return void
 */
function render_admin_section_navigation( string $active = 'theme' ): void {
	$tabs = array(
		'theme' => array(
			'label'      => __( 'CreceWeb Lumen', 'creceweb-lumen' ),
			'capability' => 'edit_theme_options',
			'owner'      => 'theme',
		),
	);

	foreach ( get_admin_sections() as $id => $definition ) {
		$tabs[ $id ] = array(
			'label'      => $definition['label'],
			'capability' => $definition['capability'],
			'owner'      => $definition['owner'],
		);
	}
	?>
	<nav class="nav-tab-wrapper cw-lumen-hub__tabs" aria-label="<?php echo esc_attr__( 'Opciones de Lumen', 'creceweb-lumen' ); ?>">
		<?php foreach ( $tabs as $id => $tab ) : ?>
			<?php if ( ! current_user_can( $tab['capability'] ) ) : ?>
				<?php continue; ?>
			<?php endif; ?>
			<?php $owner_class = 'cw-lumen-hub__tab--' . sanitize_html_class( (string) $tab['owner'] ); ?>
			<a class="nav-tab <?php echo esc_attr( $owner_class ); ?><?php echo $active === $id ? ' nav-tab-active' : ''; ?>" data-cw-lumen-owner="<?php echo esc_attr( (string) $tab['owner'] ); ?>" href="<?php echo esc_url( get_admin_section_url( $id ) ); ?>"><?php echo esc_html( $tab['label'] ); ?></a>
		<?php endforeach; ?>
	</nav>
	<?php
}

/**
 * Renders one extension-owned section inside the Theme-owned page.
 *
 * @param string $section Section identifier.
 * @return bool
 */
function render_admin_extension_section( string $section ): bool {
	$sections = get_admin_sections();
	if ( ! isset( $sections[ $section ] ) ) {
		return false;
	}

	$definition = $sections[ $section ];
	if ( ! current_user_can( $definition['capability'] ) ) {
		wp_die( esc_html__( 'No tenés permisos para ver estas opciones.', 'creceweb-lumen' ) );
	}

	do_action( 'creceweb_lumen_admin_before_section', $section, $definition );
	call_user_func( $definition['callback'], $section );
	do_action( 'creceweb_lumen_admin_after_section', $section, $definition );

	return true;
}

/**
 * Returns a stable identifier for the current native post-list context.
 *
 * Extensions can use this value to add optional information to archive cards
 * without duplicating Theme conditionals.
 *
 * @return string
 */
function get_loop_context(): string {
	if ( is_home() ) {
		$context = 'blog_home';
	} elseif ( is_category() ) {
		$context = 'category';
	} elseif ( is_tag() ) {
		$context = 'tag';
	} elseif ( is_author() ) {
		$context = 'author_archive';
	} elseif ( is_date() ) {
		$context = 'date_archive';
	} elseif ( is_post_type_archive() ) {
		$context = 'post_type_archive';
	} elseif ( is_search() ) {
		$context = 'search';
	} elseif ( is_archive() ) {
		$context = 'archive';
	} else {
		$context = 'loop';
	}

	/**
	 * Filters the current native post-list context identifier.
	 *
	 * @param string $context Context identifier.
	 */
	$context = apply_filters( 'creceweb_lumen_loop_context', $context );

	return sanitize_key( is_scalar( $context ) ? (string) $context : 'loop' );
}

/**
 * Returns the plain-text footer credit.
 *
 * @return string
 */
function get_footer_credit_text(): string {
	$default = sprintf(
		/* translators: 1: Current year. 2: Site name. 3: Rights notice. */
		__( '© %1$s %2$s · %3$s', 'creceweb-lumen' ),
		wp_date( 'Y' ),
		get_bloginfo( 'name' ),
		__( 'Todos los derechos reservados', 'creceweb-lumen' )
	);

	$value = apply_filters( 'creceweb_lumen_footer_credit_text', $default );
	if ( ! is_scalar( $value ) ) {
		return $default;
	}

	return trim( wp_strip_all_tags( (string) $value ) );
}

/**
 * Returns the normalized global floating-action configuration.
 *
 * @return array<string,string|int>
 */
function get_floating_action_config(): array {
	$settings = get_customizations();
	$config = array(
		'action'             => sanitize_key( (string) ( $settings['floating_action'] ?? 'back_to_top' ) ),
		'whatsapp_number'    => preg_replace( '/\D+/', '', (string) ( $settings['whatsapp_number'] ?? '' ) ),
		'whatsapp_message'   => sanitize_text_field( (string) ( $settings['whatsapp_message'] ?? '' ) ),
		'background_color'   => sanitize_hex_color( (string) ( $settings['floating_action_background_color'] ?? '' ) ) ?: '',
		'icon_color'         => sanitize_hex_color( (string) ( $settings['floating_action_icon_color'] ?? '' ) ) ?: '',
		'size'               => absint( $settings['floating_action_size'] ?? 0 ),
	);

	$filtered = apply_filters( 'creceweb_lumen_floating_action_config', $config );
	if ( ! is_array( $filtered ) ) {
		return $config;
	}

	$action = sanitize_key( (string) ( $filtered['action'] ?? $config['action'] ) );
	$config['action'] = in_array( $action, array( 'none', 'back_to_top', 'whatsapp' ), true ) ? $action : 'none';
	$config['whatsapp_number']  = preg_replace( '/\D+/', '', (string) ( $filtered['whatsapp_number'] ?? $config['whatsapp_number'] ) );
	$config['whatsapp_message'] = sanitize_text_field( (string) ( $filtered['whatsapp_message'] ?? $config['whatsapp_message'] ) );
	$config['background_color'] = sanitize_hex_color( (string) ( $filtered['background_color'] ?? $config['background_color'] ) ) ?: '';
	$config['icon_color']       = sanitize_hex_color( (string) ( $filtered['icon_color'] ?? $config['icon_color'] ) ) ?: '';
	$config['size']             = max( 0, absint( $filtered['size'] ?? $config['size'] ) );

	return $config;
}

/**
 * Normalizes a provider-neutral global Messaging configuration.
 *
 * @param array<string,mixed> $config Raw Messaging configuration.
 * @return array<string,mixed>
 */
function normalize_global_messaging_config( array $config ): array {
	$providers = array( 'whatsapp', 'telegram', 'messenger', 'signal' );
	$provider  = sanitize_key( (string) ( $config['provider'] ?? 'whatsapp' ) );
	if ( ! in_array( $provider, $providers, true ) ) {
		$provider = 'whatsapp';
	}

	$position = sanitize_key( (string) ( $config['position'] ?? 'right' ) );
	if ( ! in_array( $position, array( 'left', 'right' ), true ) ) {
		$position = 'right';
	}

	$devices = isset( $config['devices'] ) && is_array( $config['devices'] ) ? $config['devices'] : array();

	return array(
		'enabled'          => ! empty( $config['enabled'] ),
		'provider'         => $provider,
		'url'              => esc_url_raw( (string) ( $config['url'] ?? '' ) ),
		'destination'      => sanitize_text_field( (string) ( $config['destination'] ?? '' ) ),
		'message'          => sanitize_textarea_field( (string) ( $config['message'] ?? '' ) ),
		'background_color' => sanitize_hex_color( (string) ( $config['background_color'] ?? '' ) ) ?: '#0f172a',
		'icon_color'       => sanitize_hex_color( (string) ( $config['icon_color'] ?? '' ) ) ?: '#ffffff',
		'size'             => max( 1, absint( $config['size'] ?? 46 ) ),
		'position'         => $position,
		'devices'          => array(
			'desktop' => ! empty( $devices['desktop'] ),
			'tablet'  => ! empty( $devices['tablet'] ),
			'mobile'  => ! empty( $devices['mobile'] ),
		),
		'owner'            => sanitize_key( (string) ( $config['owner'] ?? 'theme-legacy' ) ),
		'scope'            => sanitize_key( (string) ( $config['scope'] ?? 'global' ) ),
	);
}

/**
 * Returns the normalized global Messaging configuration.
 *
 * The Theme only exposes legacy WhatsApp values as a neutral compatibility
 * source. Extensions own active Messaging configuration and may replace it
 * through the documented filter.
 *
 * @return array<string,mixed>
 */
function get_global_messaging_config(): array {
	$settings = get_customizations();
	$number   = preg_replace( '/\D+/', '', (string) ( $settings['whatsapp_number'] ?? '' ) );
	$number   = is_string( $number ) ? substr( $number, 0, 20 ) : '';
	$message  = sanitize_textarea_field( (string) ( $settings['whatsapp_message'] ?? '' ) );
	$url      = '' !== $number ? 'https://wa.me/' . $number : '';
	if ( '' !== $url && '' !== $message ) {
		$url .= '?text=' . rawurlencode( $message );
	}

	$config = array(
		'enabled'          => 'whatsapp' === (string) ( $settings['floating_action'] ?? '' ) && '' !== $number,
		'provider'         => 'whatsapp',
		'url'              => $url,
		'destination'      => $number,
		'message'          => $message,
		'background_color' => sanitize_hex_color( (string) ( $settings['floating_action_background_color'] ?? '' ) ) ?: '#0f172a',
		'icon_color'       => sanitize_hex_color( (string) ( $settings['floating_action_icon_color'] ?? '' ) ) ?: '#ffffff',
		'size'             => max( 1, absint( $settings['floating_action_size'] ?? 46 ) ),
		'position'         => 'right',
		'devices'          => array(
			'desktop' => true,
			'tablet'  => true,
			'mobile'  => true,
		),
		'owner'            => 'theme-legacy',
		'scope'            => 'global',
	);

	$filtered = apply_filters( 'creceweb_lumen_global_messaging_config', $config );
	if ( ! is_array( $filtered ) ) {
		$filtered = $config;
	}

	return normalize_global_messaging_config( $filtered );
}

/**
 * Returns whether the global Messaging action may render on this request.
 *
 * The Theme does not render Messaging itself. Extensions use this gate to
 * coordinate global and contextual ownership.
 *
 * @param array<string,mixed>|null $config Optional normalized configuration.
 * @return bool
 */
function should_render_global_messaging( ?array $config = null ): bool {
	$config = is_array( $config ) ? normalize_global_messaging_config( $config ) : get_global_messaging_config();
	$should_render = ! empty( $config['enabled'] ) && ! empty( $config['url'] );

	return (bool) apply_filters( 'creceweb_lumen_global_messaging_should_render', $should_render, $config );
}

/**
 * Returns the normalized global WhatsApp configuration.
 *
 * This compatibility bridge exposes legacy values only so Lumen Lite can migrate
 * existing installations. The theme does not render or configure WhatsApp.
 * Extensions may provide the active configuration through the documented filter.
 *
 * @return array<string,mixed>
 */
function get_global_whatsapp_config(): array {
	$messaging = get_global_messaging_config();
	$devices   = isset( $messaging['devices'] ) && is_array( $messaging['devices'] )
		? $messaging['devices']
		: array(
			'desktop' => true,
			'tablet'  => true,
			'mobile'  => true,
		);

	if ( 'whatsapp' !== (string) ( $messaging['provider'] ?? '' ) ) {
		return array(
			'enabled'          => false,
			'number'           => '',
			'message'          => '',
			'background_color' => sanitize_hex_color( (string) ( $messaging['background_color'] ?? '' ) ) ?: '#0f172a',
			'icon_color'       => sanitize_hex_color( (string) ( $messaging['icon_color'] ?? '' ) ) ?: '#ffffff',
			'size'             => max( 1, absint( $messaging['size'] ?? 46 ) ),
			'position'         => in_array( (string) ( $messaging['position'] ?? 'right' ), array( 'left', 'right' ), true )
				? (string) $messaging['position']
				: 'right',
			'devices'          => array(
				'desktop' => ! empty( $devices['desktop'] ),
				'tablet'  => ! empty( $devices['tablet'] ),
				'mobile'  => ! empty( $devices['mobile'] ),
			),
			'owner'            => sanitize_key( (string) ( $messaging['owner'] ?? 'theme-legacy' ) ),
		);
	}

	$number = preg_replace( '/\D+/', '', (string) ( $messaging['destination'] ?? '' ) );
	$number = is_string( $number ) ? substr( $number, 0, 20 ) : '';
	$config = array(
		'enabled'          => ! empty( $messaging['enabled'] ) && '' !== $number,
		'number'           => $number,
		'message'          => sanitize_textarea_field( (string) ( $messaging['message'] ?? '' ) ),
		'background_color' => sanitize_hex_color( (string) ( $messaging['background_color'] ?? '' ) ) ?: '#0f172a',
		'icon_color'       => sanitize_hex_color( (string) ( $messaging['icon_color'] ?? '' ) ) ?: '#ffffff',
		'size'             => max( 1, absint( $messaging['size'] ?? 46 ) ),
		'position'         => in_array( (string) ( $messaging['position'] ?? 'right' ), array( 'left', 'right' ), true )
			? (string) $messaging['position']
			: 'right',
		'devices'          => array(
			'desktop' => ! empty( $devices['desktop'] ),
			'tablet'  => ! empty( $devices['tablet'] ),
			'mobile'  => ! empty( $devices['mobile'] ),
		),
		'owner'            => sanitize_key( (string) ( $messaging['owner'] ?? 'theme-legacy' ) ),
	);

	$filtered = apply_filters( 'creceweb_lumen_global_whatsapp_config', $config );
	if ( ! is_array( $filtered ) ) {
		$filtered = $config;
	}

	$devices = isset( $filtered['devices'] ) && is_array( $filtered['devices'] ) ? $filtered['devices'] : $config['devices'];
	$position = sanitize_key( (string) ( $filtered['position'] ?? 'right' ) );
	if ( ! in_array( $position, array( 'left', 'center', 'right' ), true ) ) {
		$position = 'right';
	}

	$number = preg_replace( '/\D+/', '', (string) ( $filtered['number'] ?? '' ) );
	$number = is_string( $number ) ? substr( $number, 0, 20 ) : '';

	return array(
		'enabled'          => ! empty( $filtered['enabled'] ) && '' !== $number,
		'number'           => $number,
		'message'          => sanitize_textarea_field( (string) ( $filtered['message'] ?? '' ) ),
		'background_color' => sanitize_hex_color( (string) ( $filtered['background_color'] ?? '' ) ) ?: '#0f172a',
		'icon_color'       => sanitize_hex_color( (string) ( $filtered['icon_color'] ?? '' ) ) ?: '#ffffff',
		'size'             => max( 1, absint( $filtered['size'] ?? 46 ) ),
		'position'         => $position,
		'devices'          => array(
			'desktop' => ! empty( $devices['desktop'] ),
			'tablet'  => ! empty( $devices['tablet'] ),
			'mobile'  => ! empty( $devices['mobile'] ),
		),
		'owner'            => sanitize_key( (string) ( $filtered['owner'] ?? 'theme-legacy' ) ),
	);
}

/**
 * Returns whether the global WhatsApp button may render on this request.
 *
 * Compatible extensions use this contract to coordinate per-content visibility.
 * The theme itself does not render a WhatsApp action.
 *
 * @param array<string,mixed>|null $config Optional normalized configuration.
 * @return bool
 */
function should_render_global_whatsapp( ?array $config = null ): bool {
	$config = is_array( $config ) ? $config : get_global_whatsapp_config();
	$should_render = ! empty( $config['enabled'] ) && ! empty( $config['number'] );

	return (bool) apply_filters( 'creceweb_lumen_global_whatsapp_should_render', $should_render, $config );
}

