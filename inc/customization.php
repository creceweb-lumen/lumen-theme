<?php
/**
 * Appearance settings and front-end customization output.
 *
 * @package CreceWebLumen
 */

namespace CreceWeb\Lumen;

// Lumen-owned option used by the guided Customizer.
const CUSTOMIZATION_OPTION = 'creceweb_lumen_settings';

// Previous option name retained only for one-time migration.
const LEGACY_CUSTOMIZATION_OPTION = 'cw_lumen_settings';

/**
 * Returns the safe defaults for CreceWeb visual controls.
 *
 * The defaults preserve the current CreceWeb presentation. New controls extend
 * the native Customizer without changing existing pages, menus or widgets.
 *
 * @return array<string, string>
 */
function get_customization_defaults(): array {
	return array(
		// Global colors.
		'primary_color'              => '#0f172a',
		'accent_color'               => '#10b981',
		'accent_strong'              => '#059669',
		'background_color'           => '#f8fafc',
		'surface_color'              => '#ffffff',
		'text_color'                 => '#111827',
		'heading_color'              => '#0f172a',
		'link_color'                 => '#059669',
		'text_muted_color'           => '#64748b',
		'border_color'               => '#cbd5e1',
		'focus_color'                => '#059669',

		// Optional content-specific colors. Empty values inherit the existing global tokens.
		'content_heading_color'           => '',
		'content_button_background_color' => '',
		'content_button_hover_color'      => '',
		'content_button_text_color'       => '',
		'content_box_color'               => '',
		'content_bullet_color'            => '',

		// Zone colors.
		'navigation_color'                  => '#0f172a',
		'navigation_hover_color'            => '#059669',
		'navigation_active_color'           => '#059669',
		'submenu_background_color'          => '#ffffff',
		'submenu_hover_text_color'          => '',
		'submenu_hover_background_color'    => '',
		'submenu_hover_background_opacity'  => '100',
		'button_background_color'    => '#10b981',
		'button_hover_color'         => '#059669',
		'button_text_color'          => '#ffffff',
		'footer_background_color'    => '#ffffff',
		'footer_text_color'          => '#111827',
		'footer_link_color'          => '#059669',
		'copyright_background_color' => '#020914',
		'copyright_text_color'       => '#ffffff',
		'form_background_color'      => '#ffffff',
		'form_border_color'          => '#cbd5e1',
		'form_focus_color'           => '#059669',
		'top_bar_background_color'   => '#ffffff',
		'top_bar_text_color'         => '#111827',
		'top_bar_link_color'         => '#059669',

		// Native Social Icons presentation. Profiles and URLs stay in WordPress widget/block content.
		'social_header_color'        => '',
		'social_header_hover_color'  => '',
		'social_footer_color'        => '',
		'social_footer_hover_color'  => '',

		// Typography and layout.
		'font_preset'          => 'system-sans',
		'heading_preset'       => 'inherit',
		'font_scale'           => 'standard',
		'heading_weight'       => '700',
		'content_width'        => '760px',
		'wide_width'           => '1120px',
		'mobile_gutter'        => '16px',
		'spacing_density'      => 'normal',
		'shape'                => 'soft',
		'button_style'         => 'solid',
		'button_radius'        => '8',
		'button_padding_y'     => '13',
		'button_padding_x'     => '20',
		'form_radius'          => '8',
		'link_decoration'      => 'underline',
		'floating_action'                  => 'back_to_top',
		'floating_action_background_color' => '#0f172a',
		'floating_action_icon_color'       => '#ffffff',
		'floating_action_size'             => '46',
		'whatsapp_number'                  => '',
		'whatsapp_message'                 => '',
		'design_preset'        => 'professional',
		'show_advanced_controls' => '0',

		// Site identity.
		'hide_site_title'     => '0',
		'hide_site_tagline'   => '0',
		'logo_width'          => '150',
		'site_title_size'     => '20',
		'site_tagline_size'   => '12',

		// Top bar, header and navigation.
		'top_bar_enabled'       => '0',
		'top_bar_tone'          => 'inherit',
		'top_bar_width'         => 'wide',
		'top_bar_alignment'     => 'right',
		'top_bar_padding'       => '12',
		'header_width'          => 'wide',
		'header_behavior'       => 'static',
		'header_tone'           => 'surface',
		'header_density'        => 'normal',
		'header_padding'        => '24',
		'header_divider'        => 'visible',
		'header_alignment'      => 'space-between',
		'navigation_align'      => 'right',
		'navigation_gap'        => 'normal',
		'navigation_font_size'  => '15',
		'navigation_weight'     => '650',
		'navigation_transform'  => 'none',
		'mobile_menu_style'     => 'overlay',
		'mobile_menu_breakpoint'=> '781',
		'header_sticky_shadow'  => 'subtle',

		// Social Icons placement. Hidden by default for backwards compatibility.
		// Shared mode lets one native Social Icons block feed both locations.
		'social_content_mode'          => 'shared',
		'social_header_position'       => 'hidden',
		'social_header_mobile_enabled' => '0',
		'social_footer_position'       => 'hidden',
		'social_footer_alignment'      => 'center',
		'social_icon_size'             => '24',
		'social_icon_gap'              => '10',

		// Responsive adjustments.
		'tablet_gutter'             => '32',
		'mobile_logo_width'         => '32',
		'mobile_logo_width_mode'    => 'inherit',
		'mobile_site_title_size'    => '18',
		'mobile_site_tagline_size'  => '11',
		'mobile_navigation_font_size'=> '16',
		'mobile_footer_columns'           => '1',
		'hide_footer_widgets_on_mobile'  => '0',

		// Blog and footer.
		'blog_surface'              => 'elevated',
		'blog_layout'               => 'list',
		'blog_columns'              => '2',
		'blog_card_layout'          => 'compact',
		'blog_image_ratio'          => 'landscape',
		'blog_feed_title'           => __( 'Últimas publicaciones', 'creceweb-lumen' ),
		'blog_feed_description'     => __( 'Lecturas prácticas para construir un sitio claro y útil.', 'creceweb-lumen' ),
		'blog_show_featured_image'  => '1',
		'blog_show_category'        => '1',
		'blog_show_meta'            => '1',
		'blog_show_excerpt'         => '1',
		'blog_show_read_more'       => '1',
		'blog_read_more_label'      => __( 'Seguir leyendo', 'creceweb-lumen' ),
		'blog_read_more_style'      => 'lumen',
		'blog_read_more_shape'      => 'pill',
		'blog_read_more_background_color'       => '',
		'blog_read_more_hover_background_color' => '',
		'blog_read_more_text_color'             => '',
		'blog_read_more_hover_text_color'       => '',
		'blog_read_more_border_color'           => '',

		// Cabecera guiada del blog. Se aplica solo a la página de entradas y a la portada cuando muestra últimas entradas.
		'blog_intro_enabled'          => '1',
		'blog_intro_eyebrow'          => __( 'Blog · Ideas y novedades', 'creceweb-lumen' ),
		'blog_intro_title'            => '',
		'blog_intro_description'      => __( 'Ideas, recursos y novedades para impulsar tu presencia digital.', 'creceweb-lumen' ),
		'blog_intro_show_button'      => '1',
		'blog_intro_button_label'     => __( 'Ver artículos', 'creceweb-lumen' ),
		'blog_intro_button_url'       => '#cw-latest-posts',
		'blog_intro_visual_type'      => 'illustration',
		'blog_intro_image'            => '0',
		'blog_intro_alignment'        => 'left',

		'single_layout'             => 'standard',
		'single_header_alignment'   => 'left',
		'single_featured_position'  => 'below_header',
		'single_meta_visibility'    => 'date_author',

		// Screen layout and native sidebar.
		'sidebar_layout'             => 'right',
		'archive_sidebar_layout'     => 'inherit',
		'single_sidebar_layout'      => 'inherit',
		'page_sidebar_layout'        => 'inherit',
		'sidebar_width'              => 'standard',

		'footer_tone'               => 'surface',
		'footer_density'        => 'normal',
		'footer_padding'        => '48',
		'footer_widget_gap'     => '32',
		'footer_widget_columns' => 'auto',
		'show_copyright'        => '1',

		// Accessibility.
		'motion_preference' => 'system',
	);
}

/**
 * Returns allowed values for select based controls.
 *
 * @return array<string, array<int, string>>
 */
function get_customization_choices(): array {
	return array(
		'font_preset'            => array( 'system-sans', 'system-serif', 'system-mono' ),
		'heading_preset'         => array( 'inherit', 'system-sans', 'system-serif' ),
		'font_scale'             => array( 'compact', 'standard', 'comfortable' ),
		'heading_weight'         => array( '500', '600', '700', '800' ),
		'spacing_density'        => array( 'compact', 'normal', 'spacious' ),
		'shape'                  => array( 'square', 'soft', 'rounded' ),
		'button_style'           => array( 'solid', 'soft', 'outline' ),
		'link_decoration'        => array( 'always', 'hover', 'underline', 'never' ),
		'top_bar_tone'           => array( 'inherit', 'surface', 'primary', 'custom' ),
		'top_bar_width'          => array( 'full', 'content', 'wide' ),
		'top_bar_alignment'      => array( 'left', 'center', 'right' ),
		'header_width'           => array( 'content', 'wide', 'max', 'full' ),
		'header_behavior'        => array( 'static', 'sticky', 'fixed' ),
		'header_tone'            => array( 'surface', 'primary' ),
		'header_density'         => array( 'compact', 'normal', 'spacious' ),
		'header_divider'         => array( 'visible', 'hidden' ),
		'header_alignment'       => array( 'left', 'center', 'space-between' ),
		'navigation_align'       => array( 'left', 'center', 'right' ),
		'navigation_gap'         => array( 'compact', 'normal', 'spacious' ),
		'navigation_weight'      => array( '400', '500', '600', '650', '700' ),
		'navigation_transform'   => array( 'none', 'uppercase' ),
		'mobile_menu_style'      => array( 'overlay', 'drawer' ),
		'mobile_menu_breakpoint' => array( '781', '960', '1024' ),
		'blog_surface'             => array( 'minimal', 'bordered', 'elevated' ),
		'blog_layout'              => array( 'list', 'grid' ),
		'blog_columns'             => array( '1', '2', '3' ),
		'blog_card_layout'         => array( 'vertical', 'media_left', 'compact' ),
		'blog_image_ratio'         => array( 'natural', 'landscape', 'square' ),
		'blog_read_more_style'      => array( 'lumen', 'inherit', 'solid', 'outline', 'text' ),
		'blog_read_more_shape'      => array( 'inherit', 'square', 'soft', 'pill' ),
		'blog_intro_visual_type'    => array( 'illustration', 'image', 'none' ),
		'blog_intro_alignment'      => array( 'left', 'center' ),
		'single_layout'            => array( 'standard', 'narrow', 'wide', 'full' ),
		'single_header_alignment'  => array( 'left', 'center' ),
		'single_featured_position' => array( 'above_header', 'below_header', 'hidden' ),
		'single_meta_visibility'   => array( 'date_author', 'date', 'hidden' ),
		'sidebar_layout'            => array( 'none', 'left', 'right' ),
		'archive_sidebar_layout'    => array( 'inherit', 'none', 'left', 'right' ),
		'single_sidebar_layout'     => array( 'inherit', 'none', 'left', 'right' ),
		'page_sidebar_layout'       => array( 'inherit', 'none', 'left', 'right' ),
		'sidebar_width'             => array( 'narrow', 'standard', 'wide' ),
		'footer_tone'              => array( 'surface', 'primary' ),
		'footer_density'         => array( 'compact', 'normal', 'spacious', 'none' ),
		'footer_widget_columns'  => array( 'auto', '1', '2', '3', '4', '5' ),
		'motion_preference'      => array( 'system', 'reduce' ),
		'design_preset'          => array( 'professional', 'commercial', 'editorial', 'custom' ),
		'header_sticky_shadow'   => array( 'none', 'subtle', 'strong' ),
		'social_content_mode' => array( 'shared', 'separate' ),
		'social_header_position' => array( 'hidden', 'before_navigation', 'after_navigation' ),
		'social_footer_position' => array( 'hidden', 'before_widgets', 'after_widgets' ),
		'social_footer_alignment' => array( 'left', 'center', 'right' ),
		'mobile_footer_columns'  => array( '1', '2' ),
		'mobile_logo_width_mode' => array( 'inherit', 'custom' ),
		'floating_action'       => array( 'none', 'back_to_top', 'whatsapp' ),
	);
}

/**
 * Returns visual starting points for the guided Customizer.
 *
 * Presets only update CreceWeb base values. They never alter blocks, plantillas,
 * widgets, or styles saved by Gutenberg and visual builders.
 *
 * @return array<string, array<string, mixed>>
 */
