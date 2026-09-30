<?php
/**
 * Comentários.
 *
 * @package rootwp
 */

if ( post_password_required() ) {
	return;
}
?>
<section id="comments" class="comments-area">
	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
			echo rootwp_icon( 'message' ); // phpcs:ignore WordPress.Security.EscapeOutput
			$count = get_comments_number();
			/* translators: %d: número de comentários */
			printf( esc_html( _n( '%d comentário', '%d comentários', $count, 'rootwp' ) ), (int) $count );
			?>
		</h2>

		<ol class="comment-list">
			<?php
			// Montagem de cada comentário em inc/comments.php.
			wp_list_comments(
				array(
					'style'    => 'ol',
					'callback' => 'rootwp_comment',
				)
			);
			?>
		</ol>

		<?php the_comments_navigation(); ?>

		<?php if ( ! comments_open() ) : ?>
			<p class="no-comments"><?php esc_html_e( 'Os comentários estão encerrados.', 'rootwp' ); ?></p>
		<?php endif; ?>
	<?php endif; ?>

	<?php comment_form(); // Textos e campos em inc/comments.php. ?>
</section>
