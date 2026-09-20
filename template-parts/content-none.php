<?php
/**
 * Empty-state template part.
 *
 * @package CreceWebLumen
 */
?>
<section class="cw-empty-state">
	<h1><?php esc_html_e( 'No encontramos contenido', 'creceweb-lumen' ); ?></h1>
	<p><?php esc_html_e( 'Probá con otra búsqueda o volvé al inicio.', 'creceweb-lumen' ); ?></p>
	<?php get_search_form(); ?>
</section>