function get_design_presets(): array {
	return array(
		'professional' => array(
			'label'       => __( 'Profesional', 'creceweb-lumen' ),
			'description' => __( 'Sobrio, claro y confiable para empresas, estudios y servicios.', 'creceweb-lumen' ),
			'values'      => array(
				'font_preset' => 'system-sans', 'heading_preset' => 'system-sans', 'font_scale' => 'standard', 'heading_weight' => '700',
				'shape' => 'soft', 'content_width' => '760px', 'wide_width' => '1160px',
				'spacing_density' => 'normal', 'header_density' => 'normal', 'footer_density' => 'normal',
				'button_style' => 'solid', 'button_radius' => '8', 'button_padding_y' => '13', 'button_padding_x' => '20', 'link_decoration' => 'hover',
				'blog_surface' => 'bordered', 'blog_layout' => 'list', 'blog_card_layout' => 'vertical', 'blog_image_ratio' => 'landscape',
				'header_tone' => 'surface', 'footer_tone' => 'primary',
				'primary_color' => '#0f172a', 'accent_color' => '#10b981', 'accent_strong' => '#059669',
				'background_color' => '#f8fafc', 'surface_color' => '#ffffff', 'text_color' => '#111827', 'heading_color' => '#0f172a', 'link_color' => '#059669', 'text_muted_color' => '#64748b', 'border_color' => '#cbd5e1',
				'navigation_color' => '#0f172a', 'navigation_hover_color' => '#059669', 'navigation_active_color' => '#059669', 'submenu_background_color' => '#ffffff',
				'button_background_color' => '#10b981', 'button_hover_color' => '#059669', 'button_text_color' => '#ffffff',
				'footer_background_color' => '#0f172a', 'footer_text_color' => '#ffffff', 'footer_link_color' => '#a7f3d0', 'copyright_background_color' => '#020617', 'copyright_text_color' => '#ffffff',
				'form_background_color' => '#ffffff', 'form_border_color' => '#cbd5e1', 'form_focus_color' => '#059669',
			),
		),
		'commercial' => array(
			'label'       => __( 'Cercano y comercial', 'creceweb-lumen' ),
			'description' => __( 'Más contraste, llamados visibles y energía para captar consultas.', 'creceweb-lumen' ),
			'values'      => array(
				'font_preset' => 'system-sans', 'heading_preset' => 'system-sans', 'font_scale' => 'comfortable', 'heading_weight' => '800',
				'shape' => 'rounded', 'content_width' => '800px', 'wide_width' => '1320px',
				'spacing_density' => 'spacious', 'header_density' => 'normal', 'footer_density' => 'spacious',
				'button_style' => 'solid', 'button_radius' => '16', 'button_padding_y' => '15', 'button_padding_x' => '26', 'link_decoration' => 'hover',
				'blog_surface' => 'elevated', 'blog_layout' => 'grid', 'blog_columns' => '3', 'blog_card_layout' => 'vertical', 'blog_image_ratio' => 'landscape',
				'header_tone' => 'primary', 'footer_tone' => 'primary',
				'primary_color' => '#172554', 'accent_color' => '#f97316', 'accent_strong' => '#ea580c',
				'background_color' => '#fff7ed', 'surface_color' => '#ffffff', 'text_color' => '#1f2937', 'heading_color' => '#172554', 'link_color' => '#ea580c', 'text_muted_color' => '#6b7280', 'border_color' => '#fed7aa',
				'navigation_color' => '#ffffff', 'navigation_hover_color' => '#fed7aa', 'navigation_active_color' => '#fed7aa', 'submenu_background_color' => '#ffffff',
				'button_background_color' => '#f97316', 'button_hover_color' => '#ea580c', 'button_text_color' => '#ffffff',
				'footer_background_color' => '#172554', 'footer_text_color' => '#ffffff', 'footer_link_color' => '#fed7aa', 'copyright_background_color' => '#0b1120', 'copyright_text_color' => '#ffffff',
				'form_background_color' => '#ffffff', 'form_border_color' => '#fdba74', 'form_focus_color' => '#f97316',
			),
		),
		'editorial' => array(
			'label'       => __( 'Editorial y blog', 'creceweb-lumen' ),
			'description' => __( 'Lectura protagonista, títulos expresivos y una presentación más serena.', 'creceweb-lumen' ),
			'values'      => array(
				'font_preset' => 'system-serif', 'heading_preset' => 'system-serif', 'font_scale' => 'comfortable', 'heading_weight' => '700',
				'shape' => 'square', 'content_width' => '700px', 'wide_width' => '1120px',
				'spacing_density' => 'normal', 'header_density' => 'compact', 'footer_density' => 'normal',
				'button_style' => 'outline', 'button_radius' => '2', 'button_padding_y' => '12', 'button_padding_x' => '18', 'link_decoration' => 'underline',
				'blog_surface' => 'minimal', 'blog_layout' => 'list', 'blog_card_layout' => 'vertical', 'blog_image_ratio' => 'landscape',
				'header_tone' => 'surface', 'footer_tone' => 'surface',
				'primary_color' => '#1f2937', 'accent_color' => '#9a3412', 'accent_strong' => '#7c2d12',
				'background_color' => '#fffdf7', 'surface_color' => '#ffffff', 'text_color' => '#292524', 'heading_color' => '#1c1917', 'link_color' => '#9a3412', 'text_muted_color' => '#78716c', 'border_color' => '#e7e5e4',
				'navigation_color' => '#292524', 'navigation_hover_color' => '#9a3412', 'navigation_active_color' => '#9a3412', 'submenu_background_color' => '#ffffff',
				'button_background_color' => '#ffffff', 'button_hover_color' => '#f5f5f4', 'button_text_color' => '#1c1917',
				'footer_background_color' => '#ffffff', 'footer_text_color' => '#292524', 'footer_link_color' => '#9a3412', 'copyright_background_color' => '#1c1917', 'copyright_text_color' => '#ffffff',
				'form_background_color' => '#ffffff', 'form_border_color' => '#d6d3d1', 'form_focus_color' => '#9a3412',
			),
		),
	);
}

/**
 * Migrates the previous settings option to the directory-safe theme prefix.
 *
 * The migration is idempotent and removes the legacy option after the new
 * single-array option is available. Existing user values are sanitized with
 * the same allowlist used by the Customizer.
 *
 * @return void
 */
