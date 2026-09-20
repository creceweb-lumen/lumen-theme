<?php
/**
 * 404 template.
 *
 * @package CreceWebLumen
 */
get_header();
?>
<main id="cw-main-content" class="cw-template-main"><div class="cw-content-wrap cw-empty-state"><h1><?php esc_html_e( 'Página no encontrada', 'creceweb-lumen' ); ?></h1><p><?php esc_html_e( 'La página que buscás no existe o fue movida.', 'creceweb-lumen' ); ?></p><p><a class="wp-block-button__link" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Volver al inicio', 'creceweb-lumen' ); ?></a></p><?php get_search_form(); ?></div></main>
<?php get_footer();
