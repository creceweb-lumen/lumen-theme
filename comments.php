<?php
/**
 * Comments template.
 *
 * @package CreceWebLumen
 */

if ( post_password_required() ) {
	return;
}
?>
<section id="comments" class="cw-comments" aria-labelledby="comments-title">
	<?php if ( have_comments() ) : ?>
		<h2 id="comments-title" class="cw-comments__title">
			<?php
			$creceweb_comment_count = get_comments_number();
			printf(
				esc_html(
					_nx(
						'%s comentario',
						'%s comentarios',
						$creceweb_comment_count,
						'comments title',
						'creceweb-lumen'
					)
				),
				esc_html( number_format_i18n( $creceweb_comment_count ) )
			);
			?>
		</h2>

		<ol class="comment-list cw-comments__list">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 48,
				)
			);
			?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<nav class="cw-comments__navigation" aria-label="<?php esc_attr_e( 'Navegación de comentarios', 'creceweb-lumen' ); ?>">
				<?php paginate_comments_links(); ?>
			</nav>
		<?php endif; ?>
	<?php endif; ?>

	<?php if ( ! comments_open() && get_comments_number() ) : ?>
		<p class="cw-comments__closed"><?php esc_html_e( 'Los comentarios están cerrados.', 'creceweb-lumen' ); ?></p>
	<?php endif; ?>

	<?php
	if ( comments_open() ) {
		comment_form(
			array(
				'title_reply_before'   => '<h2 id="reply-title" class="cw-comments__reply-title">',
				'title_reply_after'    => '</h2>',
				'title_reply'          => esc_html__( 'Dejá un comentario', 'creceweb-lumen' ),
				/* translators: %s: Name of the person being replied to. */
		'title_reply_to'       => esc_html__( 'Responder a %s', 'creceweb-lumen' ),
				'cancel_reply_link'    => esc_html__( 'Cancelar respuesta', 'creceweb-lumen' ),
				'label_submit'         => esc_html__( 'Publicar comentario', 'creceweb-lumen' ),
				'comment_notes_before' => '<p class="comment-notes">' . esc_html__( 'Tu dirección de correo electrónico no será publicada.', 'creceweb-lumen' ) . '</p>',
			)
		);
	}
	?>
</section>
