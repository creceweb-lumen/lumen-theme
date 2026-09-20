<?php
/**
 * Native WordPress Customizer integration for CreceWeb Lumen.
 *
 * @package CreceWebLumen
 */

namespace CreceWeb\Lumen;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns the setting ID used inside the single CreceWeb option array.
 *
 * @param string $key Setting key.
 * @return string
 */
function get_customizer_setting_id( string $key ): string {
	return CUSTOMIZATION_OPTION . '[' . $key . ']';
}

/**
 * Registers one safe setting.
 *
 * @param \WP_Customize_Manager $wp_customize Manager.
 * @param string                 $key Setting key.
 * @param string                 $transport Preview transport.
 * @return string
 */
function add_customizer_setting( \WP_Customize_Manager $wp_customize, string $key, string $transport = 'postMessage' ): string {
	$defaults   = get_customization_defaults();
	$setting_id = get_customizer_setting_id( $key );

	/*
	 * The Customizer resolves the saved value from the option automatically.
	 * Keep this value as a safe fallback only. Reading and sanitizing the full
	 * option while every setting is registered caused an avoidable initialization
	 * risk and made missing defaults harder to detect.
	 */
	$default = $defaults[ $key ] ?? '';

	$wp_customize->add_setting(
		$setting_id,
		array(
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'default'           => $default,
			'sanitize_callback' => static function ( $value ) use ( $key ): string {
				return sanitize_customization_value( $key, $value );
			},
			'transport'         => $transport,
		)
	);

	return $setting_id;
}

/**
 * Adds a select control.
 *
 * @param \WP_Customize_Manager $wp_customize Manager.
 * @param string                 $key Setting key.
 * @param string                 $section Section ID.
 * @param string                 $label Label.
 * @param array<string, string>  $choices Choices.
 * @param int                    $priority Priority.
 * @param string                 $description Description.
 * @param string                 $transport Preview transport.
 * @return void
 */
function add_customizer_select( \WP_Customize_Manager $wp_customize, string $key, string $section, string $label, array $choices, int $priority, string $description = '', string $transport = 'postMessage', $active_callback = null ): void {
	$setting_id = add_customizer_setting( $wp_customize, $key, $transport );
	$args = array(
		'label'       => $label,
		'description' => $description,
		'section'     => $section,
		'type'        => 'select',
		'choices'     => $choices,
		'priority'    => $priority,
	);
	if ( is_callable( $active_callback ) ) {
		$args['active_callback'] = $active_callback;
	}
	$wp_customize->add_control( $setting_id, $args );
}

/**
 * Adds a checkbox control.
 *
 * @param \WP_Customize_Manager $wp_customize Manager.
 * @param string                 $key Setting key.
 * @param string                 $section Section ID.
 * @param string                 $label Label.
 * @param int                    $priority Priority.
 * @param string                 $description Description.
 * @return void
 */
function add_customizer_checkbox( \WP_Customize_Manager $wp_customize, string $key, string $section, string $label, int $priority, string $description = '', $active_callback = null, string $transport = 'refresh' ): void {
	$setting_id = add_customizer_setting( $wp_customize, $key, $transport );
	$args = array(
		'label'       => $label,
		'description' => $description,
		'section'     => $section,
		'priority'    => $priority,
	);
	if ( is_callable( $active_callback ) ) {
		$args['active_callback'] = $active_callback;
	}
	$wp_customize->add_control( new Boolean_Checkbox_Control( $wp_customize, $setting_id, $args ) );
}

/**
 * Adds a native text or textarea control for short guided values.
 *
 * @param \WP_Customize_Manager $wp_customize Manager.
 * @param string                 $key Setting key.
 * @param string                 $section Section ID.
 * @param string                 $label Label.
 * @param int                    $priority Priority.
 * @param string                 $description Description.
 * @param string                 $type Native input type.
 * @param array<string,mixed>    $input_attrs Input attributes.
 * @param mixed                  $active_callback Optional active callback.
 * @param string                 $transport Preview transport.
 * @return void
 */
