<?php
/**
 * Template Name: Lumen: Entrada Hero ancho completo
 * Template Post Type: post
 *
 * Renders a leading full-width Gutenberg block or the first Elementor section
 * above the normal single-post editorial layout. The title, metadata, featured
 * image, content, tags, navigation, comments and configured single sidebar stay
 * owned by the regular Lumen single-post presentation.
 *
 * @package CreceWebLumen
 */

$creceweb_settings          = \CreceWeb\Lumen\get_customizations();
$creceweb_single_layout     = sanitize_html_class( (string) ( $creceweb_settings['single_layout'] ?? 'standard' ) );
$creceweb_header_alignment  = sanitize_html_class( (string) ( $creceweb_settings['single_header_alignment'] ?? 'left' ) );
$creceweb_featured_position = (string) ( $creceweb_settings['single_featured_position'] ?? 'below_header' );
$creceweb_meta_visibility   = (string) ( $creceweb_settings['single_meta_visibility'] ?? 'date_author' );
$creceweb_sidebar_layout    = \CreceWeb\Lumen\get_effective_sidebar_layout( 'single' );
$creceweb_template_post_id  = (int) get_queried_object_id();
$creceweb_template_has_hero = false;

/*
 * Gutenberg can be resolved before the header from stored post_content. For an
 * Elementor document, selecting this template deliberately makes the first
 * rendered top-level section the Hero, matching the existing page template.
 * Plain posts without either condition fall back to the normal single layout.
 */
if ( $creceweb_template_post_id > 0 ) {
	$creceweb_template_content = (string) get_post_field( 'post_content', $creceweb_template_post_id );
	$creceweb_template_blocks  = has_blocks( $creceweb_template_content ) ? parse_blocks( $creceweb_template_content ) : array();
	$creceweb_template_has_hero = null !== \CreceWeb\Lumen\get_leading_full_width_block( $creceweb_template_blocks );

	if ( ! $creceweb_template_has_hero ) {
		$creceweb_template_has_hero = 'builder' === (string) get_post_meta( $creceweb_template_post_id, '_elementor_edit_mode', true );
	}
}

if ( $creceweb_template_has_hero ) {
	add_filter(
		'body_class',
		static function ( $classes ) {
			$classes[] = 'cw-template--hero-full-width';
			$classes[] = 'cw-template--single-hero-full-width';
			return array_values( array_unique( $classes ) );
		}
	);
}

get_header();
?>
<main id="cw-main-content" class="cw-template-main<?php echo $creceweb_template_has_hero ? ' cw-template-main--hero' : ''; ?>">
	<?php while ( have_posts() ) : the_post(); ?>
		<?php
		$creceweb_raw_content       = (string) get_the_content();
		$creceweb_blocks            = has_blocks( $creceweb_raw_content ) ? parse_blocks( $creceweb_raw_content ) : array();
		$creceweb_hero_block        = \CreceWeb\Lumen\get_leading_full_width_block( $creceweb_blocks );
		$creceweb_hero_html         = '';
		$creceweb_content_html      = '';
		$creceweb_is_elementor      = false;

		if ( $creceweb_hero_block ) {
			$creceweb_hero_html        = \CreceWeb\Lumen\render_blocks( array( $creceweb_hero_block['block'] ) );
			$creceweb_remaining_blocks = \CreceWeb\Lumen\get_visible_blocks( array_slice( $creceweb_blocks, $creceweb_hero_block['index'] + 1 ) );
			$creceweb_content_html     = \CreceWeb\Lumen\render_blocks( $creceweb_remaining_blocks );
		} else {
			ob_start();
			the_content();
			$creceweb_rendered_content = (string) ob_get_clean();
			$creceweb_elementor        = \CreceWeb\Lumen\prepare_elementor_hero_content( $creceweb_rendered_content );

			if ( ! empty( $creceweb_elementor['has_elementor_hero'] ) ) {
				$creceweb_hero_html    = (string) $creceweb_elementor['hero_html'];
				$creceweb_content_html = (string) $creceweb_elementor['content_html'];
				$creceweb_is_elementor = true;
			} else {
				/* Fail-safe: no qualifying Hero means a normal single-post content flow. */
				$creceweb_content_html = $creceweb_rendered_content;
			}
		}

		$creceweb_has_hero            = '' !== trim( $creceweb_hero_html );
		$creceweb_show_featured_image = has_post_thumbnail() && 'hidden' !== $creceweb_featured_position;
		$creceweb_single_categories   = get_the_category_list( ' <span aria-hidden="true">·</span> ' );
		?>
		<?php if ( $creceweb_has_hero ) : ?>
			<section class="cw-hero-template__hero<?php echo $creceweb_is_elementor ? ' cw-hero-template__hero--elementor' : ''; ?>" data-cw-lumen-hero="1">
				<?php echo $creceweb_hero_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Rendered WordPress/Elementor markup. ?>
			</section>
		<?php endif; ?>

		<div class="<?php echo $creceweb_has_hero ? 'cw-hero-template__layout ' : ''; ?>cw-content-wrap cw-entry--single cw-single-layout--<?php echo esc_attr( $creceweb_single_layout ); ?> cw-single-header-align--<?php echo esc_attr( $creceweb_header_alignment ); ?> cw-screen-layout cw-screen-layout--<?php echo esc_attr( $creceweb_sidebar_layout ); ?>">
				<div class="cw-hero-template__content cw-screen-layout__content">
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
						<div class="entry-content wp-block-post-content" data-cw-lumen-hero-content="1">
							<?php echo $creceweb_content_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Rendered WordPress/Elementor markup. ?>
						</div>
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
				</div>
				<?php if ( 'none' !== $creceweb_sidebar_layout ) { get_sidebar(); } ?>
		</div>
	<?php endwhile; ?>
</main>
<?php get_footer();
