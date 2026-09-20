<?php
/**
 * Main posts template.
 *
 * @package CreceWebLumen
 */

$creceweb_sidebar_layout = \CreceWeb\Lumen\get_effective_sidebar_layout( 'archive' );
$creceweb_blog_settings = \CreceWeb\Lumen\get_customizations();
$creceweb_feed_title = trim( (string) ( $creceweb_blog_settings['blog_feed_title'] ?? '' ) );
if ( '' === $creceweb_feed_title ) {
	$creceweb_feed_title = __( 'Últimas publicaciones', 'creceweb-lumen' );
}
$creceweb_feed_description = trim( (string) ( $creceweb_blog_settings['blog_feed_description'] ?? '' ) );
$creceweb_feed_heading_tag = '1' === (string) ( $creceweb_blog_settings['blog_intro_enabled'] ?? '1' ) ? 'h2' : 'h1';
get_header();
?>
<main id="cw-main-content" class="cw-template-main cw-template-main--posts">
	<?php get_template_part( 'template-parts/blog', 'intro' ); ?>
	<section id="cw-latest-posts" class="cw-blog-feed" aria-labelledby="cw-blog-feed-title">
		<div class="cw-content-wrap cw-screen-layout cw-screen-layout--<?php echo esc_attr( $creceweb_sidebar_layout ); ?>">
			<div class="cw-screen-layout__content">
				<header class="cw-blog-feed__header">
					<?php if ( 'h1' === $creceweb_feed_heading_tag ) : ?>
						<h1 id="cw-blog-feed-title"><?php echo esc_html( $creceweb_feed_title ); ?></h1>
					<?php else : ?>
						<h2 id="cw-blog-feed-title"><?php echo esc_html( $creceweb_feed_title ); ?></h2>
					<?php endif; ?>
					<?php if ( '' !== $creceweb_feed_description ) : ?>
						<p><?php echo esc_html( $creceweb_feed_description ); ?></p>
					<?php endif; ?>
				</header>
				<?php get_template_part( 'template-parts/content', 'loop' ); ?>
			</div>
			<?php if ( 'none' !== $creceweb_sidebar_layout ) { get_sidebar(); } ?>
		</div>
	</section>
</main>
<?php get_footer();
