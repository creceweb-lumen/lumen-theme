<?php
/**
 * Standard page template.
 *
 * @package CreceWebLumen
 */

$creceweb_sidebar_layout                              = \CreceWeb\Lumen\get_effective_sidebar_layout( 'page' );
$creceweb_default_transparent_header_supported           = 'none' === $creceweb_sidebar_layout;
$GLOBALS['creceweb_lumen_transparent_header_supported'] = $creceweb_default_transparent_header_supported;
$creceweb_default_lumen_hero_only                         = false;
$creceweb_default_lumen_hero_with_sidebar                 = false;
$creceweb_default_lumen_hero_full_width                   = false;
$creceweb_default_lumen_hero_sidebar_flush                = false;
$creceweb_default_page_id                                 = (int) get_queried_object_id();
$creceweb_default_blocks                                  = array();
$creceweb_default_lumen_hero                              = null;

if ( $creceweb_default_page_id > 0 ) {
	$creceweb_default_content    = (string) get_post_field( 'post_content', $creceweb_default_page_id );
	$creceweb_default_blocks     = has_blocks( $creceweb_default_content ) ? parse_blocks( $creceweb_default_content ) : array();
	$creceweb_default_lumen_hero = \CreceWeb\Lumen\get_leading_lumen_hero_block( $creceweb_default_blocks );
}

if ( $creceweb_default_lumen_hero && 'none' !== $creceweb_sidebar_layout ) {
	$creceweb_default_lumen_hero_with_sidebar = true;

	if (
		! \CreceWeb\Lumen\is_entry_title_visible( 'page', $creceweb_default_page_id )
		&& ! has_post_thumbnail( $creceweb_default_page_id )
	) {
		$creceweb_default_lumen_hero_sidebar_flush = true;
	}
}

if ( $creceweb_default_lumen_hero && 'none' === $creceweb_sidebar_layout ) {
	$creceweb_default_lumen_hero_full_width = true;
}

if (
	$creceweb_default_lumen_hero
	&& 'none' === $creceweb_sidebar_layout
	&& ! \CreceWeb\Lumen\is_entry_title_visible( 'page', $creceweb_default_page_id )
	&& ! has_post_thumbnail( $creceweb_default_page_id )
	&& ! comments_open( $creceweb_default_page_id )
	&& 0 === (int) get_comments_number( $creceweb_default_page_id )
) {
	$creceweb_default_remaining       = \CreceWeb\Lumen\get_visible_blocks( array_slice( $creceweb_default_blocks, $creceweb_default_lumen_hero['index'] + 1 ) );
	$creceweb_default_lumen_hero_only = empty( $creceweb_default_remaining );
}

if ( $creceweb_default_lumen_hero_only || $creceweb_default_lumen_hero_with_sidebar || $creceweb_default_lumen_hero_full_width || ! $creceweb_default_transparent_header_supported ) {
	add_filter(
		'body_class',
		static function ( $classes ) use ( $creceweb_default_lumen_hero_only, $creceweb_default_lumen_hero_with_sidebar, $creceweb_default_lumen_hero_full_width, $creceweb_default_lumen_hero_sidebar_flush, $creceweb_default_transparent_header_supported ) {
			if ( ! $creceweb_default_transparent_header_supported ) {
				$classes[] = 'cw-lumen-header-transparency--unsupported';
			}
			if ( $creceweb_default_lumen_hero_only ) {
				$classes[] = 'cw-template--default-lumen-hero-only';
			}

			if ( $creceweb_default_lumen_hero_with_sidebar ) {
				$classes[] = 'cw-template--default-lumen-hero-sidebar';
				$classes[] = 'cw-lumen-hero-layout--compact';

				if ( $creceweb_default_lumen_hero_sidebar_flush ) {
					$classes[] = 'cw-template--default-lumen-hero-sidebar-flush';
				}
			}

			if ( $creceweb_default_lumen_hero_full_width ) {
				$classes[] = 'cw-template--default-first-lumen-hero-full';
				$classes[] = 'cw-lumen-hero-layout--full';
			}

			return array_values( array_unique( $classes ) );
		}
	);
}

$GLOBALS['creceweb_lumen_default_hero_only_page'] = $creceweb_default_lumen_hero_only;

get_header();
?>
<main id="cw-main-content" class="cw-template-main"><div class="cw-content-wrap cw-entry cw-screen-layout cw-screen-layout--<?php echo esc_attr( $creceweb_sidebar_layout ); ?>">
	<div class="cw-screen-layout__content">
		<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php if ( \CreceWeb\Lumen\is_entry_title_visible( 'page', get_the_ID() ) ) : ?>
					<h1 class="entry-title wp-block-post-title"><?php the_title(); ?></h1>
				<?php endif; ?>
				<?php if ( has_post_thumbnail() ) : ?><div class="entry-thumbnail"><?php the_post_thumbnail( 'large' ); ?></div><?php endif; ?>
				<div class="entry-content wp-block-post-content"><?php the_content(); ?></div>
				<?php wp_link_pages(); ?>
			</article>
			<?php if ( comments_open() || get_comments_number() ) { comments_template(); } ?>
		<?php endwhile; ?>
	</div>
	<?php if ( 'none' !== $creceweb_sidebar_layout ) { get_sidebar(); } ?>
</div></main>
<?php get_footer();
