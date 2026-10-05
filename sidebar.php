<?php
/**
 * Primary sidebar template.
 *
 * @package CreceWebLumen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$creceweb_sidebar_context = \CreceWeb\Lumen\get_current_sidebar_context();
$creceweb_sidebar_id      = \CreceWeb\Lumen\get_sidebar_id( $creceweb_sidebar_context );

if ( ! is_active_sidebar( $creceweb_sidebar_id ) ) {
	return;
}
?>
<section id="secondary" class="cw-sidebar widget-area" aria-label="<?php esc_attr_e( 'Barra lateral', 'creceweb-lumen' ); ?>">
	<?php dynamic_sidebar( $creceweb_sidebar_id ); ?>
</section>
