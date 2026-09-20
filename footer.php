<?php
/**
 * Footer template.
 *
 * The footer is built from native widget areas plus the optional copyright band.
 * Logo, navigation and other custom elements can be added with blocks/widgets,
 * so the theme does not render a duplicate identity/menu region.
 *
 * @package CreceWebLumen
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
</div>
<?php
do_action( 'creceweb_before_footer' );

$cw_footer_classes = array( 'cw-site-footer' );
if ( ! empty( $GLOBALS['creceweb_lumen_hero_only_page'] ) || ! empty( $GLOBALS['creceweb_lumen_full_hero_only_page'] ) || ! empty( $GLOBALS['creceweb_lumen_hero_base_only_page'] ) || ! empty( $GLOBALS['creceweb_lumen_default_hero_only_page'] ) ) {
	$cw_footer_classes[] = 'cw-site-footer--after-hero-only';
}

if ( ! empty( $GLOBALS['creceweb_lumen_full_hero_only_page'] ) || ! empty( $GLOBALS['creceweb_lumen_hero_base_only_page'] ) || ! empty( $GLOBALS['creceweb_lumen_default_hero_only_page'] ) ) {
	$cw_footer_classes[] = 'cw-site-footer--after-lumen-hero';
}
?>
<footer class="<?php echo esc_attr( implode( ' ', $cw_footer_classes ) ); ?>" role="contentinfo">
	<?php
	$cw_footer_settings        = \CreceWeb\Lumen\get_customizations();
	$cw_social_footer_markup   = \CreceWeb\Lumen\render_footer_social_widgets();
	$cw_social_footer_position = (string) ( $cw_footer_settings['social_footer_position'] ?? 'hidden' );

	if ( '' !== $cw_social_footer_markup && 'before_widgets' === $cw_social_footer_position ) {
		echo $cw_social_footer_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	echo \CreceWeb\Lumen\render_footer_widgets(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	if ( '' !== $cw_social_footer_markup && 'after_widgets' === $cw_social_footer_position ) {
		echo $cw_social_footer_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	?>

	<?php $cw_footer_credit = \CreceWeb\Lumen\get_footer_credit_text(); ?>
	<?php if ( '' !== $cw_footer_credit ) : ?>
		<div class="cw-site-copyright">
			<div class="cw-site-copyright__inner">
				<p class="cw-site-copyright__text"><?php echo esc_html( $cw_footer_credit ); ?></p>
			</div>
		</div>
	<?php endif; ?>
</footer>
<?php do_action( 'creceweb_after_footer' ); ?>
<?php wp_footer(); ?>
</body>
</html>
