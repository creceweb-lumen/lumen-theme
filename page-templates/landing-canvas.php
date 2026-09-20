<?php
/**
 * Template Name: Lumen: Landing sin cabecera
 * Template Post Type: page
 *
 * @package CreceWebLumen
 */
get_header( 'canvas' );
?>
<main id="cw-main-content" class="cw-builder-canvas__content">
	<?php // Landing canvas intentionally renders only editor content: no automatic Lumen title, header or footer. ?>
	<?php while ( have_posts() ) : the_post(); ?>
		<?php the_content(); ?>
		<?php wp_link_pages(); ?>
	<?php endwhile; ?>
</main>
<?php get_footer( 'canvas' );
