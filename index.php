<?php
/**
 * Fallback posts template.
 *
 * @package CreceWebLumen
 */

$creceweb_sidebar_layout = \CreceWeb\Lumen\get_effective_sidebar_layout( 'archive' );
get_header();
?>
<main id="cw-main-content" class="cw-template-main cw-template-main--posts">
	<div class="cw-content-wrap cw-screen-layout cw-screen-layout--<?php echo esc_attr( $creceweb_sidebar_layout ); ?>">
		<div class="cw-screen-layout__content">
			<?php get_template_part( 'template-parts/content', 'loop' ); ?>
		</div>
		<?php if ( 'none' !== $creceweb_sidebar_layout ) { get_sidebar(); } ?>
	</div>
</main>
<?php get_footer();
