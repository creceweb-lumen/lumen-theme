<?php
/**
 * Search template.
 *
 * @package CreceWebLumen
 */

$creceweb_sidebar_layout = \CreceWeb\Lumen\get_effective_sidebar_layout( 'archive' );
get_header();
?>
<main id="cw-main-content" class="cw-template-main cw-template-main--posts">
	<div class="cw-content-wrap cw-screen-layout cw-screen-layout--<?php echo esc_attr( $creceweb_sidebar_layout ); ?>">
		<div class="cw-screen-layout__content">
			<header class="cw-archive-header cw-archive-header--single-line">
				<h1 class="wp-block-post-title"><?php esc_html_e( 'Resultados de búsqueda', 'creceweb-lumen' ); ?></h1>
				<?php /* translators: %s: Search query. */ ?>
		<p class="cw-archive-header__query"><?php printf( esc_html__( 'Buscaste: %s', 'creceweb-lumen' ), '<strong>' . esc_html( get_search_query() ) . '</strong>' ); ?></p>
				<?php get_search_form(); ?>
			</header>
			<?php get_template_part( 'template-parts/content', 'loop' ); ?>
		</div>
		<?php if ( 'none' !== $creceweb_sidebar_layout ) { get_sidebar(); } ?>
	</div>
</main>
<?php get_footer();