function add_customizer_text( \WP_Customize_Manager $wp_customize, string $key, string $section, string $label, int $priority, string $description = '', string $type = 'text', array $input_attrs = array(), $active_callback = null, string $transport = 'refresh' ): void {
	$setting_id = add_customizer_setting( $wp_customize, $key, $transport );
	$args = array(
		'label'       => $label,
		'description' => $description,
		'section'     => $section,
		'priority'    => $priority,
		'type'        => $type,
		'input_attrs' => $input_attrs,
	);
	if ( is_callable( $active_callback ) ) {
		$args['active_callback'] = $active_callback;
	}

	if ( 'textarea' === $type ) {
		$wp_customize->add_control( new Textarea_Control( $wp_customize, $setting_id, $args ) );
		return;
	}

	$wp_customize->add_control( $setting_id, $args );
}

/**
 * Adds an image selector backed by the native WordPress media library.
 *
 * @param \WP_Customize_Manager $wp_customize Manager.
 * @param string                 $key Setting key.
 * @param string                 $section Section ID.
 * @param string                 $label Label.
 * @param int                    $priority Priority.
 * @param string                 $description Description.
 * @param mixed                  $active_callback Optional active callback.
 * @return void
 */
function add_customizer_image( \WP_Customize_Manager $wp_customize, string $key, string $section, string $label, int $priority, string $description = '', $active_callback = null ): void {
	$setting_id = add_customizer_setting( $wp_customize, $key, 'refresh' );
	$args = array(
		'label'       => $label,
		'description' => $description,
		'section'     => $section,
		'priority'    => $priority,
		'mime_type'   => 'image',
	);
	if ( is_callable( $active_callback ) ) {
		$args['active_callback'] = $active_callback;
	}
	$wp_customize->add_control( new \WP_Customize_Media_Control( $wp_customize, $setting_id, $args ) );
}

/**
 * Adds a color picker.
 *
 * @param \WP_Customize_Manager $wp_customize Manager.
 * @param string                 $key Setting key.
 * @param string                 $section Section ID.
 * @param string                 $label Label.
 * @param int                    $priority Priority.
 * @param mixed                  $active_callback Optional active callback.
 * @param string                 $description Optional help text.
 * @param string                 $transport Preview transport.
 * @return void
 */
function add_customizer_color( \WP_Customize_Manager $wp_customize, string $key, string $section, string $label, int $priority, $active_callback = null, string $description = '', string $transport = 'postMessage' ): void {
	$setting_id = add_customizer_setting( $wp_customize, $key, $transport );
	$args = array(
		'label'       => $label,
		'description' => $description,
		'section'     => $section,
		'priority'    => $priority,
	);

	if ( is_callable( $active_callback ) ) {
		$args['active_callback'] = $active_callback;
	}

	$wp_customize->add_control(
		new \WP_Customize_Color_Control(
			$wp_customize,
			$setting_id,
			$args
		)
	);
}

/**
 * Only exposes the top-bar custom colors when the custom tone is selected.
 *
 * @param \WP_Customize_Control $control Current Customizer control.
 * @return bool
 */
function is_top_bar_custom_tone_active( \WP_Customize_Control $control ): bool {
	$setting = $control->manager->get_setting( get_customizer_setting_id( 'top_bar_tone' ) );
	$enabled = $control->manager->get_setting( get_customizer_setting_id( 'top_bar_enabled' ) );

	return $setting && $enabled && '1' === (string) $enabled->value() && 'custom' === (string) $setting->value();
}

/**
 * Adds a safe range slider.
 *
 * @param \WP_Customize_Manager $wp_customize Manager.
 * @param string                 $key Setting key.
 * @param string                 $section Section ID.
 * @param string                 $label Label.
 * @param int                    $priority Priority.
 * @param int|float              $min Minimum.
 * @param int|float              $max Maximum.
 * @param int|float              $step Step.
 * @param string                 $unit Visible unit.
 * @param string                 $description Description.
 * @return void
 */
