<?php
/**
 * Single post template.
 *
 * @package CreceWebLumen
 */

$creceweb_settings           = \CreceWeb\Lumen\get_customizations();
$creceweb_single_layout      = sanitize_html_class( (string) ( $creceweb_settings['single_layout'] ?? 'standard' ) );
$creceweb_header_alignment   = sanitize_html_class( (string) ( $creceweb_settings['single_header_alignment'] ?? 'left' ) );
$creceweb_featured_position  = (string) ( $creceweb_settings['single_featured_position'] ?? 'below_header' );
$creceweb_meta_visibility    = (string) ( $creceweb_settings['single_meta_visibility'] ?? 'date_author' );
$creceweb_sidebar_layout     = \CreceWeb\Lumen\get_effective_sidebar_layout( 'single' );

get_header();
?>
<main id="cw-main-content" class="cw-template-main"><div class="cw-content-wrap cw-entry cw-entry--single cw-single-layout--<?php echo esc_attr( $creceweb_single_layout ); ?> cw-single-header-align--<?php echo esc_attr( $creceweb_header_alignment ); ?> cw-screen-layout cw-screen-layout--<?php echo esc_attr( $creceweb_sidebar_layout ); ?>">
	<div class="cw-screen-layout__content">
		<?php while ( have_posts() ) : the_post(); ?>
			<?php
			$creceweb_show_featured_image = has_post_thumbnail() && 'hidden' !== $creceweb_featured_position;
			$creceweb_single_categories  = get_the_category_list( ' <span aria-hidden="true">·</span> ' );
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'cw-single-post' ); ?>>
				<?php if ( $creceweb_show_featured_image && 'above_header' === $creceweb_featured_position ) : ?><div class="entry-thumbnail entry-thumbnail--above-title"><?php the_post_thumbnail( 'large' ); ?></div><?php endif; ?>
				<header class="entry-header">
					<?php if ( $creceweb_single_categories ) : ?><div class="cw-entry__eyebrow"><?php echo wp_kses_post( $creceweb_single_categories ); ?></div><?php endif; ?>
					<h1 class="entry-title wp-block-post-title"><?php the_title(); ?></h1>
					<?php if ( 'hidden' !== $creceweb_meta_visibility ) : ?>
						<div class="entry-meta"><time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time><?php if ( 'date_author' === $creceweb_meta_visibility && get_the_author() ) : ?> · <?php the_author_posts_link(); ?><?php endif; ?></div>
					<?php endif; ?>
					<?php do_action( 'creceweb_lumen_single_after_meta', get_the_ID(), $creceweb_meta_visibility ); ?>
				</header>
				<?php if ( $creceweb_show_featured_image && 'below_header' === $creceweb_featured_position ) : ?><div class="entry-thumbnail entry-thumbnail--below-title"><?php the_post_thumbnail( 'large' ); ?></div><?php endif; ?>
				<div class="entry-content wp-block-post-content"><?php the_content(); ?></div>
				<?php wp_link_pages(); ?>
				<?php
				$creceweb_tag_list = get_the_tag_list( '', esc_html_x( ', ', 'tag separator', 'creceweb-lumen' ) );
				if ( $creceweb_tag_list ) :
					?>
					<footer class="entry-footer">
						<?php /* translators: %s: Post tags list. */ ?>
						<span class="tags-links"><?php printf( esc_html__( 'Etiquetas: %s', 'creceweb-lumen' ), wp_kses_post( $creceweb_tag_list ) ); ?></span>
					</footer>
				<?php endif; ?>
			</article>
			<?php
			the_post_navigation(
				array(
					'prev_text' => '<span class="cw-post-navigation__label">' . esc_html__( 'Entrada anterior', 'creceweb-lumen' ) . '</span><span class="cw-post-navigation__title">%title</span>',
					'next_text' => '<span class="cw-post-navigation__label">' . esc_html__( 'Siguiente entrada', 'creceweb-lumen' ) . '</span><span class="cw-post-navigation__title">%title</span>',
				)
			);
			?>
			<?php if ( comments_open() || get_comments_number() ) { comments_template(); } ?>
		<?php endwhile; ?>
	</div>
	<?php if ( 'none' !== $creceweb_sidebar_layout ) { get_sidebar(); } ?>
</div></main>
<?php get_footer();
