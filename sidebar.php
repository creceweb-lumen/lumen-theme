<?php
/**
 * Primary sidebar template.
 *
 * @package CreceWebLumen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! is_active_sidebar( \CreceWeb\Lumen\CRECEWEB_PRIMARY_SIDEBAR ) ) {
	return;
}
?>
<section id="secondary" class="cw-sidebar widget-area" aria-label="<?php esc_attr_e( 'Barra lateral', 'creceweb-lumen' ); ?>">
	<?php dynamic_sidebar( \CreceWeb\Lumen\CRECEWEB_PRIMARY_SIDEBAR ); ?>
</section>