function add_customizer_range( \WP_Customize_Manager $wp_customize, string $key, string $section, string $label, int $priority, $min, $max, $step = 1, string $unit = 'px', string $description = '', $active_callback = null ): void {
	$setting_id = add_customizer_setting( $wp_customize, $key );
	$args = array(
		'label'       => $label,
		'description' => $description,
		'section'     => $section,
		'priority'    => $priority,
		'unit'        => $unit,
		'input_attrs' => array( 'min' => $min, 'max' => $max, 'step' => $step ),
	);
	if ( is_callable( $active_callback ) ) {
		$args['active_callback'] = $active_callback;
	}
	$wp_customize->add_control( new Range_Control( $wp_customize, $setting_id, $args ) );
}


/**
 * Adds visual card choices for settings that benefit from a compact layout preview.
 *
 * @param \WP_Customize_Manager $wp_customize Manager.
 * @param string                 $key Setting key.
 * @param string                 $section Section ID.
 * @param string                 $label Label.
 * @param array<string, mixed>   $choices Choices.
 * @param int                    $priority Priority.
 * @param string                 $preview_type Preview family.
 * @param string                 $description Description.
 * @param mixed                  $active_callback Optional active callback.
 * @return void
 */
function add_customizer_choice_cards( \WP_Customize_Manager $wp_customize, string $key, string $section, string $label, array $choices, int $priority, string $preview_type, string $description = '', $active_callback = null ): void {
	$setting_id = add_customizer_setting( $wp_customize, $key );
	$args = array(
		'label'        => $label,
		'description'  => $description,
		'section'      => $section,
		'choices'      => $choices,
		'priority'     => $priority,
		'preview_type' => $preview_type,
	);
	if ( is_callable( $active_callback ) ) {
		$args['active_callback'] = $active_callback;
	}
	$wp_customize->add_control( new Choice_Cards_Control( $wp_customize, $setting_id, $args ) );
}

/**
 * Adds a static grouping note to a Customizer section.
 *
 * @param \WP_Customize_Manager $wp_customize Manager.
 * @param string                 $id Control ID.
 * @param string                 $section Section ID.
 * @param string                 $label Label.
 * @param string                 $description Description.
 * @param int                    $priority Priority.
 * @return void
 */
function add_customizer_note( \WP_Customize_Manager $wp_customize, string $id, string $section, string $label, string $description, int $priority, string $note_style = 'standard' ): void {
	$wp_customize->add_setting(
		$id,
		array( 'capability' => 'edit_theme_options', 'sanitize_callback' => '__return_empty_string' )
	);
	$wp_customize->add_control(
		new Section_Note_Control(
			$wp_customize,
			$id,
			array(
				'section'    => $section,
				'label'      => $label,
				'description'=> $description,
				'priority'   => $priority,
				'note_style' => $note_style,
			)
		)
	);
}

/**
 * Returns a setting value used by active callbacks.
 *
 * @param \WP_Customize_Manager $manager Manager.
 * @param string                 $key Setting key.
 * @return string
 */
function get_customizer_value( \WP_Customize_Manager $manager, string $key ): string {
	$setting = $manager->get_setting( get_customizer_setting_id( $key ) );
	return $setting ? (string) $setting->value() : '';
}

/**
 * Shows top-bar details only when the area is enabled.
 *
 * @param \WP_Customize_Control $control Control.
 * @return bool
 */
function is_top_bar_enabled_active( \WP_Customize_Control $control ): bool {
	return '1' === get_customizer_value( $control->manager, 'top_bar_enabled' );
}

/**
 * Shows grid-specific blog controls only for the grid layout.
 *
 * @param \WP_Customize_Control $control Control.
 * @return bool
 */
function is_blog_grid_active( \WP_Customize_Control $control ): bool {
	return 'grid' === get_customizer_value( $control->manager, 'blog_layout' );
}

/**
 * Shows list-only presentation options when the archive uses a list.
 *
 * @param \WP_Customize_Control $control Control.
 * @return bool
 */
function is_blog_list_active( \WP_Customize_Control $control ): bool {
	return 'list' === get_customizer_value( $control->manager, 'blog_layout' );
}

/**
 * Shows image-ratio options only when archive cards display a featured image.
 *
 * @param \WP_Customize_Control $control Control.
 * @return bool
 */
function is_blog_featured_image_active( \WP_Customize_Control $control ): bool {
	return '1' === get_customizer_value( $control->manager, 'blog_show_featured_image' );
}

