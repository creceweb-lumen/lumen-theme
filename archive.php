<?php
/**
 * Archive template.
 *
 * @package CreceWebLumen
 */

$creceweb_sidebar_layout = \CreceWeb\Lumen\get_effective_sidebar_layout( 'archive' );
$creceweb_archive_header_class = is_category() ? 'cw-archive-header cw-archive-header--single-line' : 'cw-archive-header';
get_header();
?>
<main id="cw-main-content" class="cw-template-main cw-template-main--posts">
	<div class="cw-content-wrap cw-screen-layout cw-screen-layout--<?php echo esc_attr( $creceweb_sidebar_layout ); ?>">
		<div class="cw-screen-layout__content">
			<header class="<?php echo esc_attr( $creceweb_archive_header_class ); ?>">
				<h1 class="wp-block-post-title"><?php the_archive_title(); ?></h1>
				<?php the_archive_description( '<div class="archive-description">', '</div>' ); ?>
			</header>
			<?php get_template_part( 'template-parts/content', 'loop' ); ?>
		</div>
		<?php if ( 'none' !== $creceweb_sidebar_layout ) { get_sidebar(); } ?>
	</div>
</main>
<?php get_footer();
