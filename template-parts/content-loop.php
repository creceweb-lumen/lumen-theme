<?php
/**
 * Reusable posts loop for the blog home, front-page posts view, archives and search.
 *
 * @package CreceWebLumen
 */

$creceweb_blog_settings = \CreceWeb\Lumen\get_customizations();
$creceweb_blog_layout   = 'grid' === (string) ( $creceweb_blog_settings['blog_layout'] ?? 'list' ) ? 'grid' : 'list';
// Card composition is deliberately a list-only decision. Grid cards always
// use the vertical composition to keep every column visually consistent.
$creceweb_card_layout   = 'list' === $creceweb_blog_layout
	? sanitize_html_class( (string) ( $creceweb_blog_settings['blog_card_layout'] ?? 'vertical' ) )
	: 'vertical';
$creceweb_allowed_card_layouts = array( 'vertical', 'media_left', 'compact' );
if ( ! in_array( $creceweb_card_layout, $creceweb_allowed_card_layouts, true ) ) {
	$creceweb_card_layout = 'vertical';
}
?>
<?php if ( have_posts() ) : ?>
	<div class="cw-loop cw-loop--<?php echo esc_attr( $creceweb_blog_layout ); ?> cw-loop--cards-<?php echo esc_attr( str_replace( '_', '-', $creceweb_card_layout ) ); ?>">
		<div class="wp-block-post-template">
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'template-parts/content', 'card' ); ?>
			<?php endwhile; ?>
		</div>
	</div>
	<?php
	the_posts_pagination(
		array(
			'mid_size'  => 0,
			'end_size'  => 1,
			'prev_text' => '<span class="screen-reader-text">' . esc_html__( 'Página anterior', 'creceweb-lumen' ) . '</span><span aria-hidden="true">' . esc_html__( 'Ant.', 'creceweb-lumen' ) . '</span>',
			'next_text' => '<span class="screen-reader-text">' . esc_html__( 'Página siguiente', 'creceweb-lumen' ) . '</span><span aria-hidden="true">' . esc_html__( 'Sig.', 'creceweb-lumen' ) . '</span>',
		)
	);
	?>
<?php else : ?>
	<?php get_template_part( 'template-parts/content', 'none' ); ?>
<?php endif; ?>
