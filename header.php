<?php
/**
 * Header template.
 *
 * @package CreceWebLumen
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php do_action( 'creceweb_before_header' ); ?>
<?php
$cw_identity_settings = \CreceWeb\Lumen\get_customizations();
$cw_header_classes     = apply_filters( 'creceweb_lumen_header_classes', array( 'cw-site-header' ) );
$cw_header_classes     = is_array( $cw_header_classes ) ? $cw_header_classes : array();
$cw_header_classes     = array_filter( array_map( 'sanitize_html_class', $cw_header_classes ) );
if ( ! in_array( 'cw-site-header', $cw_header_classes, true ) ) {
	array_unshift( $cw_header_classes, 'cw-site-header' );
}
?>
<header class="<?php echo esc_attr( implode( ' ', $cw_header_classes ) ); ?>" role="banner">
	<?php
	/* Keep the optional top bar inside the same measured header box. Sticky and
	 * fixed modes can then anchor one unit, and Hero safe-top calculations receive
	 * the combined rendered height without duplicating layout logic. */
	echo \CreceWeb\Lumen\render_top_bar_widgets(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	?>
	<div class="cw-site-header__inner wp-block-group">
		<div class="cw-site-identity wp-block-group">
			<?php
			$custom_logo = get_custom_logo();
			if ( $custom_logo ) {
				echo str_replace( 'custom-logo-link', 'custom-logo-link wp-block-site-logo', $custom_logo ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			?>
			<div class="cw-site-identity__text wp-block-group">
				<?php if ( is_front_page() && is_home() ) : ?>
					<h1 class="site-title wp-block-site-title"<?php echo '1' === (string) ( $cw_identity_settings[ 'hide_site_title' ] ?? '0' ) ? ' hidden aria-hidden="true"' : ''; ?>><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<?php else : ?>
					<p class="site-title wp-block-site-title"<?php echo '1' === (string) ( $cw_identity_settings[ 'hide_site_title' ] ?? '0' ) ? ' hidden aria-hidden="true"' : ''; ?>><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
				<?php endif; ?>
				<?php $description = get_bloginfo( 'description', 'display' ); if ( $description ) : ?>
					<p class="site-description wp-block-site-tagline"<?php echo '1' === (string) ( $cw_identity_settings[ 'hide_site_tagline' ] ?? '0' ) ? ' hidden aria-hidden="true"' : ''; ?>><?php echo esc_html( $description ); ?></p>
				<?php endif; ?>
			</div>
		</div>
		<?php
		$cw_social_header_markup   = \CreceWeb\Lumen\render_header_social_widgets();
		$cw_social_header_position = (string) ( $cw_identity_settings['social_header_position'] ?? 'hidden' );

		if ( '' !== $cw_social_header_markup && 'before_navigation' === $cw_social_header_position ) {
			echo $cw_social_header_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		echo \CreceWeb\Lumen\render_navigation(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( '' !== $cw_social_header_markup && 'after_navigation' === $cw_social_header_position ) {
			echo $cw_social_header_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		?>
	</div>
</header>
<?php do_action( 'creceweb_after_header' ); ?>
<?php $cw_hero_only_page = ! empty( $GLOBALS['creceweb_lumen_hero_only_page'] ); ?>
<div class="cw-site-shell"<?php echo $cw_hero_only_page ? ' style="display:flex;flex:1 1 0;flex-direction:column;min-height:0"' : ''; ?>>
