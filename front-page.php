<?php
/**
 * Front page template.
 *
 * When WordPress is configured to show the latest posts on the front page,
 * this template has priority in the hierarchy. In that context it must render
 * the same editorial blog index used by the posts page and archives.
 *
 * @package CreceWebLumen
 */

/*
 * WordPress gives front-page.php priority over a page template assigned to a
 * static front page. Honor Lumen's own templates explicitly so a homepage
 * configured as "Lumen: Hero ancho completo" keeps its zero top spacing and
 * leading hero behavior instead of falling back to the editorial wrapper.
 */
if ( ! is_home() ) {
	$creceweb_front_page_template = get_page_template_slug();
	$creceweb_front_page_templates = array(
		'page-templates/hero-full-width.php',
		'page-templates/full-width.php',
		'page-templates/landing-canvas.php',
	);

	if ( in_array( $creceweb_front_page_template, $creceweb_front_page_templates, true ) ) {
		locate_template( $creceweb_front_page_template, true, true );
		return;
	}
}

$creceweb_sidebar_context = is_home() ? 'archive' : 'page';
$creceweb_sidebar_layout  = \CreceWeb\Lumen\get_effective_sidebar_layout( $creceweb_sidebar_context );

get_header();
?>
<main id="cw-main-content" class="cw-template-main<?php echo is_home() ? ' cw-template-main--posts' : ''; ?>">
	<?php if ( is_home() ) : ?>
		<?php get_template_part( 'template-parts/blog', 'intro' ); ?>
		<section id="cw-latest-posts" class="cw-blog-feed" aria-labelledby="cw-blog-feed-title">
			<div class="cw-content-wrap cw-entry cw-screen-layout cw-screen-layout--<?php echo esc_attr( $creceweb_sidebar_layout ); ?>">
				<div class="cw-screen-layout__content">
					<header class="cw-blog-feed__header">
						<h2 id="cw-blog-feed-title"><?php esc_html_e( 'Últimas publicaciones', 'creceweb-lumen' ); ?></h2>
						<p><?php esc_html_e( 'Lecturas prácticas para construir un sitio claro y útil.', 'creceweb-lumen' ); ?></p>
					</header>
					<?php get_template_part( 'template-parts/content', 'loop' ); ?>
				</div>
				<?php if ( 'none' !== $creceweb_sidebar_layout ) { get_sidebar(); } ?>
			</div>
		</section>
	<?php else : ?>
		<div class="cw-content-wrap cw-entry cw-screen-layout cw-screen-layout--<?php echo esc_attr( $creceweb_sidebar_layout ); ?>">
			<div class="cw-screen-layout__content">
				<?php if ( have_posts() ) : ?>
					<?php while ( have_posts() ) : the_post(); ?>
						<div class="entry-content wp-block-post-content"><?php the_content(); ?></div>
						<?php wp_link_pages(); ?>
					<?php endwhile; ?>
				<?php else : ?>
					<?php get_template_part( 'template-parts/content', 'none' ); ?>
				<?php endif; ?>
			</div>
			<?php if ( 'none' !== $creceweb_sidebar_layout ) { get_sidebar(); } ?>
		</div>
	<?php endif; ?>
</main>
<?php get_footer();
