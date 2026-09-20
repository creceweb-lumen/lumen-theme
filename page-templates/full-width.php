<?php
/**
 * Template Name: Lumen: Ancho completo
 * Template Post Type: page
 *
 * A full-width canvas that keeps Lumen's header and footer. Gutenberg blocks
 * aligned as "Ancho completo" and Elementor root layouts can reach the
 * viewport edge; regular blocks stay centered inside the readable wide column.
 *
 * @package CreceWebLumen
 */

$creceweb_template_page_id = (int) get_queried_object_id();
$creceweb_template_first_lumen_hero = false;
$creceweb_template_lumen_hero_only  = false;

if ( $creceweb_template_page_id > 0 && ! comments_open( $creceweb_template_page_id ) && 0 === (int) get_comments_number( $creceweb_template_page_id ) ) {
	$creceweb_template_content    = (string) get_post_field( 'post_content', $creceweb_template_page_id );
	$creceweb_template_blocks     = has_blocks( $creceweb_template_content ) ? parse_blocks( $creceweb_template_content ) : array();
	$creceweb_template_lumen_hero = \CreceWeb\Lumen\get_leading_lumen_hero_block( $creceweb_template_blocks );

	if ( $creceweb_template_lumen_hero ) {
		$creceweb_template_first_lumen_hero = true;
		$creceweb_template_remaining       = \CreceWeb\Lumen\get_visible_blocks( array_slice( $creceweb_template_blocks, $creceweb_template_lumen_hero['index'] + 1 ) );
		$creceweb_template_lumen_hero_only  = empty( $creceweb_template_remaining );
	}
}

add_filter(
	'body_class',
	static function ( $classes ) use ( $creceweb_template_first_lumen_hero, $creceweb_template_lumen_hero_only ) {
		if ( $creceweb_template_first_lumen_hero ) {
			$classes[] = 'cw-template--full-first-lumen-hero';
		}

		if ( $creceweb_template_lumen_hero_only ) {
			$classes[] = 'cw-template--full-lumen-hero-only';
		}

		return array_values( array_unique( $classes ) );
	}
);

$GLOBALS['creceweb_lumen_full_first_hero_page'] = $creceweb_template_first_lumen_hero;
$GLOBALS['creceweb_lumen_full_hero_only_page']  = $creceweb_template_lumen_hero_only;

get_header();
?>
<main id="cw-main-content" class="cw-template-main cw-template-main--full cw-template-main--full-canvas">
	<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'cw-entry cw-entry--full cw-entry--full-canvas' ); ?>>
			<?php // This canvas template intentionally omits Lumen's automatic title. Add a heading block in the selected editor when needed. ?>
			<div class="entry-content wp-block-post-content">
				<?php the_content(); ?>
				<?php wp_link_pages(); ?>
			</div>
			<?php if ( comments_open() || get_comments_number() ) { comments_template(); } ?>
		</article>
	<?php endwhile; ?>
</main>
<?php get_footer();
