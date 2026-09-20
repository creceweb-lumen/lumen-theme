<?php
/**
 * Stable integration hooks for CreceWeb Lumen extensions.
 *
 * @package CreceWebLumen
 */

namespace CreceWeb\Lumen;

/**
 * Announces the active Lumen theme and bridge API.
 *
 * @return void
 */
function announce_loaded(): void {
	do_action( 'creceweb_lumen_loaded', get_version(), get_bridge_api_version() );
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\\announce_loaded', 20 );

/**
 * Normalizes a CSS color value emitted by Elementor controls.
 *
 * The Gallery control normally stores hex/alpha colors, while imported or
 * global values may be represented by CSS custom properties. Keep this
 * intentionally narrow so a saved setting cannot escape the style attribute.
 *
 * @param mixed $value Raw Elementor color value.
 * @return string Safe CSS color value or an empty string.
 */
function normalize_elementor_color_value( $value ): string {
	if ( ! is_scalar( $value ) ) {
		return '';
	}

	$color = trim( (string) $value );
	if ( '' === $color ) {
		return '';
	}

	if ( preg_match( '/^#(?:[0-9a-f]{3}|[0-9a-f]{4}|[0-9a-f]{6}|[0-9a-f]{8})$/i', $color ) ) {
		return $color;
	}

	if ( preg_match( '/^var\(--[a-z0-9_-]+\)$/i', $color ) ) {
		return $color;
	}

	if ( preg_match( '/^(?:rgb|rgba|hsl|hsla)\([0-9+.,%\/\s-]+\)$/i', $color ) ) {
		return $color;
	}

	if ( in_array( strtolower( $color ), array( 'transparent', 'currentcolor' ), true ) ) {
		return $color;
	}

	return '';
}

/**
 * Resolves an Elementor Global Color reference to its CSS custom property.
 *
 * @param array<string,mixed> $settings Raw widget settings.
 * @return string CSS var() reference or an empty string.
 */
function get_elementor_gallery_global_border_color( array $settings ): string {
	$globals = isset( $settings['__globals__'] ) && is_array( $settings['__globals__'] )
		? $settings['__globals__']
		: array();

	$reference = isset( $globals['image_border_color'] ) && is_scalar( $globals['image_border_color'] )
		? trim( (string) $globals['image_border_color'] )
		: '';

	if ( '' === $reference ) {
		return '';
	}

	if ( ! preg_match( '/^globals\/colors\?id=([a-z0-9_-]+)$/i', $reference, $matches ) ) {
		return '';
	}

	return 'var(--e-global-color-' . $matches[1] . ')';
}

/**
 * Returns whether the current Elementor document is content owned by Lumen.
 *
 * Lumen keeps Elementor Canvas and Theme Builder/library documents isolated
 * from theme content integrations. Ordinary page/post documents are supported.
 *
 * @return bool
 */
function is_supported_elementor_content_document(): bool {
	$post_id = get_queried_object_id();
	if ( ! $post_id ) {
		global $post;
		$post_id = $post instanceof \WP_Post ? (int) $post->ID : 0;
	}

	if ( ! $post_id ) {
		return true;
	}

	$post_type = get_post_type( $post_id );
	if ( ! in_array( $post_type, array( 'page', 'post' ), true ) ) {
		return false;
	}

	if ( 'page' === $post_type && 'elementor_canvas' === get_page_template_slug( $post_id ) ) {
		return false;
	}

	return true;
}

/**
 * Returns the Lumen semantic colors mirrored into Elementor's active Kit.
 *
 * The Customizer remains the source of truth. Each entry always exists in the
 * Elementor picker, using the content-specific token when configured and the
 * closest base Theme color otherwise. The `active` flag controls whether that
 * Lumen color also becomes the inherited default of a compatible widget.
 *
 * @return array<string,array{_id:string,title:string,label:string,group:string,color:string,active:bool,content_key:string}>
 */
function get_elementor_lumen_kit_colors(): array {
	$settings = get_customizations();
	$base_map = array(
		'cwlumenprimary'      => array(
			'title'       => __( 'Principal', 'creceweb-lumen' ),
			'setting_key' => 'primary_color',
		),
		'cwlumenaccent'       => array(
			'title'       => __( 'Acción', 'creceweb-lumen' ),
			'setting_key' => 'accent_color',
		),
		'cwlumenaccentstrong' => array(
			'title'       => __( 'Acción activa', 'creceweb-lumen' ),
			'setting_key' => 'accent_strong',
		),
		'cwlumenbackground'   => array(
			'title'       => __( 'Fondo general', 'creceweb-lumen' ),
			'setting_key' => 'background_color',
		),
		'cwlumensurface'      => array(
			'title'       => __( 'Superficie', 'creceweb-lumen' ),
			'setting_key' => 'surface_color',
		),
		'cwlumentext'         => array(
			'title'       => __( 'Texto principal', 'creceweb-lumen' ),
			'setting_key' => 'text_color',
		),
		'cwlumenheadingbase'  => array(
			'title'       => __( 'Títulos', 'creceweb-lumen' ),
			'setting_key' => 'heading_color',
		),
		'cwlumenlink'         => array(
			'title'       => __( 'Enlaces', 'creceweb-lumen' ),
			'setting_key' => 'link_color',
		),
		'cwlumenmuted'        => array(
			'title'       => __( 'Texto secundario', 'creceweb-lumen' ),
			'setting_key' => 'text_muted_color',
		),
		'cwlumenborder'       => array(
			'title'       => __( 'Bordes', 'creceweb-lumen' ),
			'setting_key' => 'border_color',
		),
	);
	$content_map = array(
		'cwlumenheading'     => array(
			'title'        => __( 'Títulos', 'creceweb-lumen' ),
			'content_key'  => 'content_heading_color',
			'fallback_key' => 'heading_color',
		),
		'cwlumenbutton'      => array(
			'title'        => __( 'Botón fondo', 'creceweb-lumen' ),
			'content_key'  => 'content_button_background_color',
			'fallback_key' => 'button_background_color',
		),
		'cwlumenbuttonhover' => array(
			'title'        => __( 'Botón hover', 'creceweb-lumen' ),
			'content_key'  => 'content_button_hover_color',
			'fallback_key' => 'button_hover_color',
		),
		'cwlumenbuttontext'  => array(
			'title'        => __( 'Botón texto', 'creceweb-lumen' ),
			'content_key'  => 'content_button_text_color',
			'fallback_key' => 'button_text_color',
		),
		'cwlumenbullet'      => array(
			'title'        => __( 'Viñetas e iconos', 'creceweb-lumen' ),
			'content_key'  => 'content_bullet_color',
			'fallback_key' => 'primary_color',
		),
		'cwlumenbox'         => array(
			'title'        => __( 'Recuadros', 'creceweb-lumen' ),
			'content_key'  => 'content_box_color',
			'fallback_key' => 'border_color',
		),
	);

	$colors = array();
	foreach ( $base_map as $id => $definition ) {
		$value = sanitize_hex_color( (string) ( $settings[ $definition['setting_key'] ] ?? '' ) );
		$colors[ $id ] = array(
			'_id'         => $id,
			'title'       => 'Lumen · ' . __( 'Base', 'creceweb-lumen' ) . ' · ' . $definition['title'],
			'label'       => $definition['title'],
			'group'       => 'base',
			'color'       => $value ? strtolower( $value ) : '#000000',
			'active'      => false,
			'content_key' => '',
		);
	}

	foreach ( $content_map as $id => $definition ) {
		$content_value = sanitize_hex_color( (string) ( $settings[ $definition['content_key'] ] ?? '' ) );
		$fallback      = sanitize_hex_color( (string) ( $settings[ $definition['fallback_key'] ] ?? '' ) );
		$color         = $content_value ? strtolower( $content_value ) : ( $fallback ? strtolower( $fallback ) : '#000000' );

		$colors[ $id ] = array(
			'_id'         => $id,
			'title'       => 'Lumen · ' . __( 'Contenido', 'creceweb-lumen' ) . ' · ' . $definition['title'],
			'label'       => $definition['title'],
			'group'       => 'content',
			'color'       => $color,
			'active'      => (bool) $content_value,
			'content_key' => $definition['content_key'],
		);
	}

	return $colors;
}


/**
 * Groups Lumen's Elementor colors for a compact Site Settings presentation.
 *
 * The Elementor Global Color picker itself is flat, so stable title prefixes
 * keep that list ordered while Site Settings can present two collapsible
 * groups without duplicating ownership of the actual color values.
 *
 * @return array{base:array<int,array<string,mixed>>,content:array<int,array<string,mixed>>}
 */
function get_elementor_lumen_color_groups(): array {
	$groups = array(
		'base'    => array(),
		'content' => array(),
	);

	foreach ( get_elementor_lumen_kit_colors() as $color ) {
		$group = isset( $color['group'] ) && 'content' === $color['group'] ? 'content' : 'base';
		$groups[ $group ][] = $color;
	}

	return $groups;
}

/**
 * Returns Theme settings whose changes must be mirrored into Elementor.
 *
 * @return array<int,string>
 */
function get_elementor_lumen_mirrored_setting_keys(): array {
	return array(
		'primary_color',
		'accent_color',
		'accent_strong',
		'background_color',
		'surface_color',
		'text_color',
		'heading_color',
		'link_color',
		'text_muted_color',
		'border_color',
		'content_heading_color',
		'content_button_background_color',
		'content_button_hover_color',
		'content_button_text_color',
		'content_box_color',
		'content_bullet_color',
		'button_background_color',
		'button_hover_color',
		'button_text_color',
	);
}


/**
 * Adds compact, read-only Lumen color groups to Elementor Site Settings.
 *
 * Elementor's Global Color picker is intentionally flat. These collapsible
 * sections mirror the same Lumen entries in two semantic groups so the user
 * can inspect the Theme palette without scrolling through one long settings
 * list. The Customizer remains the source of truth.
 *
 * @param mixed $tab Elementor Kit controls stack.
 * @param array $args Section arguments.
 * @return void
 */
function add_elementor_lumen_theme_color_controls( $tab, array $args ): void {
	unset( $args );

	if ( ! is_object( $tab ) || ! method_exists( $tab, 'start_controls_section' ) || ! method_exists( $tab, 'add_control' ) || ! method_exists( $tab, 'end_controls_section' ) ) {
		return;
	}

	if ( ! class_exists( '\\Elementor\\Repeater' ) || ! class_exists( '\\Elementor\\Controls_Manager' ) || ! class_exists( '\\Elementor\\Core\\Kits\\Controls\\Repeater' ) ) {
		return;
	}

	$groups = get_elementor_lumen_color_groups();
	$sections = array(
		'base' => array(
			'id'      => 'section_creceweb_lumen_base_colors',
			'control' => 'creceweb_lumen_base_colors',
			'label'   => __( 'Lumen · Paleta base', 'creceweb-lumen' ),
		),
		'content' => array(
			'id'      => 'section_creceweb_lumen_content_colors',
			'control' => 'creceweb_lumen_content_colors',
			'label'   => __( 'Lumen · Colores de contenido', 'creceweb-lumen' ),
		),
	);

	foreach ( $sections as $group_key => $section ) {
		$tab->start_controls_section(
			$section['id'],
			array(
				'label' => $section['label'],
				'tab'   => 'global-colors',
			)
		);

		$repeater = new \Elementor\Repeater();
		$repeater->add_control(
			'title',
			array(
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'disabled'    => true,
			)
		);
		$repeater->add_control(
			'color',
			array(
				'type'        => \Elementor\Controls_Manager::COLOR,
				'label_block' => true,
				'dynamic'     => array(),
				'disabled'    => true,
				'global'      => array(
					'active' => false,
				),
			)
		);

		$defaults = array();
		foreach ( $groups[ $group_key ] as $color ) {
			$defaults[] = array(
				'_id'   => $color['_id'],
				'title' => $color['label'],
				'color' => $color['color'],
			);
		}

		$tab->add_control(
			$section['control'],
			array(
				'type'         => \Elementor\Core\Kits\Controls\Repeater::CONTROL_TYPE,
				'fields'       => $repeater->get_controls(),
				'default'      => $defaults,
				'item_actions' => array(
					'add'    => false,
					'remove' => false,
				),
			)
		);

		$tab->end_controls_section();
	}
}
add_action( 'elementor/element/kit/section_global_colors/after_section_end', __NAMESPACE__ . '\\add_elementor_lumen_theme_color_controls', 20, 2 );

/**
 * Mirrors only Lumen-owned Global Colors into Elementor's active Kit.
 *
 * Existing Elementor system/custom colors are preserved. Lumen entries use
 * stable IDs, are updated in place as a group, and never write changes back to
 * Theme settings. No page/post Elementor data is modified.
 *
 * @return bool Whether the active Elementor Kit was changed.
 */
function sync_lumen_colors_to_elementor_kit(): bool {
	static $syncing = false;

	if ( $syncing || ! current_user_can( 'edit_theme_options' ) || ! class_exists( '\\Elementor\\Plugin' ) || ! method_exists( '\\Elementor\\Plugin', 'instance' ) ) {
		return false;
	}

	$plugin = \Elementor\Plugin::instance();
	if ( ! isset( $plugin->kits_manager ) || ! is_object( $plugin->kits_manager ) ) {
		return false;
	}

	$kits_manager = $plugin->kits_manager;
	if ( ! method_exists( $kits_manager, 'get_current_settings' ) || ! method_exists( $kits_manager, 'update_kit_settings_based_on_option' ) ) {
		return false;
	}

	$syncing = true;
	try {
		$current = $kits_manager->get_current_settings();
		if ( ! is_array( $current ) ) {
			return false;
		}

		$current_colors = isset( $current['custom_colors'] ) && is_array( $current['custom_colors'] )
			? array_values( $current['custom_colors'] )
			: array();
		$lumen_state   = get_elementor_lumen_kit_colors();
		$lumen_ids     = array_keys( $lumen_state );
		$lumen_colors  = array();

		foreach ( $lumen_state as $definition ) {
			$lumen_colors[] = array(
				'_id'   => $definition['_id'],
				'title' => $definition['title'],
				'color' => $definition['color'],
			);
		}

		$other_colors = array();
		foreach ( $current_colors as $entry ) {
			$id = is_array( $entry ) && isset( $entry['_id'] ) && is_scalar( $entry['_id'] )
				? (string) $entry['_id']
				: '';

			if ( '' !== $id && in_array( $id, $lumen_ids, true ) ) {
				continue;
			}

			$other_colors[] = $entry;
		}

		$merged = array_merge( $lumen_colors, $other_colors );
		if ( $merged === $current_colors ) {
			return false;
		}

		$kits_manager->update_kit_settings_based_on_option( 'custom_colors', $merged );

		if ( isset( $plugin->files_manager ) && is_object( $plugin->files_manager ) && method_exists( $plugin->files_manager, 'clear_cache' ) ) {
			$plugin->files_manager->clear_cache();
		}

		return true;
	} catch ( \Throwable $error ) {
		unset( $error );
		return false;
	} finally {
		$syncing = false;
	}
}

/**
 * Keeps the active Elementor Kit synchronized before the visual editor loads.
 *
 * @return void
 */
function sync_lumen_colors_for_elementor_editor(): void {
	sync_lumen_colors_to_elementor_kit();
}
add_action( 'elementor/editor/init', __NAMESPACE__ . '\\sync_lumen_colors_for_elementor_editor', 5 );

/**
 * Performs a lightweight admin-side sync so Theme updates do not require the
 * user to open Elementor once before Lumen Global Colors become available.
 *
 * @return void
 */
function maybe_sync_lumen_colors_in_admin(): void {
	if ( ! is_admin() || wp_doing_ajax() || ! class_exists( '\\Elementor\\Plugin' ) ) {
		return;
	}

	sync_lumen_colors_to_elementor_kit();
}
add_action( 'admin_init', __NAMESPACE__ . '\\maybe_sync_lumen_colors_in_admin', 100 );

/**
 * Re-syncs Elementor only when a Theme color mirrored to the Kit changes.
 *
 * @param mixed  $old_value Previous Theme settings.
 * @param mixed  $new_value New Theme settings.
 * @param string $option Option name.
 * @return void
 */
function sync_elementor_kit_after_lumen_color_change( $old_value, $new_value, string $option ): void {
	if ( CUSTOMIZATION_OPTION !== $option || ! is_array( $old_value ) || ! is_array( $new_value ) ) {
		return;
	}

	$keys = get_elementor_lumen_mirrored_setting_keys();

	foreach ( $keys as $key ) {
		if ( (string) ( $old_value[ $key ] ?? '' ) !== (string) ( $new_value[ $key ] ?? '' ) ) {
			sync_lumen_colors_to_elementor_kit();
			return;
		}
	}
}
add_action( 'update_option_' . CUSTOMIZATION_OPTION, __NAMESPACE__ . '\\sync_elementor_kit_after_lumen_color_change', 20, 3 );

/**
 * Assigns a real Lumen Kit Global Color as an Elementor control default.
 *
 * The control's existing definition is preserved. Elementor only uses a Global
 * default when the widget has no saved local/global value, so user selections
 * continue to override Lumen without Theme-specific heuristics.
 *
 * @param mixed  $element Elementor controls stack.
 * @param string $control_id Elementor control ID.
 * @param string $global_id Stable Lumen Global Color ID.
 * @return void
 */
function set_elementor_lumen_control_global_default( $element, string $control_id, string $global_id ): void {
	if ( ! is_object( $element ) || ! method_exists( $element, 'update_control' ) || ! method_exists( $element, 'get_unique_name' ) || ! class_exists( '\\Elementor\\Plugin' ) || ! method_exists( '\\Elementor\\Plugin', 'instance' ) ) {
		return;
	}

	$colors = get_elementor_lumen_kit_colors();
	if ( empty( $colors[ $global_id ]['active'] ) ) {
		return;
	}

	$plugin = \Elementor\Plugin::instance();
	if ( ! isset( $plugin->controls_manager ) || ! is_object( $plugin->controls_manager ) || ! method_exists( $plugin->controls_manager, 'get_control_from_stack' ) ) {
		return;
	}

	$control = $plugin->controls_manager->get_control_from_stack( $element->get_unique_name(), $control_id );
	if ( is_wp_error( $control ) || ! is_array( $control ) ) {
		return;
	}

	$control['global'] = isset( $control['global'] ) && is_array( $control['global'] ) ? $control['global'] : array();
	$control['global']['default'] = 'globals/colors?id=' . $global_id;

	$element->update_control( $control_id, $control, array( 'recursive' => true ) );
}

/**
 * Uses Lumen's configured heading token as the inherited Elementor Heading
 * Global Color. Saved local/global widget values still win.
 *
 * @param mixed $element Elementor Heading controls stack.
 * @param array $args Section arguments.
 * @return void
 */
function set_elementor_heading_lumen_global_default( $element, array $args ): void {
	unset( $args );
	set_elementor_lumen_control_global_default( $element, 'title_color', 'cwlumenheading' );
}
add_action( 'elementor/element/heading/section_title_style/before_section_end', __NAMESPACE__ . '\\set_elementor_heading_lumen_global_default', 20, 2 );

/**
 * Uses configured Lumen button tokens as Elementor Button inherited defaults.
 *
 * @param mixed $element Elementor Button controls stack.
 * @param array $args Section arguments.
 * @return void
 */
function set_elementor_button_lumen_global_defaults( $element, array $args ): void {
	unset( $args );
	set_elementor_lumen_control_global_default( $element, 'background_color', 'cwlumenbutton' );
	set_elementor_lumen_control_global_default( $element, 'button_background_hover_color', 'cwlumenbuttonhover' );
	set_elementor_lumen_control_global_default( $element, 'button_text_color', 'cwlumenbuttontext' );
}
add_action( 'elementor/element/button/section_style/before_section_end', __NAMESPACE__ . '\\set_elementor_button_lumen_global_defaults', 20, 2 );

/**
 * Uses Lumen's configured list token as Elementor Icon List's inherited color.
 *
 * @param mixed $element Elementor Icon List controls stack.
 * @param array $args Section arguments.
 * @return void
 */
function set_elementor_icon_list_lumen_global_default( $element, array $args ): void {
	unset( $args );
	set_elementor_lumen_control_global_default( $element, 'icon_color', 'cwlumenbullet' );
}
add_action( 'elementor/element/icon-list/section_icon_style/before_section_end', __NAMESPACE__ . '\\set_elementor_icon_list_lumen_global_default', 20, 2 );

/**
 * Restores Elementor Gallery's own border-color custom property when saved.
 *
 * Elementor Gallery renders its image borders from --image-border-color. Some
 * Elementor 4.x combinations keep the saved image_border_color setting but do
 * not expose the custom property consistently in the editor/frontend output.
 * Lumen mirrors Elementor's own saved value when present; when the Gallery has
 * no explicit border color, it falls back to Lumen's semantic border token.
 *
 * @param mixed $widget Elementor widget instance.
 * @return void
 */
function restore_elementor_gallery_border_color( $widget ): void {
	if ( ! is_object( $widget ) || ! method_exists( $widget, 'get_name' ) || 'gallery' !== $widget->get_name() ) {
		return;
	}

	if ( ! is_supported_elementor_content_document() ) {
		return;
	}

	if ( ! method_exists( $widget, 'get_settings_for_display' ) || ! method_exists( $widget, 'add_render_attribute' ) ) {
		return;
	}

	$display_settings = $widget->get_settings_for_display();
	$display_settings = is_array( $display_settings ) ? $display_settings : array();
	$color            = normalize_elementor_color_value( $display_settings['image_border_color'] ?? '' );

	if ( '' === $color && method_exists( $widget, 'get_settings' ) ) {
		$raw_settings = $widget->get_settings();
		$raw_settings = is_array( $raw_settings ) ? $raw_settings : array();
		$color        = normalize_elementor_color_value( $raw_settings['image_border_color'] ?? '' );

		if ( '' === $color ) {
			$color = get_elementor_gallery_global_border_color( $raw_settings );
		}
	}

	if ( '' === $color ) {
		$theme_settings = get_customizations();
		$theme_border   = normalize_elementor_color_value( $theme_settings['border_color'] ?? '' );

		if ( '' === $theme_border ) {
			$theme_border = '#cbd5e1';
		}

		/* Elementor's visual editor canvas does not always expose Lumen's root
		 * custom properties. Keep the semantic token for the frontend, but provide
		 * the current Theme border color as the CSS var fallback so editor and
		 * frontend render the same border. */
		$color = 'var(--cw-color-border,' . $theme_border . ')';
	}

	$widget->add_render_attribute( '_wrapper', 'style', '--image-border-color:' . $color . ';' );
}
add_action( 'elementor/frontend/widget/before_render', __NAMESPACE__ . '\\restore_elementor_gallery_border_color', 10, 1 );


/**
 * Loads the Gallery border fallback bridge inside Elementor's visual preview.
 *
 * Elementor's editor can rerender Pro Gallery client-side without preserving
 * server render attributes. The preview bridge mirrors the Theme border only
 * when the Gallery has no explicit widget/global border color.
 *
 * @return void
 */
function enqueue_elementor_gallery_preview_bridge(): void {
	if ( ! is_supported_elementor_content_document() ) {
		return;
	}

	$relative_path = 'assets/js/elementor-gallery-preview.js';
	$absolute_path = CRECEWEB_LUMEN_DIR . '/' . $relative_path;
	if ( ! file_exists( $absolute_path ) ) {
		return;
	}

	$theme_settings = get_customizations();
	$theme_border   = normalize_elementor_color_value( $theme_settings['border_color'] ?? '' );
	if ( ! preg_match( '/^#(?:[0-9a-f]{3}|[0-9a-f]{6})$/i', $theme_border ) ) {
		$theme_border = '#cbd5e1';
	}

	wp_enqueue_script(
		'creceweb-lumen-elementor-gallery-preview',
		CRECEWEB_LUMEN_URI . '/' . $relative_path,
		array( 'jquery' ),
		(string) filemtime( $absolute_path ),
		true
	);

	wp_localize_script(
		'creceweb-lumen-elementor-gallery-preview',
		'crecewebLumenElementorGalleryPreview',
		array(
			'borderColor' => $theme_border,
		)
	);
}
add_action( 'elementor/preview/enqueue_scripts', __NAMESPACE__ . '\\enqueue_elementor_gallery_preview_bridge' );
