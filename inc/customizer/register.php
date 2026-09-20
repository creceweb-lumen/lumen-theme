<?php
/**
 * Customizer registration orchestrator.
 *
 * Loads controls once, then delegates registration to focused feature modules.
 *
 * @package CreceWebLumen
 */

namespace CreceWeb\Lumen;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Loads the custom control classes used by the guided interface.
 *
 * @return void
 */
function ensure_customizer_control_classes(): void {
	$required_classes = array(
		__NAMESPACE__ . '\\Textarea_Control',
		__NAMESPACE__ . '\\Range_Control',
		__NAMESPACE__ . '\\Choice_Cards_Control',
		__NAMESPACE__ . '\\Design_Preset_Control',
		__NAMESPACE__ . '\\Logo_Width_Control',
		__NAMESPACE__ . '\\Boolean_Checkbox_Control',
		__NAMESPACE__ . '\\External_Link_Control',
	);

	foreach ( $required_classes as $class_name ) {
		if ( ! class_exists( $class_name ) ) {
			require_once CRECEWEB_LUMEN_DIR . '/inc/customizer-controls.php';
			break;
		}
	}
}

/**
 * Registers CreceWeb Lumen controls in the native WordPress Customizer.
 *
 * Each area is intentionally delegated to a small module so a future change in
 * blog, footer or navigation does not require editing an unrelated section.
 *
 * @param \WP_Customize_Manager $wp_customize Manager.
 * @return void
 */
function register_customizer_controls( \WP_Customize_Manager $wp_customize ): void {
	ensure_customizer_control_classes();
	register_design_panel_and_sections( $wp_customize );

	register_quick_start_controls( $wp_customize );
	register_branding_controls( $wp_customize );
	register_global_style_controls( $wp_customize );
	register_header_navigation_controls( $wp_customize );
	register_screen_layout_controls( $wp_customize );
	register_content_blog_controls( $wp_customize );
	register_footer_controls( $wp_customize );
	register_social_controls( $wp_customize );
	register_responsive_controls( $wp_customize );
	register_floating_action_controls( $wp_customize );
	register_accessibility_controls( $wp_customize );

	register_customizer_section_notes( $wp_customize );
	finalize_customizer_controls( $wp_customize );
}
add_action( 'customize_register', __NAMESPACE__ . '\\register_customizer_controls' );