/**
 * Shows the read-more label only when archive cards display the link.
 *
 * @param \WP_Customize_Control $control Control.
 * @return bool
 */
function is_blog_read_more_active( \WP_Customize_Control $control ): bool {
	return '1' === get_customizer_value( $control->manager, 'blog_show_read_more' );
}

/**
 * Shows guided blog-header controls only while the editorial header is active.
 *
 * @param \WP_Customize_Control $control Current control.
 * @return bool
 */
function is_blog_intro_active( \WP_Customize_Control $control ): bool {
	return '1' === get_customizer_value( $control->manager, 'blog_intro_enabled' );
}

/**
 * Shows call-to-action details only when the blog header displays a button.
 *
 * @param \WP_Customize_Control $control Current control.
 * @return bool
 */
function is_blog_intro_button_active( \WP_Customize_Control $control ): bool {
	return is_blog_intro_active( $control ) && '1' === get_customizer_value( $control->manager, 'blog_intro_show_button' );
}

/**
 * Shows the media selector only when the user chooses an image visual.
 *
 * @param \WP_Customize_Control $control Current control.
 * @return bool
 */
function is_blog_intro_image_active( \WP_Customize_Control $control ): bool {
	return is_blog_intro_active( $control ) && 'image' === get_customizer_value( $control->manager, 'blog_intro_visual_type' );
}

/**
 * Shows sticky-specific controls only when sticky header is selected.
 *
 * @param \WP_Customize_Control $control Control.
 * @return bool
 */
function is_sticky_header_active( \WP_Customize_Control $control ): bool {
	return in_array( get_customizer_value( $control->manager, 'header_behavior' ), array( 'sticky', 'fixed' ), true );
}

/**
 * Shows the compact logo width only when a separate mobile/tablet width is selected.
 *
 * @param \WP_Customize_Control $control Control.
 * @return bool
 */
function is_mobile_logo_custom_width_active( \WP_Customize_Control $control ): bool {
	return 'custom' === get_customizer_value( $control->manager, 'mobile_logo_width_mode' );
}

/**
 * Shows visual floating-button controls whenever an action is selected.
 *
 * @param \WP_Customize_Control $control Current Customizer control.
 * @return bool
 */
function is_floating_action_active( \WP_Customize_Control $control ): bool {
	return 'none' !== get_customizer_value( $control->manager, 'floating_action' );
}

/**
 * Checks whether the user enabled detailed controls.
 *
 * @param \WP_Customize_Control $control Control.
 * @return bool
 */
function is_detailed_control_active( \WP_Customize_Control $control ): bool {
	return '1' === get_customizer_value( $control->manager, 'show_advanced_controls' );
}

/**
 * Applies the detailed-mode condition without replacing existing dependencies.
 *
 * @param \WP_Customize_Manager $wp_customize Manager.
 * @param string[]                $keys Customization keys.
 * @return void
 */
function mark_customizer_controls_as_detailed( \WP_Customize_Manager $wp_customize, array $keys ): void {
	foreach ( $keys as $key ) {
		$control = $wp_customize->get_control( get_customizer_setting_id( $key ) );
		if ( ! $control ) {
			continue;
		}

		$existing_callback = $control->active_callback;
		$control->active_callback = static function ( \WP_Customize_Control $current_control ) use ( $existing_callback ): bool {
			if ( ! is_detailed_control_active( $current_control ) ) {
				return false;
			}
			return ! is_callable( $existing_callback ) || (bool) call_user_func( $existing_callback, $current_control );
		};
	}
}

/**
 * Normalizes control priorities after sections are merged.
 *
 * @param \WP_Customize_Manager          $wp_customize Manager.
 * @param array<string, array<int,string>> $sections Ordered control IDs by section.
 * @return void
 */
function order_customizer_controls( \WP_Customize_Manager $wp_customize, array $sections ): void {
	foreach ( $sections as $section_id => $control_ids ) {
		$priority = 10;
		foreach ( $control_ids as $control_id ) {
			$control = $wp_customize->get_control( $control_id );
			if ( $control && $section_id === $control->section ) {
				$control->priority = $priority;
				$priority += 10;
			}
		}
	}
}
