<?php
/**
 * Reusable archive card.
 *
 * @package CreceWebLumen
 */

$creceweb_settings = \CreceWeb\Lumen\get_customizations();
$show_image         = '1' === (string) ( $creceweb_settings['blog_show_featured_image'] ?? '1' );
$show_category      = '1' === (string) ( $creceweb_settings['blog_show_category'] ?? '1' );
$show_meta          = '1' === (string) ( $creceweb_settings['blog_show_meta'] ?? '1' );
$show_excerpt       = '1' === (string) ( $creceweb_settings['blog_show_excerpt'] ?? '1' );
$show_read_more     = '1' === (string) ( $creceweb_settings['blog_show_read_more'] ?? '1' );
$default_read_more_label = __( 'Seguir leyendo', 'creceweb-lumen' );
$read_more_label         = trim( (string) ( $creceweb_settings['blog_read_more_label'] ?? $default_read_more_label ) );
if ( '' === $read_more_label ) {
	$read_more_label = $default_read_more_label;
}
$creceweb_layout    = 'grid' === (string) ( $creceweb_settings['blog_layout'] ?? 'list' ) ? 'grid' : 'list';
$creceweb_card_layout = 'list' === $creceweb_layout ? (string) ( $creceweb_settings['blog_card_layout'] ?? 'vertical' ) : 'vertical';
$creceweb_allowed_card_layouts = array( 'vertical', 'media_left', 'compact' );
if ( ! in_array( $creceweb_card_layout, $creceweb_allowed_card_layouts, true ) ) {
	$creceweb_card_layout = 'vertical';
}
$creceweb_categories = $show_category ? get_the_category_list( ' <span aria-hidden="true">·</span> ' ) : '';
$creceweb_card_classes = array(
	'cw-card',
	'wp-block-group',
	'cw-card--layout-' . str_replace( '_', '-', $creceweb_card_layout ),
);
if ( ! $show_image || ! has_post_thumbnail() ) {
	$creceweb_card_classes[] = 'cw-card--without-media';
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( $creceweb_card_classes ); ?>>
	<?php if ( $show_image && has_post_thumbnail() ) : ?>
		<a class="cw-card__media" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1"><?php the_post_thumbnail( 'large' ); ?></a>
	<?php endif; ?>
	<div class="cw-card__body">
		<?php if ( $show_category && $creceweb_categories ) : ?><div class="cw-card__eyebrow"><?php echo wp_kses_post( $creceweb_categories ); ?></div><?php endif; ?>
		<h2 class="entry-title wp-block-post-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
		<?php if ( $show_meta ) : ?><div class="cw-card__meta"><time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></div><?php endif; ?>
		<?php do_action( 'creceweb_lumen_loop_after_meta', get_the_ID(), \CreceWeb\Lumen\get_loop_context() ); ?>
		<?php if ( $show_excerpt ) : ?><div class="cw-card__excerpt"><?php the_excerpt(); ?></div><?php endif; ?>
		<?php if ( $show_read_more ) : ?><a class="cw-card__more" href="<?php the_permalink(); ?>"><?php echo esc_html( $read_more_label ); ?></a><?php endif; ?>
	</div>
</article>
