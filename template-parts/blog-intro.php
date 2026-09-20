<?php
/**
 * Guided editorial introduction for the posts index.
 *
 * @package CreceWebLumen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$creceweb_settings = \CreceWeb\Lumen\get_customizations();

if ( '1' !== (string) ( $creceweb_settings['blog_intro_enabled'] ?? '1' ) ) {
	return;
}

$creceweb_page_for_posts = absint( get_option( 'page_for_posts' ) );
$creceweb_default_title  = $creceweb_page_for_posts ? get_the_title( $creceweb_page_for_posts ) : __( 'Ideas y novedades', 'creceweb-lumen' );
$creceweb_default_title  = $creceweb_default_title ? $creceweb_default_title : __( 'Ideas y novedades', 'creceweb-lumen' );
$creceweb_blog_title     = trim( (string) ( $creceweb_settings['blog_intro_title'] ?? '' ) );
$creceweb_blog_title     = '' !== $creceweb_blog_title ? $creceweb_blog_title : $creceweb_default_title;

$creceweb_blog_summary = trim( (string) ( $creceweb_settings['blog_intro_description'] ?? '' ) );
if ( '' === $creceweb_blog_summary ) {
	$creceweb_blog_summary = __( 'Ideas, recursos y novedades para impulsar tu presencia digital.', 'creceweb-lumen' );
}

$creceweb_eyebrow      = trim( (string) ( $creceweb_settings['blog_intro_eyebrow'] ?? '' ) );
$creceweb_show_button  = '1' === (string) ( $creceweb_settings['blog_intro_show_button'] ?? '1' );
$creceweb_button_label = trim( (string) ( $creceweb_settings['blog_intro_button_label'] ?? '' ) );
$creceweb_button_url   = trim( (string) ( $creceweb_settings['blog_intro_button_url'] ?? '' ) );
$creceweb_visual_type  = (string) ( $creceweb_settings['blog_intro_visual_type'] ?? 'illustration' );
$creceweb_alignment    = (string) ( $creceweb_settings['blog_intro_alignment'] ?? 'left' );

if ( ! in_array( $creceweb_visual_type, array( 'illustration', 'image', 'none' ), true ) ) {
	$creceweb_visual_type = 'illustration';
}
if ( ! in_array( $creceweb_alignment, array( 'left', 'center' ), true ) ) {
	$creceweb_alignment = 'left';
}

$creceweb_image_id = absint( $creceweb_settings['blog_intro_image'] ?? 0 );
if ( 'image' === $creceweb_visual_type && ! wp_get_attachment_image_url( $creceweb_image_id, 'large' ) ) {
	$creceweb_visual_type = 'illustration';
}
?>
<section class="cw-blog-intro cw-blog-intro--align-<?php echo esc_attr( $creceweb_alignment ); ?> cw-blog-intro--visual-<?php echo esc_attr( $creceweb_visual_type ); ?>" aria-labelledby="cw-blog-intro-title">
	<div class="cw-blog-intro__copy">
		<?php if ( '' !== $creceweb_eyebrow ) : ?>
			<p class="cw-blog-intro__eyebrow"><?php echo esc_html( $creceweb_eyebrow ); ?></p>
		<?php endif; ?>
		<h1 id="cw-blog-intro-title" class="cw-blog-intro__title"><?php echo esc_html( $creceweb_blog_title ); ?></h1>
		<?php if ( '' !== $creceweb_blog_summary ) : ?>
			<p class="cw-blog-intro__description"><?php echo esc_html( $creceweb_blog_summary ); ?></p>
		<?php endif; ?>
		<?php if ( $creceweb_show_button && '' !== $creceweb_button_label && '' !== $creceweb_button_url ) : ?>
			<a class="cw-blog-intro__cta" href="<?php echo esc_url( $creceweb_button_url ); ?>"><?php echo esc_html( $creceweb_button_label ); ?><span aria-hidden="true">→</span></a>
		<?php endif; ?>
	</div>

	<?php if ( 'none' !== $creceweb_visual_type ) : ?>
		<div class="cw-blog-intro__visual cw-blog-intro__visual--<?php echo esc_attr( $creceweb_visual_type ); ?>" aria-hidden="true">
			<?php if ( 'image' === $creceweb_visual_type ) : ?>
				<?php echo wp_get_attachment_image( $creceweb_image_id, 'large', false, array( 'class' => 'cw-blog-intro__image', 'alt' => '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php else : ?>
				<span class="cw-blog-intro__visual-label"><?php esc_html_e( 'Ideas claras', 'creceweb-lumen' ); ?></span>
				<span class="cw-blog-intro__visual-line"></span>
				<span class="cw-blog-intro__visual-hill"></span>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</section>