function maybe_migrate_legacy_customization_option(): void {
	$missing = '__creceweb_lumen_option_missing__';
	$current = get_option( CUSTOMIZATION_OPTION, $missing );

	if ( $missing !== $current ) {
		if ( $missing !== get_option( LEGACY_CUSTOMIZATION_OPTION, $missing ) ) {
			delete_option( LEGACY_CUSTOMIZATION_OPTION );
		}
		return;
	}

	$legacy = get_option( LEGACY_CUSTOMIZATION_OPTION, $missing );
	if ( $missing === $legacy ) {
		return;
	}

	if ( ! is_array( $legacy ) ) {
		delete_option( LEGACY_CUSTOMIZATION_OPTION );
		return;
	}

	$clean = sanitize_customizations( $legacy );
	add_option( CUSTOMIZATION_OPTION, $clean, '', true );

	if ( $missing !== get_option( CUSTOMIZATION_OPTION, $missing ) ) {
		delete_option( LEGACY_CUSTOMIZATION_OPTION );
	}
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\\maybe_migrate_legacy_customization_option', 20 );

/**
 * Returns the current settings with safe defaults.
 *
 * @return array<string, string>
 */
function get_customizations(): array {
	maybe_migrate_legacy_customization_option();
	$settings = get_option( CUSTOMIZATION_OPTION, array() );

	if ( ! is_array( $settings ) ) {
		$settings = array();
	}

	$customizations = sanitize_customizations( wp_parse_args( $settings, get_customization_defaults() ) );

	/**
	 * Filters the final safe Lumen settings before front-end use.
	 *
	 * Extensions may only adapt keys already owned by Lumen. The second
	 * sanitization keeps this bridge from accepting new or unsafe values.
	 *
	 * @param array<string, string> $customizations Safe Lumen settings.
	 */
	$filtered = apply_filters( 'creceweb_lumen_get_customizations', $customizations );

	return is_array( $filtered ) ? sanitize_customizations( $filtered ) : $customizations;
}

/**
 * Sanitizes one Customizer setting.
 *
 * @param string $key Setting key.
 * @param mixed  $value Raw value.
 * @return string
 */
function sanitize_customization_value( string $key, $value ): string {
	$defaults = get_customization_defaults();
	$choices  = get_customization_choices();

	if ( ! array_key_exists( $key, $defaults ) ) {
		return '';
	}

	/* accent_strong predates the *_color naming convention but is a color value. */
	if ( false !== strpos( $key, 'color' ) || 'accent_strong' === $key ) {
		$color = sanitize_hex_color( (string) $value );
		return $color ? strtolower( $color ) : $defaults[ $key ];
	}

	$boolean_keys = array( 'hide_site_title', 'hide_site_tagline', 'top_bar_enabled', 'social_header_mobile_enabled', 'hide_footer_widgets_on_mobile', 'show_copyright', 'show_advanced_controls', 'blog_show_featured_image', 'blog_show_category', 'blog_show_meta', 'blog_show_excerpt', 'blog_show_read_more', 'blog_intro_enabled', 'blog_intro_show_button' );
	if ( in_array( $key, $boolean_keys, true ) ) {
		return '1' === (string) $value ? '1' : '0';
	}

	if ( 'blog_intro_image' === $key ) {
		return (string) absint( $value );
	}

	if ( in_array( $key, array( 'blog_intro_description', 'blog_feed_description' ), true ) ) {
		return sanitize_textarea_field( (string) $value );
	}

	if ( 'blog_intro_button_url' === $key ) {
		$value = trim( (string) $value );
		if ( '' === $value ) {
			return '';
		}
		if ( 0 === strpos( $value, '#' ) ) {
			return '#' . sanitize_title_with_dashes( ltrim( $value, '#' ) );
		}
		$url = esc_url_raw( $value );
		return $url ? $url : $defaults[ $key ];
	}

	if ( in_array( $key, array( 'blog_intro_eyebrow', 'blog_intro_title', 'blog_intro_button_label', 'blog_feed_title', 'blog_read_more_label' ), true ) ) {
		return sanitize_text_field( (string) $value );
	}

	if ( 'whatsapp_number' === $key ) {
		$number = preg_replace( '/\D+/', '', (string) $value );
		$length = strlen( $number );
		return ( $length >= 7 && $length <= 15 ) ? $number : '';
	}

	if ( 'whatsapp_message' === $key ) {
		return sanitize_textarea_field( (string) $value );
	}

	$dimension_rules = array(
		'logo_width'         => array( 24, 320, false ),
		'content_width'      => array( 640, 1100, true ),
		'wide_width'         => array( 960, 1600, true ),
		'mobile_gutter'      => array( 12, 40, true ),
		'tablet_gutter'      => array( 20, 64, false ),
		'mobile_logo_width'  => array( 24, 240, false ),
		'mobile_site_title_size' => array( 12, 36, false ),
		'mobile_site_tagline_size' => array( 10, 20, false ),
		'mobile_navigation_font_size' => array( 12, 22, false ),
		'top_bar_padding'    => array( 6, 32, false ),
		'header_padding'     => array( 12, 56, false ),
		'site_title_size'    => array( 16, 42, false ),
		'site_tagline_size'  => array( 10, 24, false ),
		'navigation_font_size'=> array( 12, 22, false ),
		'button_radius'      => array( 0, 48, false ),
		'button_padding_y'   => array( 8, 28, false ),
		'button_padding_x'   => array( 12, 44, false ),
		'form_radius'        => array( 0, 36, false ),
		'footer_padding'     => array( 24, 112, false ),
		'footer_widget_gap'  => array( 12, 72, false ),
		'social_icon_size'   => array( 16, 40, false ),
		'social_icon_gap'    => array( 4, 24, false ),
		'submenu_hover_background_opacity' => array( 0, 100, false ),
		'floating_action_size' => array( 40, 80, false ),
	);

	if ( isset( $dimension_rules[ $key ] ) ) {
		list( $minimum, $maximum, $append_px ) = $dimension_rules[ $key ];
		$number = absint( $value );
		if ( ! $number && 0 !== $minimum ) {
			$number = absint( $defaults[ $key ] );
		}
		$number = min( $maximum, max( $minimum, $number ) );
		return $append_px ? $number . 'px' : (string) $number;
	}

	$value = sanitize_text_field( (string) $value );

	if ( isset( $choices[ $key ] ) && in_array( $value, $choices[ $key ], true ) ) {
		return $value;
	}

	return $defaults[ $key ];
}

/**
 * Sanitizes the full setting option.
 *
 * @param mixed $input Raw value.
 * @return array<string, string>
 */
function sanitize_customizations( $input ): array {
	$input    = is_array( $input ) ? $input : array();
	$defaults = get_customization_defaults();
	$clean    = array();

	foreach ( $defaults as $key => $default ) {
		$clean[ $key ] = sanitize_customization_value( $key, $input[ $key ] ?? $default );
	}

	return $clean;
}

/**
 * Registers the single array option used by the Customizer.
 *
 * @return void
 */
function register_customization_settings(): void {
	register_setting(
		'creceweb_lumen_customization',
		CUSTOMIZATION_OPTION,
		array(
			'type'              => 'array',
			'sanitize_callback' => __NAMESPACE__ . '\\sanitize_customizations',
			'default'           => get_customization_defaults(),
		)
	);
}
add_action( 'admin_init', __NAMESPACE__ . '\\register_customization_settings' );

/**
 * Adds presentational classes without altering saved page content.
 *
 * @param string[] $classes Current classes.
 * @return string[]
 */
function add_customization_body_classes( array $classes ): array {
	$settings = get_customizations();
	$class_keys = array(
		'spacing_density', 'header_behavior', 'header_tone', 'header_density', 'header_divider', 'header_alignment', 'header_sticky_shadow',
		'top_bar_tone', 'top_bar_width', 'top_bar_alignment',
		'navigation_align', 'navigation_gap', 'navigation_transform',
		'mobile_menu_style', 'mobile_menu_breakpoint', 'footer_tone', 'footer_density',
		'footer_widget_columns', 'blog_surface', 'blog_layout', 'blog_columns', 'blog_image_ratio', 'blog_read_more_style', 'blog_read_more_shape',
		'sidebar_layout', 'archive_sidebar_layout', 'single_sidebar_layout', 'page_sidebar_layout', 'sidebar_width',
		'shape', 'button_style', 'link_decoration', 'motion_preference',
		'hide_site_title', 'hide_site_tagline', 'hide_footer_widgets_on_mobile', 'show_copyright',
	);

	foreach ( $class_keys as $key ) {
		$classes[] = 'cw-' . str_replace( '_', '-', $key ) . '--' . $settings[ $key ];
	}

	return $classes;
}
add_filter( 'body_class', __NAMESPACE__ . '\\add_customization_body_classes' );

/**
 * Returns the CSS stack for a supported typography preset.
 *
 * @param string $preset Preset name.
 * @return string
 */
function get_font_stack( string $preset ): string {
	$presets = array(
		'system-sans'  => "ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif",
		'system-serif' => "ui-serif, Georgia, Cambria, 'Times New Roman', serif",
		'system-mono'  => 'ui-monospace, SFMono-Regular, Menlo, Consolas, monospace',
	);

	return $presets[ $preset ] ?? $presets['system-sans'];
}

/**
 * Prints safe Customizer variables and scoped style rules.
 *
 * @param bool $include_static_hero_fallbacks Include Hero layout fallbacks needed by editor contexts without lumen.css.
 * @param bool $include_editor_styles          Include Gutenberg-only selectors used by editor previews.
 * @param bool $include_frontend_static         Include invariant frontend contracts that are externalized on public requests.
 * @return string
 */
function get_customization_css(
	bool $include_static_hero_fallbacks = true,
	bool $include_editor_styles = true,
	bool $include_frontend_static = true
): string {
	$settings = get_customizations();
	$mobile_breakpoint = in_array( (string) ( $settings['mobile_menu_breakpoint'] ?? '781' ), array( '781', '960', '1024' ), true ) ? absint( $settings['mobile_menu_breakpoint'] ) : 781;
	$mobile_logo_width = 'custom' === (string) ( $settings['mobile_logo_width_mode'] ?? 'inherit' )
		? absint( $settings['mobile_logo_width'] ) . 'px'
		: 'var(--cw-logo-width)';

	$font_scales = array(
		'compact'     => array(
			'body' => '0.9375rem', 'small' => '0.8125rem', 'display' => 'clamp(2.25rem, 4.5vw, 4rem)',
			'h1' => 'clamp(2rem, 3.6vw, 3.1rem)', 'h2' => 'clamp(1.6rem, 2.7vw, 2.25rem)', 'h3' => 'clamp(1.28rem, 2vw, 1.65rem)', 'h4' => '1.125rem',
		),
		'standard'    => array(
			'body' => '1rem', 'small' => '0.875rem', 'display' => 'clamp(2.5rem, 5vw, 4.5rem)',
			'h1' => 'clamp(2.25rem, 4vw, 3.5rem)', 'h2' => 'clamp(1.75rem, 3vw, 2.55rem)', 'h3' => 'clamp(1.4rem, 2.2vw, 1.85rem)', 'h4' => '1.2rem',
		),
		'comfortable' => array(
			'body' => '1.0625rem', 'small' => '0.9375rem', 'display' => 'clamp(2.75rem, 5.5vw, 4.75rem)',
			'h1' => 'clamp(2.5rem, 4.5vw, 4rem)', 'h2' => 'clamp(1.95rem, 3.35vw, 2.9rem)', 'h3' => 'clamp(1.55rem, 2.45vw, 2.05rem)', 'h4' => '1.3rem',
		),
	);
	$shape_scales = array(
		'square'  => array( 'sm' => '0.25rem', 'md' => '0.375rem', 'lg' => '0.5rem', 'xl' => '0.75rem' ),
		'soft'    => array( 'sm' => '0.5rem', 'md' => '0.75rem', 'lg' => '1rem', 'xl' => '1.5rem' ),
		'rounded' => array( 'sm' => '0.75rem', 'md' => '1rem', 'lg' => '1.5rem', 'xl' => '2rem' ),
	);
	$header_widths = array(
		'content' => $settings['content_width'],
		'wide'    => $settings['wide_width'],
		'max'     => '1280px',
		'full'    => '100%',
	);
	$top_bar_widths = array(
		'full'    => '100%',
		'content' => $settings['content_width'],
		'wide'    => $settings['wide_width'],
	);
	$alignments = array(
		'left'          => 'flex-start',
		'center'        => 'center',
		'right'         => 'flex-end',
		'space-between' => 'space-between',
	);
	$navigation_gaps = array( 'compact' => '0.75rem', 'normal' => '1.25rem', 'spacious' => '2rem' );
	$sidebar_widths = array( 'narrow' => '260px', 'standard' => '320px', 'wide' => '380px' );
	$header_density_scales = array( 'compact' => 0.75, 'normal' => 1, 'spacious' => 1.3 );
	$footer_density_scales = array( 'compact' => 0.75, 'normal' => 1, 'spacious' => 1.3, 'none' => 0 );
	$header_effective_padding = (int) round( absint( $settings['header_padding'] ) * ( $header_density_scales[ $settings['header_density'] ] ?? 1 ) );
	$footer_effective_padding = (int) round( absint( $settings['footer_padding'] ) * ( $footer_density_scales[ $settings['footer_density'] ] ?? 1 ) );

	$font_family    = get_font_stack( $settings['font_preset'] );
	$heading_family = 'inherit' === $settings['heading_preset'] ? $font_family : get_font_stack( $settings['heading_preset'] );
	$font_scale     = $font_scales[ $settings['font_scale'] ];
	$shape          = $shape_scales[ $settings['shape'] ];

	$variables = array(
		'--cw-color-primary:' . $settings['primary_color'],
		'--cw-color-accent:' . $settings['accent_color'],
		'--cw-color-accent-strong:' . $settings['accent_strong'],
		'--cw-color-background:' . $settings['background_color'],
		'--cw-color-surface:' . $settings['surface_color'],
		'--cw-color-text:' . $settings['text_color'],
		'--cw-color-heading:' . $settings['heading_color'],
		'--cw-color-link:' . $settings['link_color'],
		'--cw-color-text-muted:' . $settings['text_muted_color'],
		'--cw-color-border:' . $settings['border_color'],
		'--cw-color-focus:' . $settings['focus_color'],
		'--cw-navigation-color:' . $settings['navigation_color'],
		'--cw-navigation-hover-color:' . $settings['navigation_hover_color'],
		'--cw-navigation-active-color:' . $settings['navigation_active_color'],
		'--cw-submenu-background:' . $settings['submenu_background_color'],
		'--cw-button-background:' . $settings['button_background_color'],
		'--cw-button-hover:' . $settings['button_hover_color'],
		'--cw-button-text:' . $settings['button_text_color'],
		'--cw-footer-background:' . $settings['footer_background_color'],
		'--cw-footer-text:' . $settings['footer_text_color'],
		'--cw-footer-link:' . $settings['footer_link_color'],
		'--cw-copyright-background:' . $settings['copyright_background_color'],
		'--cw-copyright-text:' . $settings['copyright_text_color'],
		'--cw-form-background:' . $settings['form_background_color'],
		'--cw-form-border:' . $settings['form_border_color'],
		'--cw-form-focus:' . $settings['form_focus_color'],
		'--cw-topbar-background:' . $settings['top_bar_background_color'],
		'--cw-topbar-text:' . $settings['top_bar_text_color'],
		'--cw-topbar-link:' . $settings['top_bar_link_color'],
		'--cw-layout-content:' . $settings['content_width'],
		'--cw-layout-wide:' . $settings['wide_width'],
		'--cw-mobile-gutter:' . $settings['mobile_gutter'],
		'--cw-tablet-gutter:' . $settings['tablet_gutter'] . 'px',
		'--cw-mobile-logo-width:' . $mobile_logo_width,
		'--cw-mobile-site-title-size:' . $settings['mobile_site_title_size'] . 'px',
		'--cw-mobile-site-tagline-size:' . $settings['mobile_site_tagline_size'] . 'px',
		'--cw-mobile-navigation-font-size:' . $settings['mobile_navigation_font_size'] . 'px',
		'--cw-mobile-footer-columns:' . $settings['mobile_footer_columns'],
		'--cw-floating-action-background:' . $settings['floating_action_background_color'],
		'--cw-floating-action-icon-color:' . $settings['floating_action_icon_color'],
		'--cw-floating-action-size:' . $settings['floating_action_size'] . 'px',
		'--cw-header-width:' . $header_widths[ $settings['header_width'] ],
		'--cw-topbar-width:' . $top_bar_widths[ $settings['top_bar_width'] ],
		'--cw-topbar-alignment:' . $alignments[ $settings['top_bar_alignment'] ],
		'--cw-header-alignment:' . $alignments[ $settings['header_alignment'] ],
		'--cw-navigation-alignment:' . $alignments[ $settings['navigation_align'] ],
		'--cw-navigation-gap:' . $navigation_gaps[ $settings['navigation_gap'] ],
		'--cw-header-custom-padding:' . $settings['header_padding'] . 'px',
		'--cw-header-effective-padding:' . $header_effective_padding . 'px',
		'--cw-topbar-padding:' . $settings['top_bar_padding'] . 'px',
		'--cw-footer-custom-padding:' . $settings['footer_padding'] . 'px',
		'--cw-footer-effective-padding:' . $footer_effective_padding . 'px',
		'--cw-footer-widget-gap:' . $settings['footer_widget_gap'] . 'px',
		'--cw-social-icon-size:' . $settings['social_icon_size'] . 'px',
		'--cw-social-icon-gap:' . $settings['social_icon_gap'] . 'px',
		'--cw-social-footer-alignment:' . ( array( 'left' => 'flex-start', 'center' => 'center', 'right' => 'flex-end' )[ $settings['social_footer_alignment'] ] ?? 'center' ),
		'--cw-sidebar-width:' . ( $sidebar_widths[ $settings['sidebar_width'] ] ?? $sidebar_widths['standard'] ),
		'--cw-site-title-size:' . $settings['site_title_size'] . 'px',
		'--cw-site-tagline-size:' . $settings['site_tagline_size'] . 'px',
		'--cw-navigation-font-size:' . $settings['navigation_font_size'] . 'px',
		'--cw-navigation-weight:' . $settings['navigation_weight'],
		'--cw-heading-weight:' . $settings['heading_weight'],
		'--cw-button-radius:' . $settings['button_radius'] . 'px',
		'--cw-button-padding-y:' . $settings['button_padding_y'] . 'px',
		'--cw-button-padding-x:' . $settings['button_padding_x'] . 'px',
		'--cw-form-radius:' . $settings['form_radius'] . 'px',
		'--cw-radius-sm:' . $shape['sm'],
		'--cw-radius-md:' . $shape['md'],
		'--cw-radius-lg:' . $shape['lg'],
		'--cw-radius-xl:' . $shape['xl'],
		'--cw-font-sans:' . $font_family,
		'--cw-font-heading:' . $heading_family,
		'--cw-text-body:' . $font_scale['body'],
		'--cw-text-small:' . $font_scale['small'],
		'--cw-text-display:' . $font_scale['display'],
		'--cw-text-h1:' . $font_scale['h1'],
		'--cw-text-h2:' . $font_scale['h2'],
		'--cw-text-h3:' . $font_scale['h3'],
		'--cw-text-h4:' . $font_scale['h4'],
		'--cw-logo-width:' . $settings['logo_width'] . 'px',
		'--wp--preset--color--primary:' . $settings['primary_color'],
		'--wp--preset--color--accent:' . $settings['accent_color'],
		'--wp--preset--color--accent-strong:' . $settings['accent_strong'],
		'--wp--preset--color--background:' . $settings['background_color'],
		'--wp--preset--color--surface:' . $settings['surface_color'],
		'--wp--preset--color--lumen-text:' . $settings['text_color'],
		'--wp--preset--color--lumen-border:' . $settings['border_color'],
		// Aliases de variables para CSS y contenido previo; ya no generan clases de paleta.
		'--wp--preset--color--text:' . $settings['text_color'],
		'--wp--preset--color--text-muted:' . $settings['text_muted_color'],
		'--wp--preset--color--border:' . $settings['border_color'],
		'--wp--preset--font-family--sans:' . $font_family,
		'--wp--style--global--content-size:' . $settings['content_width'],
		'--wp--style--global--wide-size:' . $settings['wide_width'],
	);

	// Content-specific tokens are opt-in. Omitting empty variables guarantees
	// that existing sites and Lumen patterns render exactly as before.
	$optional_content_variables = array(
		'content_heading_color'           => '--cw-content-heading-color',
		'content_button_background_color' => '--cw-content-button-background',
		'content_button_hover_color'      => '--cw-content-button-hover',
		'content_button_text_color'       => '--cw-content-button-text',
		'content_box_color'               => '--cw-content-box-color',
		'content_bullet_color'            => '--cw-content-bullet-color',
		'blog_read_more_background_color'       => '--cw-blog-read-more-background',
		'blog_read_more_hover_background_color' => '--cw-blog-read-more-hover-background',
		'blog_read_more_text_color'             => '--cw-blog-read-more-text',
		'blog_read_more_hover_text_color'       => '--cw-blog-read-more-hover-text',
		'blog_read_more_border_color'           => '--cw-blog-read-more-border',
	);
	foreach ( $optional_content_variables as $setting_key => $variable_name ) {
		if ( ! empty( $settings[ $setting_key ] ) ) {
			$variables[] = $variable_name . ':' . $settings[ $setting_key ];
		}
	}

	$optional_navigation_variables = array(
		'submenu_hover_text_color'       => '--cw-submenu-hover-text',
		'submenu_hover_background_color' => '--cw-submenu-hover-background',
	);
	foreach ( $optional_navigation_variables as $setting_key => $variable_name ) {
		if ( ! empty( $settings[ $setting_key ] ) ) {
			$variables[] = $variable_name . ':' . $settings[ $setting_key ];
		}
	}
	$variables[] = '--cw-submenu-hover-background-opacity:' . absint( $settings['submenu_hover_background_opacity'] ) . '%';

	$optional_social_variables = array(
		'social_header_color'       => '--cw-social-header-color',
		'social_header_hover_color' => '--cw-social-header-hover-color',
		'social_footer_color'       => '--cw-social-footer-color',
		'social_footer_hover_color' => '--cw-social-footer-hover-color',
	);
	foreach ( $optional_social_variables as $setting_key => $variable_name ) {
		if ( ! empty( $settings[ $setting_key ] ) ) {
			$variables[] = $variable_name . ':' . $settings[ $setting_key ];
		}
	}

	/*
	 * This stylesheet is intentionally unlayered. Global Styles generated from
	 * theme.json are unlayered too; putting these defaults in a lower-priority CSS
	 * layer prevented Customizer typography from taking effect. Explicit block and
	 * builder styles still win through their own selectors or inline declarations.
	 */
	$css = ':root{' . implode( ';', $variables ) . ';}';

	if ( $include_frontend_static ) {
		/* Base typography and content defaults. Explicit per-block values still win. */
		$css .= 'body{font-family:var(--cw-font-sans);font-size:var(--cw-text-body);color:var(--cw-color-text);background:var(--cw-color-background)}';
		$css .= 'body .cw-template-main{font-family:var(--cw-font-sans);font-size:var(--cw-text-body)}';
		$css .= 'body .cw-template-main :where(small,figcaption,.wp-block-image figcaption,.wp-element-caption){font-size:var(--cw-text-small)}';
		if ( $include_editor_styles ) {
			$css .= ':is(body.editor-styles-wrapper,body .editor-styles-wrapper) :where(small,figcaption,.wp-block-image figcaption,.wp-element-caption){font-size:var(--cw-text-small)}';
		}
		$css .= 'body :where(.cw-template-main,.cw-site-header,.cw-site-footer,.cw-footer-widgets) :where(h1,h2,h3,h4,h5,h6,.entry-title,.wp-block-heading,.wp-block-post-title,.wp-block-site-title):not([style*="font-family"]):not([class*="-font-family"]){font-family:var(--cw-font-heading)}';
		$css .= 'body :where(.cw-template-main,.cw-site-header,.cw-site-footer,.cw-footer-widgets) :where(h1,h2,h3,h4,h5,h6,.entry-title,.wp-block-heading,.wp-block-post-title,.wp-block-site-title):not([style*="font-weight"]){font-weight:var(--cw-heading-weight)}';
		$css .= 'body :where(.cw-template-main,.cw-site-header,.cw-site-footer,.cw-footer-widgets) :where(h1,h2,h3,h4,h5,h6,.entry-title,.wp-block-heading,.wp-block-post-title,.wp-block-site-title):not(.has-text-color):not([style*="color"]){color:var(--cw-color-heading)}';
		if ( $include_editor_styles ) {
			$css .= ':is(body.editor-styles-wrapper,body .editor-styles-wrapper) :where(h1,h2,h3,h4,h5,h6,.entry-title,.wp-block-heading,.wp-block-post-title,.wp-block-site-title):not([style*="font-family"]):not([class*="-font-family"]){font-family:var(--cw-font-heading)}';
		}
		if ( $include_editor_styles ) {
			$css .= ':is(body.editor-styles-wrapper,body .editor-styles-wrapper) :where(h1,h2,h3,h4,h5,h6,.entry-title,.wp-block-heading,.wp-block-post-title,.wp-block-site-title):not([style*="font-weight"]){font-weight:var(--cw-heading-weight)}';
		}
		if ( $include_editor_styles ) {
			$css .= ':is(body.editor-styles-wrapper,body .editor-styles-wrapper) :where(h1,h2,h3,h4,h5,h6,.entry-title,.wp-block-heading,.wp-block-post-title,.wp-block-site-title):not(.has-text-color):not([style*="color"]){color:var(--cw-color-heading)}';
		}
		$css .= 'body .cw-template-main .cw-entry .entry-title:not([style*="font-size"]):not([class*="-font-size"]),body .cw-template-main .cw-entry .entry-content h1:not([style*="font-size"]):not([class*="-font-size"]),body .cw-template-main .cw-archive-header h1:not([style*="font-size"]):not([class*="-font-size"]){font-size:var(--cw-text-h1)}body .cw-template-main .cw-entry .entry-content h2:not([style*="font-size"]):not([class*="-font-size"]){font-size:var(--cw-text-h2)}body .cw-template-main .cw-entry .entry-content h3:not([style*="font-size"]):not([class*="-font-size"]){font-size:var(--cw-text-h3)}body .cw-template-main .cw-entry .entry-content h4:not([style*="font-size"]):not([class*="-font-size"]){font-size:var(--cw-text-h4)}';
		if ( $include_editor_styles ) {
			$css .= ':is(body.editor-styles-wrapper,body .editor-styles-wrapper) h1:not([style*="font-size"]):not([class*="-font-size"]){font-size:var(--cw-text-h1)}:is(body.editor-styles-wrapper,body .editor-styles-wrapper) h2:not([style*="font-size"]):not([class*="-font-size"]){font-size:var(--cw-text-h2)}:is(body.editor-styles-wrapper,body .editor-styles-wrapper) h3:not([style*="font-size"]):not([class*="-font-size"]){font-size:var(--cw-text-h3)}:is(body.editor-styles-wrapper,body .editor-styles-wrapper) h4:not([style*="font-size"]):not([class*="-font-size"]){font-size:var(--cw-text-h4)}';
		}
		/* Keep classic sidebar widget headings below the editorial page hierarchy. The rule is unlayered so it also wins over WordPress Global Styles. */
		$css .= 'body .cw-template-main .cw-sidebar .cw-widget :is(.widget-title,.cw-widget__title){margin:0 0 .8rem;font-size:1.35rem;font-weight:var(--cw-heading-weight);letter-spacing:-.022em;line-height:1.18;text-transform:none}';
		$css .= 'body .cw-template-main :where(a:not(.wp-block-button__link):not(.cw-card__more)){color:var(--cw-color-link)}';
		if ( $include_editor_styles ) {
			$css .= ':is(body.editor-styles-wrapper,body .editor-styles-wrapper) :where(a:not(.wp-block-button__link)):not(.has-text-color):not([style*="color"]){color:var(--cw-color-link)}';
		}
	}

	if ( $include_frontend_static ) {
		/* Theme chrome: header, identity and navigation. */
		$css .= ':where(.cw-site-header){padding-block:0}:where(.cw-site-header)>.cw-site-header__inner{width:min(calc(100% - var(--cw-mobile-gutter) - var(--cw-mobile-gutter)),var(--cw-header-width));max-width:var(--cw-header-width);margin-inline:auto;padding-block:var(--cw-header-effective-padding);box-sizing:border-box;justify-content:var(--cw-header-alignment)}';
		$css .= ':where(.cw-site-header,.cw-site-footer) :is(.wp-block-site-logo,.custom-logo-link){display:block;flex:0 0 auto;line-height:0}:where(.cw-site-header,.cw-site-footer) .wp-block-site-logo img{display:block;width:var(--cw-logo-width);max-width:min(var(--cw-logo-width),54vw);height:auto;object-fit:contain}.cw-site-identity{gap:.55rem}.cw-site-identity__text{gap:0;min-width:0}';
		$css .= ':where(.cw-site-header,.cw-site-footer) :where(.wp-block-site-title,.wp-block-site-title a){font-size:var(--cw-site-title-size);line-height:1.15}:where(.cw-site-header,.cw-site-footer) .wp-block-site-tagline{margin:.16rem 0 0;color:var(--cw-color-text-muted);font-size:var(--cw-site-tagline-size);line-height:1.35}';
		$css .= '.cw-hide-site-title--1 .wp-block-site-title{display:none!important}.cw-hide-site-tagline--1 .wp-block-site-tagline{display:none!important}';
		$css .= ':where(.cw-site-header) .cw-classic-navigation--primary{display:flex;flex:1 1 auto;min-width:0}:where(.cw-site-header) .cw-classic-navigation__menu-container{width:100%}:where(.cw-site-header) .cw-classic-navigation__menu{justify-content:var(--cw-navigation-alignment);gap:var(--cw-navigation-gap)}.cw-navigation-align--left :where(.cw-site-header) .cw-classic-navigation--primary{margin-left:0;margin-right:auto}.cw-navigation-align--center :where(.cw-site-header) .cw-classic-navigation--primary{margin-left:auto;margin-right:auto}.cw-navigation-align--right :where(.cw-site-header) .cw-classic-navigation--primary{margin-left:auto;margin-right:0}:where(.cw-site-header) .cw-classic-navigation__menu a{color:var(--cw-navigation-color);font-size:var(--cw-navigation-font-size);font-weight:var(--cw-navigation-weight);text-transform:var(--cw-navigation-transform,none)}:where(.cw-site-header) .cw-classic-navigation__menu>li>a:hover,:where(.cw-site-header) .cw-classic-navigation__menu>li>a:focus-visible{color:var(--cw-navigation-hover-color)}:where(.cw-site-header) .cw-classic-navigation__menu :is(.current-menu-item,.current_page_item)>a{color:var(--cw-navigation-active-color)}.cw-classic-navigation__menu .sub-menu{background:var(--cw-submenu-background)}';
	}
	if ( ! empty( $settings['submenu_hover_text_color'] ) ) {
		$css .= ':where(.cw-site-header) .cw-classic-navigation__menu .sub-menu a:hover,:where(.cw-site-header) .cw-classic-navigation__menu .sub-menu a:focus-visible{color:var(--cw-submenu-hover-text)}';
	}
	if ( ! empty( $settings['submenu_hover_background_color'] ) ) {
		$css .= ':where(.cw-site-header) .cw-classic-navigation__menu .sub-menu a:hover,:where(.cw-site-header) .cw-classic-navigation__menu .sub-menu a:focus-visible{background:color-mix(in srgb,var(--cw-submenu-hover-background) var(--cw-submenu-hover-background-opacity,100%),transparent)}';
	}
	if ( $include_frontend_static ) {
		$css .= '.cw-navigation-transform--uppercase{--cw-navigation-transform:uppercase}.cw-navigation-transform--none{--cw-navigation-transform:none}';
		$css .= '.cw-header-alignment--left :where(.cw-site-header)>.cw-site-header__inner{justify-content:flex-start}.cw-header-alignment--center :where(.cw-site-header)>.cw-site-header__inner{justify-content:center}.cw-header-alignment--space-between :where(.cw-site-header)>.cw-site-header__inner{justify-content:space-between}.cw-header-alignment--left :where(.cw-site-header) .cw-classic-navigation--primary,.cw-header-alignment--center :where(.cw-site-header) .cw-classic-navigation--primary{flex:0 1 auto;width:auto;margin-inline:0}.cw-header-alignment--left .cw-social-region--header-before,.cw-header-alignment--center .cw-social-region--header-before{margin-left:0}.cw-header-alignment--space-between :where(.cw-site-header) .cw-classic-navigation--primary{flex:1 1 auto;min-width:0;margin-inline:0}.cw-header-divider--hidden :where(.cw-site-header){border-bottom-color:transparent;border-bottom-width:0}';
		$css .= '.cw-header-behavior--static :where(.cw-site-header:not(.cw-lumen-pro-header--overlay)){position:relative;top:auto;z-index:auto;box-shadow:none}.cw-header-behavior--static .cw-site-header.cw-lumen-pro-header--overlay{position:absolute;top:0;left:0;right:0;width:100%;max-width:none;margin:0;box-sizing:border-box;z-index:9990}.cw-header-behavior--sticky .cw-header-sticky-spacer,.cw-header-behavior--fixed .cw-header-sticky-spacer{display:block}.cw-header-behavior--sticky :where(.cw-site-header),.cw-header-behavior--fixed :where(.cw-site-header){position:relative;top:auto;z-index:9990}.cw-header-behavior--sticky .cw-site-header.cw-header-is-fixed,.cw-header-behavior--fixed .cw-site-header.cw-header-is-fixed{position:fixed;top:0;left:0;right:0;width:100%;max-width:none;margin:0;box-sizing:border-box;z-index:99990;box-shadow:0 8px 24px rgb(15 23 42 / 18%)}.admin-bar.cw-header-behavior--sticky .cw-site-header.cw-header-is-fixed,.admin-bar.cw-header-behavior--fixed .cw-site-header.cw-header-is-fixed{top:32px}@media screen and (max-width:782px){.admin-bar.cw-header-behavior--sticky .cw-site-header.cw-header-is-fixed,.admin-bar.cw-header-behavior--fixed .cw-site-header.cw-header-is-fixed{top:46px}}';
		$css .= '.cw-header-tone--primary :where(.cw-site-header){background:var(--cw-color-primary);border-color:var(--cw-color-primary)}.cw-header-tone--primary :where(.cw-site-header) :where(a,.wp-block-site-title,.wp-block-site-tagline){color:#fff}';
	}

	if ( $include_frontend_static ) {
		/* Layout defaults. They affect theme wrappers, never force a builder module. */
		$css .= ':where(.cw-template-main){padding-block:var(--cw-template-space)}.cw-template-main--full-canvas{padding-block:0}.cw-entry--full-canvas>.entry-content>:where(:not(.alignfull):not(.alignwide):not(.elementor)){width:min(calc(100% - 2rem),var(--cw-layout-wide));margin-inline:auto}.cw-entry--full-canvas>.entry-content>:is(.alignfull,.elementor){width:100%;max-width:none;margin-inline:0}.cw-entry--full-canvas>.entry-content>:is(.alignfull,.elementor):first-child{margin-top:0}:where(.cw-template-main) .wp-block-post-content>:is(.wp-block-group,.wp-block-cover,.wp-block-media-text,.wp-block-columns,.wp-block-query,.wp-block-separator)+:is(.wp-block-group,.wp-block-cover,.wp-block-media-text,.wp-block-columns,.wp-block-query,.wp-block-separator){margin-block-start:var(--cw-rhythm-gap)}';
		$css .= ':where(.cw-template-main) .is-layout-constrained>:where(:not(.alignleft):not(.alignright):not(.alignfull):not(.alignwide)){max-width:var(--cw-layout-content)}:where(.cw-template-main) .is-layout-constrained>.alignwide{max-width:var(--cw-layout-wide)}';
		$css .= 'body.cw-spacing-density--compact{--cw-rhythm-gap:clamp(1.75rem,3.5vw,3rem);--cw-rhythm-section-padding:clamp(2.5rem,5vw,4.25rem);--cw-template-space:clamp(2rem,5vw,4.5rem)}body.cw-spacing-density--normal{--cw-rhythm-gap:clamp(2.75rem,5.5vw,4.75rem);--cw-rhythm-section-padding:clamp(3.5rem,7vw,6rem);--cw-template-space:clamp(3rem,7vw,6rem)}body.cw-spacing-density--spacious{--cw-rhythm-gap:clamp(3.75rem,7.5vw,6.75rem);--cw-rhythm-section-padding:clamp(4.5rem,9vw,8rem);--cw-template-space:clamp(4rem,9vw,8rem)}';
	}

	if ( $include_frontend_static ) {
		/* Footer and top-bar chrome. Widget/block styles inside may override these defaults. */
		$css .= '.cw-site-footer{background:var(--cw-footer-background);border-color:var(--cw-color-border);color:var(--cw-footer-text)}.cw-footer-widgets{padding-block:var(--cw-footer-effective-padding)}body.cw-footer-density--none .cw-site-footer{margin-top:0!important}.cw-site-footer :where(a){color:var(--cw-footer-link)}.cw-site-footer :where(.widget-title,.cw-widget__title,.wp-block-heading){color:inherit}';
		$css .= ':where(.cw-site-copyright){background:var(--cw-copyright-background);color:var(--cw-copyright-text)}.cw-show-copyright--0 .cw-site-copyright{display:none!important}.cw-footer-widgets__inner{gap:var(--cw-footer-widget-gap)}.cw-footer-widgets__section + .cw-footer-widgets__section{margin-top:var(--cw-footer-widget-gap);padding-top:var(--cw-footer-widget-gap)}';
		$css .= '.cw-footer-tone--primary .cw-site-footer{background:var(--cw-color-primary);border-color:var(--cw-color-primary);color:#fff}.cw-footer-tone--primary .cw-site-footer :where(a,.wp-block-site-title,.wp-block-site-tagline,.widget-title,.cw-widget__title,.wp-block-heading){color:#fff}';
		$css .= ':where(.cw-top-bar-widgets){padding-block:var(--cw-topbar-padding)}:where(.cw-top-bar-widgets__inner){width:min(100%,var(--cw-topbar-width));max-width:var(--cw-topbar-width);justify-content:var(--cw-topbar-alignment)}.cw-top-bar-width--full :where(.cw-top-bar-widgets__inner){width:100%;max-width:none}.cw-top-bar-tone--inherit.cw-header-tone--surface :where(.cw-top-bar-widgets){background:var(--cw-color-surface);color:var(--cw-color-text);border-color:var(--cw-color-border)}.cw-top-bar-tone--inherit.cw-header-tone--surface :where(.cw-top-bar-widgets) :where(a,.widget-title,.cw-widget__title,.wp-block-heading){color:var(--cw-color-link)}.cw-top-bar-tone--inherit.cw-header-tone--primary :where(.cw-top-bar-widgets){background:var(--cw-color-primary);color:#fff;border-color:var(--cw-color-primary)}.cw-top-bar-tone--inherit.cw-header-tone--primary :where(.cw-top-bar-widgets) :where(a,.widget-title,.cw-widget__title,.wp-block-heading){color:#fff}.cw-top-bar-tone--surface :where(.cw-top-bar-widgets){background:var(--cw-color-surface);color:var(--cw-color-text);border-color:var(--cw-color-border)}.cw-top-bar-tone--surface :where(.cw-top-bar-widgets) :where(a,.widget-title,.cw-widget__title,.wp-block-heading){color:var(--cw-color-link)}.cw-top-bar-tone--primary :where(.cw-top-bar-widgets){background:var(--cw-color-primary);color:#fff;border-color:var(--cw-color-primary)}.cw-top-bar-tone--primary :where(.cw-top-bar-widgets) :where(a,.widget-title,.cw-widget__title,.wp-block-heading){color:#fff}.cw-top-bar-tone--custom :where(.cw-top-bar-widgets){background:var(--cw-topbar-background);color:var(--cw-topbar-text);border-color:var(--cw-topbar-background)}.cw-top-bar-tone--custom :where(.cw-top-bar-widgets) :where(a,.widget-title,.cw-widget__title,.wp-block-heading){color:var(--cw-topbar-link)}';
	}

	if ( $include_frontend_static ) {
		/* Native Social Icons integration. Content stays in dedicated WordPress widget areas; Lumen owns only placement and presentation. */
		$css .= '.cw-social-region{box-sizing:border-box}.cw-social-region .cw-social-widget{display:flex;align-items:center;margin:0}.cw-social-region .wp-block-social-links{display:flex;flex-wrap:wrap;align-items:center;gap:var(--cw-social-icon-gap);margin:0!important;padding:0;list-style:none!important;font-size:var(--cw-social-icon-size)!important;line-height:1}.cw-social-region .wp-block-social-links>li::marker{content:""!important}.cw-social-region .wp-block-social-links .wp-social-link{display:flex;align-items:center;justify-content:center;margin:0!important;padding:0;font-size:var(--cw-social-icon-size)!important;line-height:1}.cw-social-region .wp-block-social-links .wp-social-link a{display:flex;align-items:center;justify-content:center;font-size:inherit!important;line-height:1}.cw-social-region .wp-block-social-links .wp-social-link svg{display:block;width:1em!important;height:1em!important;flex:0 0 1em}.cw-social-region--header{display:flex;flex:0 0 auto;align-items:center}.cw-social-region--header-before{margin-left:auto}.cw-social-region--header-before + .cw-classic-navigation{margin-left:0}.cw-social-region--header-after{margin-left:var(--cw-space-3)}.cw-social-region--footer{display:flex;align-items:center;justify-content:var(--cw-social-footer-alignment);width:min(calc(100% - 2rem),var(--cw-layout-wide,1120px));margin-inline:auto;padding-block:1.25rem}.cw-social-region--footer .cw-social-widget{align-items:center}.cw-footer-density--compact .cw-social-region--footer{padding-block:1rem}.cw-footer-density--normal .cw-social-region--footer{padding-block:1.25rem}.cw-footer-density--spacious .cw-social-region--footer{padding-block:1.5rem}.cw-footer-density--none .cw-social-region--footer{padding-block:.875rem}.cw-social-region--footer .wp-block-social-links{align-items:center;justify-content:var(--cw-social-footer-alignment)}';
		if ( ! $include_static_hero_fallbacks ) {
			$css .= '.cw-social-region .wp-block-social-links{gap:var(--cw-social-icon-gap)!important}.cw-social-region .wp-block-social-links .wp-social-link{border-radius:9999px}.cw-social-region .wp-block-social-links:not(.is-style-logos-only) .wp-social-link a{padding:.25em}.cw-social-region .wp-block-social-links.is-style-pill-shape .wp-social-link a{padding:.25em .66667em}.cw-social-region .wp-block-social-links.is-style-logos-only .wp-social-link a{padding:0}';
		}
	}
	if ( ! empty( $settings['social_header_color'] ) ) {
		$css .= '.cw-social-region--header .wp-block-social-links:not(.is-style-logos-only) .wp-social-link{background-color:var(--cw-social-header-color)!important;color:#fff!important}.cw-social-region--header .wp-block-social-links:not(.is-style-logos-only) .wp-social-link a{color:#fff!important}.cw-social-region--header .wp-block-social-links.is-style-logos-only .wp-social-link{background-color:transparent!important}.cw-social-region--header .wp-block-social-links.is-style-logos-only .wp-social-link a{color:var(--cw-social-header-color)!important}.cw-social-region--header .wp-block-social-links .wp-social-link svg{fill:currentColor}';
	}
	if ( ! empty( $settings['social_header_hover_color'] ) ) {
		$css .= '.cw-social-region--header .wp-block-social-links:not(.is-style-logos-only) .wp-social-link:hover,.cw-social-region--header .wp-block-social-links:not(.is-style-logos-only) .wp-social-link:focus-within{background-color:var(--cw-social-header-hover-color)!important}.cw-social-region--header .wp-block-social-links.is-style-logos-only .wp-social-link a:hover,.cw-social-region--header .wp-block-social-links.is-style-logos-only .wp-social-link a:focus-visible{color:var(--cw-social-header-hover-color)!important}';
	}
	if ( ! empty( $settings['social_footer_color'] ) ) {
		$css .= '.cw-social-region--footer .wp-block-social-links:not(.is-style-logos-only) .wp-social-link{background-color:var(--cw-social-footer-color)!important;color:#fff!important}.cw-social-region--footer .wp-block-social-links:not(.is-style-logos-only) .wp-social-link a{color:#fff!important}.cw-social-region--footer .wp-block-social-links.is-style-logos-only .wp-social-link{background-color:transparent!important}.cw-social-region--footer .wp-block-social-links.is-style-logos-only .wp-social-link a{color:var(--cw-social-footer-color)!important}.cw-social-region--footer .wp-block-social-links .wp-social-link svg{fill:currentColor}';
	}
	if ( ! empty( $settings['social_footer_hover_color'] ) ) {
		$css .= '.cw-social-region--footer .wp-block-social-links:not(.is-style-logos-only) .wp-social-link:hover,.cw-social-region--footer .wp-block-social-links:not(.is-style-logos-only) .wp-social-link:focus-within{background-color:var(--cw-social-footer-hover-color)!important}.cw-social-region--footer .wp-block-social-links.is-style-logos-only .wp-social-link a:hover,.cw-social-region--footer .wp-block-social-links.is-style-logos-only .wp-social-link a:focus-visible{color:var(--cw-social-footer-hover-color)!important}';
	}
	if ( '1' !== (string) $settings['social_header_mobile_enabled'] ) {
		$compact_breakpoint = absint( $settings['mobile_menu_breakpoint'] );
		$css .= '@media(max-width:' . $compact_breakpoint . 'px){.cw-social-region--header{display:none!important}}';
	}

	if ( $include_frontend_static ) {
		/* Page defaults for Gutenberg blocks; a block / builder declaration always wins. */
		$css .= ':where(.cw-template-main) :where(.wp-block-button__link,.wp-block-search__button){border-radius:var(--cw-button-radius);padding:var(--cw-button-padding-y) var(--cw-button-padding-x);background:var(--cw-button-background);color:var(--cw-button-text)}:where(.cw-template-main) :where(.wp-block-button__link,.wp-block-search__button):hover{background:var(--cw-button-hover)}.cw-button-style--outline :where(.cw-template-main) .wp-block-button__link{background:transparent;border-color:var(--cw-button-background);color:var(--cw-button-background)}';
		$css .= ':where(.cw-template-main) :where(input:not([type="checkbox"]):not([type="radio"]),textarea,select,.wp-block-search__input){border-color:var(--cw-form-border);border-radius:var(--cw-form-radius);background:var(--cw-form-background)}:where(.cw-template-main) :where(input,textarea,select,.wp-block-search__input):focus{border-color:var(--cw-form-focus);box-shadow:0 0 0 2px color-mix(in srgb,var(--cw-form-focus) 22%,transparent)}';
		$css .= '.cw-link-decoration--always :where(.cw-template-main a:not(.wp-block-button__link)){text-decoration:underline}.cw-link-decoration--hover :where(.cw-template-main a:not(.wp-block-button__link)){text-decoration:none}.cw-link-decoration--hover :where(.cw-template-main a:not(.wp-block-button__link)):hover{text-decoration:underline}.cw-link-decoration--never :where(.cw-template-main a:not(.wp-block-button__link)){text-decoration:none}';
	}

	/*
	 * Optional content color contract. Gutenberg keeps its normal explicit-color
	 * escape hatch. Elementor Heading, Button and Icon List defaults are mapped
	 * to real Lumen Global Colors in inc/integrations.php, so Elementor's own
	 * local/global widget selections remain authoritative. Content boxes stay an
	 * explicit class opt-in and native list markers keep a low-specificity
	 * fallback without taking ownership of Elementor widget colors.
	 */
	if ( ! empty( $settings['content_heading_color'] ) ) {
		$css .= 'body :where(.cw-template-main) :where(.entry-content,.wp-block-post-content) :where(h1,h2,h3,h4,h5,h6,.wp-block-heading):not(.has-text-color):not([style*="color"]){color:var(--cw-content-heading-color)}';
		if ( $include_editor_styles ) {
			$css .= ':is(body.editor-styles-wrapper,body .editor-styles-wrapper) :where(h1,h2,h3,h4,h5,h6,.wp-block-heading,.wp-block-post-title):not(.has-text-color):not([style*="color"]){color:var(--cw-content-heading-color)}';
		}
	}

	if ( ! empty( $settings['content_button_background_color'] ) ) {
		$css .= ':where(.cw-template-main) :where(.entry-content,.wp-block-post-content) :where(.wp-block-button__link,.wp-element-button){background-color:var(--cw-content-button-background)}';
		$css .= '.cw-template-main :where(.entry-content,.wp-block-post-content) :is(a.button,a[role="button"]){background-color:var(--cw-content-button-background)}';
		if ( $include_editor_styles ) {
			$css .= ':is(body.editor-styles-wrapper,body .editor-styles-wrapper) :where(.wp-block-button__link,.wp-element-button){background-color:var(--cw-content-button-background)}';
		}
	}
	if ( ! empty( $settings['content_button_hover_color'] ) ) {
		$css .= ':where(.cw-template-main) :where(.entry-content,.wp-block-post-content) :where(.wp-block-button__link,.wp-element-button):hover{background-color:var(--cw-content-button-hover)}';
		$css .= '.cw-template-main :where(.entry-content,.wp-block-post-content) :is(a.button,a[role="button"]):hover{background-color:var(--cw-content-button-hover)}';
	}
	if ( ! empty( $settings['content_button_text_color'] ) ) {
		$css .= ':where(.cw-template-main) :where(.entry-content,.wp-block-post-content) :where(.wp-block-button__link,.wp-element-button){color:var(--cw-content-button-text)}';
		$css .= '.cw-template-main :where(.entry-content,.wp-block-post-content) :is(a.button,a[role="button"]){color:var(--cw-content-button-text)}';
		if ( $include_editor_styles ) {
			$css .= ':is(body.editor-styles-wrapper,body .editor-styles-wrapper) :where(.wp-block-button__link,.wp-element-button){color:var(--cw-content-button-text)}';
		}
	}

	if ( ! empty( $settings['content_box_color'] ) ) {
		$css .= $include_editor_styles
			? ':where(.cw-template-main,.editor-styles-wrapper) :where(.cw-content-box,.wp-block-group.is-style-lumen-card,.cw-demo-card,.cw-demo-stat-card,.cw-demo-note,.cw-demo-callout){border-color:var(--cw-content-box-color)}'
			: ':where(.cw-template-main) :where(.cw-content-box,.wp-block-group.is-style-lumen-card,.cw-demo-card,.cw-demo-stat-card,.cw-demo-note,.cw-demo-callout){border-color:var(--cw-content-box-color)}';
		$css .= $include_editor_styles
			? ':where(.cw-template-main,.editor-styles-wrapper) :where(.cw-content-box--filled){background-color:var(--cw-content-box-color)}'
			: ':where(.cw-template-main) :where(.cw-content-box--filled){background-color:var(--cw-content-box-color)}';
		$css .= 'body:not(.elementor-template-canvas) :is([data-elementor-type="wp-page"],[data-elementor-type="wp-post"]) :where(.cw-content-box){border-color:var(--cw-content-box-color)!important}';
		$css .= 'body:not(.elementor-template-canvas) :is([data-elementor-type="wp-page"],[data-elementor-type="wp-post"]) :where(.cw-content-box--filled){background-color:var(--cw-content-box-color)!important}';
	}

	if ( ! empty( $settings['content_bullet_color'] ) ) {
		$css .= ':where(.cw-template-main) :where(.entry-content,.wp-block-post-content) :where(ul,ol):not(.has-text-color):not([style*="color"])>li::marker{color:var(--cw-content-bullet-color)}';
		$css .= 'body:not(.elementor-template-canvas) :is([data-elementor-type="wp-page"],[data-elementor-type="wp-post"]) :where(ul,ol)>li::marker{color:var(--cw-content-bullet-color)}';
		if ( $include_editor_styles ) {
			$css .= ':is(body.editor-styles-wrapper,body .editor-styles-wrapper) :where(ul,ol):not(.has-text-color):not([style*="color"])>li::marker{color:var(--cw-content-bullet-color)}';
		}
	}

	if ( $include_frontend_static ) {
		/* Screen layout and native sidebar. */
		$css .= '.cw-screen-layout--left,.cw-screen-layout--right{width:min(calc(100% - 2rem),var(--cw-layout-wide));display:grid;grid-template-columns:minmax(0,1fr) var(--cw-sidebar-width);grid-template-areas:"content sidebar";gap:clamp(1.75rem,4vw,4rem);align-items:start}.cw-screen-layout--left{grid-template-columns:var(--cw-sidebar-width) minmax(0,1fr);grid-template-areas:"sidebar content"}.cw-screen-layout__content{grid-area:content;min-width:0}.cw-sidebar{grid-area:sidebar;min-width:0}.cw-sidebar .cw-widget{padding:clamp(1.25rem,2.5vw,1.85rem);border:1px solid color-mix(in srgb,var(--cw-color-border) 72%,transparent);border-radius:min(1rem,var(--cw-radius-md));background:var(--cw-color-surface);box-shadow:0 1px 0 rgb(15 23 42 / 2%)}.cw-sidebar .cw-widget + .cw-widget{margin-top:var(--cw-space-5)}.cw-sidebar :where(ul,ol){padding-left:1.15rem}.cw-sidebar :where(.wp-block-search__inside-wrapper){display:flex;gap:.5rem}.cw-sidebar :where(.wp-block-search__input){min-width:0;width:100%}.cw-sidebar :where(.wp-block-search__button){white-space:nowrap}';

		/* Blog and widgets: avoid over-dense columns on tablets. */
		$css .= '.cw-blog-layout--grid :where(.cw-loop)>.wp-block-post-template{grid-template-columns:repeat(1,minmax(0,1fr))}'
			. '@media(min-width:782px){.cw-blog-layout--grid.cw-blog-columns--1 :where(.cw-loop)>.wp-block-post-template{grid-template-columns:repeat(1,minmax(0,1fr))}.cw-blog-layout--grid.cw-blog-columns--2 :where(.cw-loop)>.wp-block-post-template,.cw-blog-layout--grid.cw-blog-columns--3 :where(.cw-loop)>.wp-block-post-template{grid-template-columns:repeat(2,minmax(0,1fr))}.cw-footer-widget-columns--1 .cw-footer-widgets__inner--columns{grid-template-columns:repeat(1,minmax(0,1fr))}.cw-footer-widget-columns--2 .cw-footer-widgets__inner--columns{grid-template-columns:repeat(2,minmax(0,1fr))}.cw-footer-widget-columns--3 .cw-footer-widgets__inner--columns,.cw-footer-widget-columns--4 .cw-footer-widgets__inner--columns,.cw-footer-widget-columns--5 .cw-footer-widgets__inner--columns{grid-template-columns:repeat(3,minmax(0,1fr))}}'
			. '@media(min-width:1025px){.cw-blog-layout--grid.cw-blog-columns--3 :where(.cw-loop)>.wp-block-post-template{grid-template-columns:repeat(3,minmax(0,1fr))}.cw-footer-widget-columns--4 .cw-footer-widgets__inner--columns{grid-template-columns:repeat(4,minmax(0,1fr))}.cw-footer-widget-columns--5 .cw-footer-widgets__inner--columns{grid-template-columns:repeat(5,minmax(0,1fr))}}';

		/*
		 * Responsive values use the same compact breakpoint selected for the mobile
		 * navigation. This keeps header, content, identity and footer in the same
		 * layout mode on phones and tablets.
		 */
		$css .= '@media(max-width:1024px){'
			. '.cw-content-wrap{width:min(calc(100% - var(--cw-tablet-gutter) - var(--cw-tablet-gutter)),var(--cw-layout-content))}'
			. '.cw-content-wrap.cw-entry--single.cw-single-layout--wide{width:min(calc(100% - var(--cw-tablet-gutter) - var(--cw-tablet-gutter)),var(--cw-layout-wide))}.cw-content-wrap.cw-entry--single.cw-single-layout--full.cw-screen-layout--none{width:100%;max-width:none}'
			. '.cw-site-header__inner{width:min(calc(100% - var(--cw-tablet-gutter) - var(--cw-tablet-gutter)),var(--cw-header-width))}'
			. '.cw-top-bar-widgets__inner{width:min(calc(100% - var(--cw-tablet-gutter) - var(--cw-tablet-gutter)),var(--cw-topbar-width))}'
			. '.cw-footer-widgets__inner--single,.cw-footer-widgets__inner--columns,.cw-site-copyright__inner{width:min(calc(100% - var(--cw-tablet-gutter) - var(--cw-tablet-gutter)),var(--cw-layout-wide))}'
			. '.cw-entry--full:not(.cw-entry--full-canvas){padding-inline:var(--cw-tablet-gutter)}'
			. '}';
		$css .= '@media(max-width:960px){.cw-screen-layout--left,.cw-screen-layout--right{display:block;width:min(calc(100% - var(--cw-tablet-gutter) - var(--cw-tablet-gutter)),var(--cw-layout-content))}.cw-sidebar{margin-top:var(--cw-space-7)}}';
	}
	$css .= '@media(max-width:' . $mobile_breakpoint . 'px){.cw-hide-footer-widgets-on-mobile--1 .cw-footer-widgets{display:none!important}}';
	$css .= '@media(max-width:' . $mobile_breakpoint . 'px){'
		. '.cw-content-wrap{width:min(calc(100% - var(--cw-mobile-gutter) - var(--cw-mobile-gutter)),var(--cw-layout-content))}'
		. '.cw-content-wrap.cw-entry--single.cw-single-layout--wide{width:min(calc(100% - var(--cw-mobile-gutter) - var(--cw-mobile-gutter)),var(--cw-layout-wide))}.cw-content-wrap.cw-entry--single.cw-single-layout--full.cw-screen-layout--none{width:100%;max-width:none}'
		. '.cw-site-header__inner{width:min(calc(100% - var(--cw-mobile-gutter) - var(--cw-mobile-gutter)),var(--cw-header-width));gap:var(--cw-space-4)}'
		. '.cw-site-header__inner .cw-classic-navigation--primary{flex:0 0 auto;width:auto;margin-inline:0}'
		. '.cw-site-header__inner .cw-classic-navigation--primary .cw-classic-navigation__toggle{margin:0}'
		. '.cw-top-bar-widgets__inner{width:min(calc(100% - var(--cw-mobile-gutter) - var(--cw-mobile-gutter)),var(--cw-topbar-width))}'
		. '.cw-footer-widgets__inner--single,.cw-footer-widgets__inner--columns,.cw-site-copyright__inner{width:min(calc(100% - var(--cw-mobile-gutter) - var(--cw-mobile-gutter)),var(--cw-layout-wide))}'
		. '.cw-entry--full:not(.cw-entry--full-canvas){padding-inline:var(--cw-mobile-gutter)}'
		. '.cw-site-header .cw-site-identity>:is(.wp-block-site-logo,.custom-logo-link){width:min(var(--cw-mobile-logo-width),calc(100vw - var(--cw-mobile-gutter) - var(--cw-mobile-gutter) - 4.25rem))!important;max-width:calc(100vw - var(--cw-mobile-gutter) - var(--cw-mobile-gutter) - 4.25rem)!important}'
		. '.cw-site-header .cw-site-identity>:is(.wp-block-site-logo,.custom-logo-link) img{width:100%!important;max-width:100%!important;height:auto}'
		. ':where(.cw-site-header) .wp-block-site-title{font-size:var(--cw-mobile-site-title-size)}'
		. ':where(.cw-site-header) .wp-block-site-tagline{font-size:var(--cw-mobile-site-tagline-size)}'
		. ':where(.cw-site-header) .cw-classic-navigation__menu a{font-size:var(--cw-mobile-navigation-font-size)}'
		. '.cw-site-footer{margin-top:var(--cw-space-7)}'
		/* Do not force a one-column override on very small screens: this setting must honor the administrator choice. */
		. '.cw-footer-widgets__inner--columns{grid-template-columns:repeat(var(--cw-mobile-footer-columns),minmax(0,1fr))}'
		. '}';
	if ( $include_frontend_static ) {
		$css .= '.cw-header-sticky-shadow--none .cw-site-header.cw-header-is-fixed{box-shadow:none}.cw-header-sticky-shadow--subtle .cw-site-header.cw-header-is-fixed{box-shadow:0 10px 28px rgb(15 23 42 / 10%)}.cw-header-sticky-shadow--strong .cw-site-header.cw-header-is-fixed{box-shadow:0 16px 36px rgb(15 23 42 / 18%)}';
		$css .= '.cw-floating-action{width:var(--cw-floating-action-size);height:var(--cw-floating-action-size);background:var(--cw-floating-action-background);color:var(--cw-floating-action-icon-color)}.cw-floating-action:hover,.cw-floating-action:focus-visible{background:var(--cw-floating-action-background);color:var(--cw-floating-action-icon-color);filter:brightness(.92)}.cw-top-bar-widgets .wp-social-link:hover,.cw-top-bar-widgets .wp-social-link:focus-within{background-color:var(--cw-color-accent-strong)}';

		/* Minimal Scandinavian chrome: visual refinement without changing the theme structure or Customizer controls. */
		$css .= '.cw-site-header__inner{gap:clamp(1rem,2.6vw,2.75rem)}.cw-site-identity{gap:.8rem}.cw-site-identity__text{display:grid;gap:.12rem}.cw-site-header .wp-block-site-title{letter-spacing:-.025em}.cw-site-header .wp-block-site-tagline{max-width:32rem;letter-spacing:.01em}.cw-site-header .cw-classic-navigation__menu>li>a{position:relative;display:inline-flex;align-items:center;min-height:2.75rem;padding:.5rem .08rem;letter-spacing:.012em;text-decoration:none}.cw-site-header .cw-classic-navigation__menu>li>a::after{content:"";position:absolute;right:.08rem;bottom:.25rem;left:.08rem;height:2px;background:currentColor;border-radius:999px;opacity:.82;transform:scaleX(0);transform-origin:right center;transition:transform 180ms var(--cw-ease-standard)}.cw-site-header .cw-classic-navigation__menu>li:hover>a::after,.cw-site-header .cw-classic-navigation__menu>li:focus-within>a::after,.cw-site-header .cw-classic-navigation__menu>:is(.current-menu-item,.current_page_item,.current-menu-ancestor,.current-menu-parent,.current_page_ancestor)>a::after{transform:scaleX(1);transform-origin:left center}.cw-site-header .cw-classic-navigation__menu .sub-menu{padding:.4rem 0;border-radius:.7rem;box-shadow:0 .8rem 2.2rem rgb(15 23 42 / 10%)}.cw-site-header .cw-classic-navigation__menu .sub-menu a{min-height:2.25rem;padding:.55rem .7rem;border-radius:0;text-decoration:none}.cw-top-bar-widgets__inner{gap:.5rem clamp(.75rem,2vw,1.4rem);font-size:.78rem;line-height:1.45}.cw-top-bar-widgets .cw-widget{margin:0}.cw-top-bar-widgets .cw-widget>:last-child{margin-bottom:0}.cw-top-bar-widgets .cw-widget__title{margin:0;font-size:.68rem;font-weight:740;letter-spacing:.1em;line-height:1.3;text-transform:uppercase}.cw-site-footer{margin-top:clamp(4.5rem,8vw,7.5rem)}.cw-site-footer--after-hero-only{margin-top:0}.cw-footer-widgets__section + .cw-footer-widgets__section{border-top-color:color-mix(in srgb,var(--cw-footer-text) 12%,transparent)}.cw-footer-widgets__column{min-width:0}.cw-site-footer .cw-widget__title,.cw-site-footer .widget-title{margin:0 0 1rem;font-size:.74rem;font-weight:760;letter-spacing:.12em;line-height:1.4;text-transform:uppercase}.cw-site-footer .cw-widget :where(p,ul,ol){margin-top:0}.cw-site-footer .cw-widget :where(ul,ol){padding-left:0;list-style:none}.cw-site-footer .cw-widget li+li{margin-top:.55rem}.cw-site-footer .cw-widget a{text-decoration:none;text-underline-offset:.22em}.cw-site-footer .cw-widget a:hover,.cw-site-footer .cw-widget a:focus-visible{text-decoration:underline}.cw-site-copyright{border-top:1px solid rgb(255 255 255 / 10%)}.cw-footer-tone--surface .cw-site-copyright{border-top-color:color-mix(in srgb,var(--cw-copyright-text) 13%,transparent)}.cw-site-copyright__inner{padding-block:1.15rem}.cw-site-copyright__text{font-size:.75rem;font-weight:600;letter-spacing:.06em;line-height:1.55}';
			/* The desktop dropdown keeps its compact surface. In compact navigation, remove that surface again after Customizer CSS so mobile branches stay integrated as a list. */
			$css .= '@media(max-width:1024px){.cw-site-header .cw-classic-navigation--primary.cw-compact-navigation-active.is-open .sub-menu{margin:0;padding:0;border:0;border-radius:0;background:transparent;box-shadow:none}body.cw-mobile-menu-style--overlay .cw-site-header .cw-classic-navigation--primary.cw-compact-navigation-active.is-open .sub-menu{padding-inline-left:0}}';
	}

	if ( $include_static_hero_fallbacks ) {
		/* Hero-only pages: the footer keeps its real intrinsic height and the hero
		 * receives the remaining visible viewport. The editor does not load the
		 * conditional frontend hero.css, so it keeps this structural fallback. */
		$css .= 'body.cw-template--hero-only{min-height:100dvh!important;display:flex!important;flex-direction:column!important}body.cw-template--hero-only .cw-site-shell{display:flex!important;flex:1 1 0!important;flex-direction:column!important;min-height:0!important}body.cw-template--hero-only .cw-template-main--hero,body.cw-template--hero-only .cw-entry--hero-only,body.cw-template--hero-only .cw-entry--hero-only .cw-hero-template__hero{display:flex!important;flex:1 1 0!important;flex-direction:column!important;min-height:0!important}body.cw-template--hero-only .cw-entry--hero-only .cw-hero-template__hero>:only-child{flex:1 1 0!important;min-height:100%!important;width:100%!important}body.cw-template--hero-only .cw-entry--hero-only .cw-hero-template__hero>.wp-block-cover{flex:1 1 0!important;min-height:100%!important;height:100%!important;aspect-ratio:auto!important}body.cw-template--hero-only .cw-site-footer{flex:0 0 auto!important;margin-top:0!important}@media (min-width:783px){body.admin-bar.cw-template--hero-only{min-height:calc(100dvh - 32px)!important}}@media (max-width:782px){body.admin-bar.cw-template--hero-only{min-height:calc(100dvh - 46px)!important}}';
		$css .= '/* 1.4.80 · Primer Hero visible pegado a la cabecera con sidebar. */body.cw-template--default-lumen-hero-sidebar-flush .cw-template-main{padding-top:0!important}body.cw-template--default-lumen-hero-sidebar-flush .cw-screen-layout__content>article>.entry-content>p:empty:has(~ :is(.cw-lumen-hero,.cw-lumen-pro-hero)){display:none!important;margin:0!important}';
		$css .= '/* 1.4.81 · Sidebar compacto sin reserva duplicada de cabecera. */@media(min-width:961px){body.cw-template--default-lumen-hero-sidebar.cw-lumen-hero-layout--compact .cw-template-main>.cw-content-wrap.cw-screen-layout:is(.cw-screen-layout--left,.cw-screen-layout--right)>.cw-screen-layout__content>article>.entry-content>:is(.cw-lumen-hero,.cw-lumen-pro-hero){--cw-lumen-hero-safe-top:clamp(2rem,3vw,3rem);--cw-lumen-hero-safe-bottom:clamp(2rem,3vw,3rem);--cw-lumen-pro-hero-safe-top:var(--cw-lumen-hero-safe-top);--cw-lumen-pro-hero-safe-bottom:var(--cw-lumen-hero-safe-bottom);margin-block-start:0!important}body.cw-template--default-lumen-hero-sidebar-flush :is(.cw-template-main,.cw-content-wrap,.cw-screen-layout__content,.cw-screen-layout__content>article,.cw-screen-layout__content>article>.entry-content){margin-block-start:0!important;padding-block-start:0!important;row-gap:0!important}}';
	
		$css .= '/* 1.4.48 · Hero Base común: paridad de alto entre plantillas. */body.cw-template--full-first-lumen-hero .cw-template-main--full-canvas .entry-content>:is(.cw-lumen-hero,.cw-lumen-pro-hero):first-child,body.cw-template--full-first-lumen-hero .cw-entry--full-canvas>.entry-content>:is(.cw-lumen-hero,.cw-lumen-pro-hero):first-child,body.cw-template--default-lumen-hero-only .entry-content>:is(.cw-lumen-hero,.cw-lumen-pro-hero):first-child,body.cw-template--hero-full-width .cw-hero-template__hero>:is(.cw-lumen-hero,.cw-lumen-pro-hero),body.cw-template--lumen-hero-only .cw-hero-template__hero>:is(.cw-lumen-hero,.cw-lumen-pro-hero),body.cw-template--library-hero-only .cw-hero-template__hero>:is(.cw-lumen-hero,.cw-lumen-pro-hero){--cw-lumen-hero-header-height:var(--cw-lumen-pro-header-height,var(--cw-lumen-header-height,84px));--cw-lumen-hero-min-height:calc(100svh - var(--cw-lumen-hero-header-height));--cw-lumen-hero-safe-top:clamp(2.75rem,4.8vh,4rem);--cw-lumen-hero-safe-bottom:clamp(2.35rem,4.4vh,3.75rem);--cw-lumen-pro-hero-header-height:var(--cw-lumen-hero-header-height);--cw-lumen-pro-hero-min-height:var(--cw-lumen-hero-min-height);--cw-lumen-pro-hero-safe-top:var(--cw-lumen-hero-safe-top);--cw-lumen-pro-hero-safe-bottom:var(--cw-lumen-hero-safe-bottom);min-height:var(--cw-lumen-hero-min-height)!important}body.cw-lumen-pro-header-transparent:not(.cw-lumen-pro-header-placement--sticky).cw-template--full-first-lumen-hero .cw-template-main--full-canvas .entry-content>:is(.cw-lumen-hero,.cw-lumen-pro-hero):first-child,body.cw-lumen-pro-header-transparent:not(.cw-lumen-pro-header-placement--sticky).cw-template--full-first-lumen-hero .cw-entry--full-canvas>.entry-content>:is(.cw-lumen-hero,.cw-lumen-pro-hero):first-child,body.cw-lumen-pro-header-transparent:not(.cw-lumen-pro-header-placement--sticky).cw-template--default-lumen-hero-only .entry-content>:is(.cw-lumen-hero,.cw-lumen-pro-hero):first-child,body.cw-lumen-pro-header-transparent:not(.cw-lumen-pro-header-placement--sticky).cw-template--hero-full-width .cw-hero-template__hero>:is(.cw-lumen-hero,.cw-lumen-pro-hero),body.cw-lumen-pro-header-transparent:not(.cw-lumen-pro-header-placement--sticky).cw-template--lumen-hero-only .cw-hero-template__hero>:is(.cw-lumen-hero,.cw-lumen-pro-hero),body.cw-lumen-pro-header-transparent:not(.cw-lumen-pro-header-placement--sticky).cw-template--library-hero-only .cw-hero-template__hero>:is(.cw-lumen-hero,.cw-lumen-pro-hero){--cw-lumen-hero-min-height:100svh;--cw-lumen-hero-safe-top:max(clamp(3.25rem,6vh,4.75rem),calc(var(--cw-lumen-hero-header-height) + .95rem));--cw-lumen-hero-safe-bottom:clamp(2.35rem,4.4vh,3.75rem);--cw-lumen-pro-hero-min-height:var(--cw-lumen-hero-min-height);--cw-lumen-pro-hero-safe-top:var(--cw-lumen-hero-safe-top);--cw-lumen-pro-hero-safe-bottom:var(--cw-lumen-hero-safe-bottom)}body.cw-template--hero-full-width .cw-hero-template__hero:has(>:is(.cw-lumen-hero,.cw-lumen-pro-hero)),body.cw-template--lumen-hero-only .cw-hero-template__hero:has(>:is(.cw-lumen-hero,.cw-lumen-pro-hero)),body.cw-template--library-hero-only .cw-hero-template__hero:has(>:is(.cw-lumen-hero,.cw-lumen-pro-hero)){min-height:0!important;height:auto!important;padding:0!important;margin:0!important;overflow:visible!important}body.cw-template--hero-full-width .cw-hero-template__hero>:is(.cw-lumen-hero,.cw-lumen-pro-hero)>:is(.cw-lumen-hero__inner,.cw-lumen-pro-hero__inner),body.cw-template--lumen-hero-only .cw-hero-template__hero>:is(.cw-lumen-hero,.cw-lumen-pro-hero)>:is(.cw-lumen-hero__inner,.cw-lumen-pro-hero__inner),body.cw-template--library-hero-only .cw-hero-template__hero>:is(.cw-lumen-hero,.cw-lumen-pro-hero)>:is(.cw-lumen-hero__inner,.cw-lumen-pro-hero__inner),body.cw-template--full-first-lumen-hero .cw-template-main--full-canvas .entry-content>:is(.cw-lumen-hero,.cw-lumen-pro-hero):first-child>:is(.cw-lumen-hero__inner,.cw-lumen-pro-hero__inner),body.cw-template--full-first-lumen-hero .cw-entry--full-canvas>.entry-content>:is(.cw-lumen-hero,.cw-lumen-pro-hero):first-child>:is(.cw-lumen-hero__inner,.cw-lumen-pro-hero__inner),body.cw-template--default-lumen-hero-only .entry-content>:is(.cw-lumen-hero,.cw-lumen-pro-hero):first-child>:is(.cw-lumen-hero__inner,.cw-lumen-pro-hero__inner){min-height:var(--cw-lumen-hero-min-height,var(--cw-lumen-pro-hero-min-height))!important;height:auto!important;padding-block-start:var(--cw-lumen-hero-safe-top,var(--cw-lumen-pro-hero-safe-top))!important;padding-block-end:var(--cw-lumen-hero-safe-bottom,var(--cw-lumen-pro-hero-safe-bottom))!important}@media(max-width:782px){body.cw-template--full-first-lumen-hero .cw-template-main--full-canvas .entry-content>:is(.cw-lumen-hero,.cw-lumen-pro-hero):first-child,body.cw-template--full-first-lumen-hero .cw-entry--full-canvas>.entry-content>:is(.cw-lumen-hero,.cw-lumen-pro-hero):first-child,body.cw-template--default-lumen-hero-only .entry-content>:is(.cw-lumen-hero,.cw-lumen-pro-hero):first-child,body.cw-template--hero-full-width .cw-hero-template__hero>:is(.cw-lumen-hero,.cw-lumen-pro-hero),body.cw-template--lumen-hero-only .cw-hero-template__hero>:is(.cw-lumen-hero,.cw-lumen-pro-hero),body.cw-template--library-hero-only .cw-hero-template__hero>:is(.cw-lumen-hero,.cw-lumen-pro-hero){--cw-lumen-hero-safe-top:clamp(2.75rem,9vw,4rem);--cw-lumen-hero-safe-bottom:clamp(2.15rem,7vw,3.25rem);--cw-lumen-pro-hero-safe-top:var(--cw-lumen-hero-safe-top);--cw-lumen-pro-hero-safe-bottom:var(--cw-lumen-hero-safe-bottom)}}';
		$css .= '/* 1.4.49 · Hero Base común: flujo real del shell en canvas hero-only. */body.cw-template--full-lumen-hero-only .cw-site-shell{display:block!important;flex:none!important;min-height:0!important;height:auto!important;overflow:visible!important}body.cw-template--full-lumen-hero-only .cw-template-main--full-canvas,body.cw-template--full-lumen-hero-only .cw-entry--full-canvas,body.cw-template--full-lumen-hero-only .cw-entry--full-canvas>.entry-content{display:block!important;flex:none!important;min-height:0!important;height:auto!important;overflow:visible!important}body.cw-template--full-lumen-hero-only .cw-template-main--full-canvas{padding-block:0!important}body.cw-template--full-lumen-hero-only .cw-entry--full-canvas>.entry-content>:is(.cw-lumen-hero,.cw-lumen-pro-hero):first-child{margin-block-end:0!important}';
		$css .= '/* 1.4.50 · Hero Base común: plantilla por defecto hero-only. */body.cw-template--default-lumen-hero-only .cw-site-shell{display:block!important;flex:none!important;min-height:0!important;height:auto!important;overflow:visible!important}body.cw-template--default-lumen-hero-only .cw-template-main,body.cw-template--default-lumen-hero-only .cw-content-wrap,body.cw-template--default-lumen-hero-only .cw-screen-layout__content,body.cw-template--default-lumen-hero-only .cw-screen-layout__content>article,body.cw-template--default-lumen-hero-only .cw-screen-layout__content>article>.entry-content{display:block!important;flex:none!important;width:100%!important;max-width:none!important;min-height:0!important;height:auto!important;margin:0!important;padding:0!important;overflow:visible!important}body.cw-template--default-lumen-hero-only .cw-screen-layout{display:block!important;grid-template-columns:none!important;width:100%!important;max-width:none!important;gap:0!important}body.cw-template--default-lumen-hero-only .entry-content>:is(.cw-lumen-hero,.cw-lumen-pro-hero):first-child{margin-block:0!important}body.cw-template--default-lumen-hero-only .cw-site-footer,body.cw-template--default-lumen-hero-only .cw-site-footer--after-lumen-hero{margin-top:0!important}';
		$css .= '/* 1.4.52 · Hero Base común: plantilla por defecto con sidebar. */body.cw-template--default-lumen-hero-sidebar .cw-template-main>.cw-content-wrap.cw-screen-layout:is(.cw-screen-layout--left,.cw-screen-layout--right)>.cw-screen-layout__content>article>.entry-content>:is(.cw-lumen-hero,.cw-lumen-pro-hero){--cw-lumen-hero-min-height:0px;--cw-lumen-pro-hero-min-height:0px;--cw-lumen-hero-safe-top:clamp(2.25rem,4.5vw,3.75rem);--cw-lumen-hero-safe-bottom:clamp(2.25rem,4.5vw,3.75rem);--cw-lumen-pro-hero-safe-top:var(--cw-lumen-hero-safe-top);--cw-lumen-pro-hero-safe-bottom:var(--cw-lumen-hero-safe-bottom);min-height:0!important;height:auto!important;margin-top:0!important;margin-bottom:0!important}body.cw-template--default-lumen-hero-sidebar .cw-template-main>.cw-content-wrap.cw-screen-layout:is(.cw-screen-layout--left,.cw-screen-layout--right)>.cw-screen-layout__content>article>.entry-content>:is(.cw-lumen-hero,.cw-lumen-pro-hero)>:is(.cw-lumen-hero__inner,.cw-lumen-pro-hero__inner),body.cw-template--default-lumen-hero-sidebar .cw-template-main>.cw-content-wrap.cw-screen-layout:is(.cw-screen-layout--left,.cw-screen-layout--right)>.cw-screen-layout__content>article>.entry-content>.cw-lumen-pro-hero--split .cw-lumen-pro-pattern__hero-media-text,body.cw-template--default-lumen-hero-sidebar .cw-template-main>.cw-content-wrap.cw-screen-layout:is(.cw-screen-layout--left,.cw-screen-layout--right)>.cw-screen-layout__content>article>.entry-content>.cw-lumen-pro-hero--split .wp-block-media-text__content{min-height:0!important;height:auto!important}body.cw-lumen-pro-header-transparent.cw-template--default-lumen-hero-sidebar .cw-template-main>.cw-content-wrap.cw-screen-layout:is(.cw-screen-layout--left,.cw-screen-layout--right)>.cw-screen-layout__content>article>.entry-content>:is(.cw-lumen-hero,.cw-lumen-pro-hero){--cw-lumen-hero-safe-top:max(clamp(2.75rem,5.5vw,4.25rem),calc(var(--cw-lumen-hero-header-height,var(--cw-lumen-pro-header-height,84px)) + .95rem));--cw-lumen-pro-hero-safe-top:var(--cw-lumen-hero-safe-top)}@media(min-width:961px){body.cw-template--default-lumen-hero-sidebar .cw-template-main>.cw-content-wrap.cw-screen-layout:is(.cw-screen-layout--left,.cw-screen-layout--right)>.cw-screen-layout__content>article>.entry-content>:is(.cw-lumen-hero,.cw-lumen-pro-hero){box-sizing:border-box!important;position:relative!important;left:auto!important;right:auto!important;inset-inline-start:auto!important;inset-inline-end:auto!important;width:100%!important;max-width:100%!important;margin-left:0!important;margin-right:0!important;transform:none!important}body.cw-template--default-lumen-hero-sidebar .cw-template-main>.cw-content-wrap.cw-screen-layout:is(.cw-screen-layout--left,.cw-screen-layout--right)>.cw-screen-layout__content>article>.entry-content>.cw-lumen-pro-hero--split .wp-block-media-text__media{min-height:0!important;height:100%!important}}@media(max-width:960px){body.cw-template--default-lumen-hero-sidebar .cw-template-main>.cw-content-wrap.cw-screen-layout:is(.cw-screen-layout--left,.cw-screen-layout--right)>.cw-screen-layout__content>article>.entry-content>:is(.cw-lumen-hero,.cw-lumen-pro-hero){--cw-lumen-hero-safe-top:clamp(2rem,8vw,3.25rem);--cw-lumen-hero-safe-bottom:clamp(2rem,8vw,3.25rem);--cw-lumen-pro-hero-safe-top:var(--cw-lumen-hero-safe-top);--cw-lumen-pro-hero-safe-bottom:var(--cw-lumen-hero-safe-bottom)}}';
	}

	if ( $include_frontend_static ) {
		$css .= ':where(a,button,input,select,textarea,summary):focus-visible{outline-color:var(--cw-color-focus)}.cw-motion-preference--reduce *,.cw-motion-preference--reduce *::before,.cw-motion-preference--reduce *::after{animation-duration:.01ms;animation-iteration-count:1;scroll-behavior:auto;transition-duration:.01ms}';
	}

	if ( $include_static_hero_fallbacks ) {
		$css .= '/* 1.4.53 · Hero compacto reutilizable con sidebar. */@media(min-width:961px){body.cw-lumen-hero-layout--compact .cw-template-main>.cw-content-wrap.cw-screen-layout:is(.cw-screen-layout--left,.cw-screen-layout--right)>.cw-screen-layout__content>article>.entry-content>:is(.cw-lumen-hero,.cw-lumen-pro-hero){--cw-lumen-hero-compact-media-height:clamp(16rem,28vw,22rem);--cw-lumen-hero-compact-gap:clamp(1.5rem,3vw,2.5rem);--cw-lumen-hero-safe-top:clamp(2rem,3vw,3rem);--cw-lumen-hero-safe-bottom:clamp(2rem,3vw,3rem);--cw-lumen-pro-hero-safe-top:var(--cw-lumen-hero-safe-top);--cw-lumen-pro-hero-safe-bottom:var(--cw-lumen-hero-safe-bottom)}body.cw-lumen-hero-layout--compact .cw-template-main>.cw-content-wrap.cw-screen-layout:is(.cw-screen-layout--left,.cw-screen-layout--right)>.cw-screen-layout__content>article>.entry-content>:is(.cw-lumen-hero,.cw-lumen-pro-hero):is(.cw-lumen-hero--media-copy,.cw-lumen-pro-hero--media-copy)>:is(.cw-lumen-hero__inner--split,.cw-lumen-pro-hero__inner--split),body.cw-lumen-hero-layout--compact .cw-template-main>.cw-content-wrap.cw-screen-layout:is(.cw-screen-layout--left,.cw-screen-layout--right)>.cw-screen-layout__content>article>.entry-content>:is(.cw-lumen-hero,.cw-lumen-pro-hero):is(.cw-lumen-hero--media-copy,.cw-lumen-pro-hero--media-copy) .cw-lumen-pro-pattern__hero-media-text{box-sizing:border-box!important;display:flex!important;flex-direction:column!important;grid-template-columns:1fr!important;align-items:stretch!important;width:100%!important;max-width:100%!important;min-height:0!important;height:auto!important;margin:0!important;gap:var(--cw-lumen-hero-compact-gap)!important}body.cw-lumen-hero-layout--compact .cw-template-main>.cw-content-wrap.cw-screen-layout:is(.cw-screen-layout--left,.cw-screen-layout--right)>.cw-screen-layout__content>article>.entry-content>:is(.cw-lumen-hero,.cw-lumen-pro-hero):is(.cw-lumen-hero--media-copy,.cw-lumen-pro-hero--media-copy):is(.cw-lumen-pro-hero--split,.cw-lumen-hero--split) .wp-block-media-text__media{order:-1!important;width:100%!important;max-width:100%!important;min-height:var(--cw-lumen-hero-compact-media-height)!important;height:var(--cw-lumen-hero-compact-media-height)!important;margin:0!important}body.cw-lumen-hero-layout--compact .cw-template-main>.cw-content-wrap.cw-screen-layout:is(.cw-screen-layout--left,.cw-screen-layout--right)>.cw-screen-layout__content>article>.entry-content>:is(.cw-lumen-hero,.cw-lumen-pro-hero):is(.cw-lumen-hero--media-copy,.cw-lumen-pro-hero--media-copy):is(.cw-lumen-pro-hero--split,.cw-lumen-hero--split) .wp-block-media-text__media img{position:absolute!important;inset:0!important;width:100%!important;height:100%!important;min-height:0!important;object-fit:cover!important}body.cw-lumen-hero-layout--compact .cw-template-main>.cw-content-wrap.cw-screen-layout:is(.cw-screen-layout--left,.cw-screen-layout--right)>.cw-screen-layout__content>article>.entry-content>:is(.cw-lumen-hero,.cw-lumen-pro-hero):is(.cw-lumen-hero--media-copy,.cw-lumen-pro-hero--media-copy) .wp-block-media-text__content,body.cw-lumen-hero-layout--compact .cw-template-main>.cw-content-wrap.cw-screen-layout:is(.cw-screen-layout--left,.cw-screen-layout--right)>.cw-screen-layout__content>article>.entry-content>:is(.cw-lumen-hero,.cw-lumen-pro-hero):is(.cw-lumen-hero--media-copy,.cw-lumen-pro-hero--media-copy)>:is(.cw-lumen-hero__inner--split,.cw-lumen-pro-hero__inner--split)>:is(.cw-lumen-hero__content,.cw-lumen-pro-hero__content){order:0!important;box-sizing:border-box!important;width:100%!important;max-width:100%!important;min-height:0!important;height:auto!important}body.cw-lumen-hero-layout--compact .cw-template-main>.cw-content-wrap.cw-screen-layout:is(.cw-screen-layout--left,.cw-screen-layout--right)>.cw-screen-layout__content>article>.entry-content>:is(.cw-lumen-hero,.cw-lumen-pro-hero):is(.cw-lumen-hero--media-copy,.cw-lumen-pro-hero--media-copy)>:is(.cw-lumen-hero__inner--split,.cw-lumen-pro-hero__inner--split)>:is(.cw-lumen-hero__media,.cw-lumen-pro-hero__media){order:-1!important;box-sizing:border-box!important;width:100%!important;max-width:100%!important;min-height:0!important;height:auto!important;margin:0!important}}';
	
		$css .= '/* 1.4.55 · Reset flex-items en Hero compacto con sidebar. */@media(min-width:961px){body.cw-lumen-hero-layout--compact .cw-template-main>.cw-content-wrap.cw-screen-layout:is(.cw-screen-layout--left,.cw-screen-layout--right)>.cw-screen-layout__content>article>.entry-content>:is(.cw-lumen-hero,.cw-lumen-pro-hero)>:is(.cw-lumen-hero__inner--split,.cw-lumen-pro-hero__inner--split)>:is(.cw-lumen-hero__content,.cw-lumen-pro-hero__content,.cw-lumen-hero__media,.cw-lumen-pro-hero__media){flex:0 0 auto!important;flex-basis:auto!important;flex-grow:0!important;flex-shrink:0!important;align-self:stretch!important}body.cw-lumen-hero-layout--compact .cw-template-main>.cw-content-wrap.cw-screen-layout:is(.cw-screen-layout--left,.cw-screen-layout--right)>.cw-screen-layout__content>article>.entry-content>:is(.cw-lumen-hero,.cw-lumen-pro-hero)>:is(.cw-lumen-hero__inner--split,.cw-lumen-pro-hero__inner--split){justify-content:flex-start!important;overflow:visible!important}}';
		$css .= '/* 1.4.56 · Primer Hero full-width en plantilla predeterminada sin sidebar. */body.cw-template--default-first-lumen-hero-full .cw-template-main>.cw-content-wrap.cw-screen-layout--none>.cw-screen-layout__content>article>.entry-content>:is(.cw-lumen-hero,.cw-lumen-pro-hero):first-child{--cw-lumen-hero-header-height:var(--cw-lumen-pro-header-height,var(--cw-lumen-header-height,84px));--cw-lumen-hero-min-height:calc(100svh - var(--cw-lumen-hero-header-height));--cw-lumen-hero-safe-top:clamp(2.75rem,4.8vh,4rem);--cw-lumen-hero-safe-bottom:clamp(2.35rem,4.4vh,3.75rem);--cw-lumen-pro-hero-header-height:var(--cw-lumen-hero-header-height);--cw-lumen-pro-hero-min-height:var(--cw-lumen-hero-min-height);--cw-lumen-pro-hero-safe-top:var(--cw-lumen-hero-safe-top);--cw-lumen-pro-hero-safe-bottom:var(--cw-lumen-hero-safe-bottom);box-sizing:border-box!important;position:relative!important;left:50%!important;right:50%!important;width:100vw!important;max-width:100vw!important;min-height:var(--cw-lumen-hero-min-height)!important;margin-left:-50vw!important;margin-right:-50vw!important;margin-top:0!important;padding-inline:0!important;border-radius:0!important;overflow:hidden}body.cw-template--default-first-lumen-hero-full .cw-template-main>.cw-content-wrap.cw-screen-layout--none>.cw-screen-layout__content>article>.entry-content>:is(.cw-lumen-hero,.cw-lumen-pro-hero):first-child>:is(.cw-lumen-hero__inner,.cw-lumen-pro-hero__inner){min-height:var(--cw-lumen-hero-min-height,var(--cw-lumen-pro-hero-min-height))!important;height:auto!important;padding-block-start:var(--cw-lumen-hero-safe-top,var(--cw-lumen-pro-hero-safe-top))!important;padding-block-end:var(--cw-lumen-hero-safe-bottom,var(--cw-lumen-pro-hero-safe-bottom))!important}body.cw-lumen-pro-header-transparent:not(.cw-lumen-pro-header-placement--sticky).cw-template--default-first-lumen-hero-full .cw-template-main>.cw-content-wrap.cw-screen-layout--none>.cw-screen-layout__content>article>.entry-content>:is(.cw-lumen-hero,.cw-lumen-pro-hero):first-child{--cw-lumen-hero-min-height:100svh;--cw-lumen-hero-safe-top:max(clamp(3.25rem,6vh,4.75rem),calc(var(--cw-lumen-hero-header-height) + .95rem));--cw-lumen-hero-safe-bottom:clamp(2.35rem,4.4vh,3.75rem);--cw-lumen-pro-hero-min-height:var(--cw-lumen-hero-min-height);--cw-lumen-pro-hero-safe-top:var(--cw-lumen-hero-safe-top);--cw-lumen-pro-hero-safe-bottom:var(--cw-lumen-hero-safe-bottom)}@media(max-width:782px){body.cw-template--default-first-lumen-hero-full .cw-template-main>.cw-content-wrap.cw-screen-layout--none>.cw-screen-layout__content>article>.entry-content>:is(.cw-lumen-hero,.cw-lumen-pro-hero):first-child{--cw-lumen-hero-safe-top:clamp(2.75rem,9vw,4rem);--cw-lumen-hero-safe-bottom:clamp(2.15rem,7vw,3.25rem);--cw-lumen-pro-hero-safe-top:var(--cw-lumen-hero-safe-top);--cw-lumen-pro-hero-safe-bottom:var(--cw-lumen-hero-safe-bottom)}}';
	
	
		$css .= '/* 1.4.83 · Primer Hero visible en Ancho completo. */body.cw-template--full-first-lumen-hero :is(.cw-template-main--full-canvas,.cw-entry--full-canvas,.cw-entry--full-canvas>.entry-content){margin-block-start:0!important;padding-block-start:0!important;row-gap:0!important}body.cw-template--full-first-lumen-hero .cw-entry--full-canvas>.entry-content>p:empty:has(~ :is(.cw-lumen-hero,.cw-lumen-pro-hero)){display:none!important;margin:0!important;padding:0!important}body.cw-template--full-first-lumen-hero .cw-entry--full-canvas>.entry-content>:nth-child(1 of :is(.cw-lumen-hero,.cw-lumen-pro-hero)){--cw-lumen-hero-header-height:var(--cw-lumen-pro-header-height,var(--cw-lumen-header-height,84px));--cw-lumen-hero-min-height:calc(100svh - var(--cw-lumen-hero-header-height));--cw-lumen-hero-safe-top:clamp(2.75rem,4.8vh,4rem);--cw-lumen-hero-safe-bottom:clamp(2.35rem,4.4vh,3.75rem);--cw-lumen-pro-hero-header-height:var(--cw-lumen-hero-header-height);--cw-lumen-pro-hero-min-height:var(--cw-lumen-hero-min-height);--cw-lumen-pro-hero-safe-top:var(--cw-lumen-hero-safe-top);--cw-lumen-pro-hero-safe-bottom:var(--cw-lumen-hero-safe-bottom);min-height:var(--cw-lumen-hero-min-height)!important;margin-block-start:0!important}body.cw-lumen-pro-header-transparent:not(.cw-lumen-pro-header-placement--sticky).cw-template--full-first-lumen-hero .cw-entry--full-canvas>.entry-content>:nth-child(1 of :is(.cw-lumen-hero,.cw-lumen-pro-hero)){--cw-lumen-hero-min-height:100svh;--cw-lumen-hero-safe-top:max(clamp(3.25rem,6vh,4.75rem),calc(var(--cw-lumen-hero-header-height) + .95rem));--cw-lumen-hero-safe-bottom:clamp(2.35rem,4.4vh,3.75rem);--cw-lumen-pro-hero-min-height:var(--cw-lumen-hero-min-height);--cw-lumen-pro-hero-safe-top:var(--cw-lumen-hero-safe-top);--cw-lumen-pro-hero-safe-bottom:var(--cw-lumen-hero-safe-bottom)}body.cw-template--full-first-lumen-hero .cw-entry--full-canvas>.entry-content>:nth-child(1 of :is(.cw-lumen-hero,.cw-lumen-pro-hero))>:is(.cw-lumen-hero__inner,.cw-lumen-pro-hero__inner){min-height:var(--cw-lumen-hero-min-height,var(--cw-lumen-pro-hero-min-height))!important;height:auto!important;padding-block-start:var(--cw-lumen-hero-safe-top,var(--cw-lumen-pro-hero-safe-top))!important;padding-block-end:var(--cw-lumen-hero-safe-bottom,var(--cw-lumen-pro-hero-safe-bottom))!important}@media(max-width:782px){body.cw-template--full-first-lumen-hero .cw-entry--full-canvas>.entry-content>:nth-child(1 of :is(.cw-lumen-hero,.cw-lumen-pro-hero)){--cw-lumen-hero-safe-top:clamp(2.75rem,9vw,4rem);--cw-lumen-hero-safe-bottom:clamp(2.15rem,7vw,3.25rem);--cw-lumen-pro-hero-safe-top:var(--cw-lumen-hero-safe-top);--cw-lumen-pro-hero-safe-bottom:var(--cw-lumen-hero-safe-bottom)}}';
	}


	return $css;
}

/**
 * Adds Customizer defaults directly after the CreceWeb stylesheet.
 *
 * The styles are deliberately layered so explicit Gutenberg block styles
 * keep their escape hatches. Optional Lumen content tokens may selectively
 * override the matching Elementor semantic element inside Lumen templates.
 *
 * @return void
 */
function enqueue_customization_css(): void {
	if ( ! wp_style_is( 'creceweb-lumen', 'enqueued' ) ) {
		return;
	}

	$dependencies = array( 'creceweb-lumen' );
	if ( wp_style_is( 'global-styles', 'registered' ) || wp_style_is( 'global-styles', 'enqueued' ) ) {
		$dependencies[] = 'global-styles';
	}

	wp_register_style( 'creceweb-lumen-customization', false, $dependencies, get_version() );
	wp_enqueue_style( 'creceweb-lumen-customization' );
	wp_add_inline_style( 'creceweb-lumen-customization', get_customization_css( false, false, false ) );
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_customization_css', 99 );
