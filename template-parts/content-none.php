<?php
/**
 * Mensagem quando não há posts.
 *
 * @package rootwp
 */

?>
<section class="no-results">
	<h1 class="page-title"><?php esc_html_e( 'Nada encontrado', 'rootwp' ); ?></h1>

	<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
		<p>
			<?php
			printf(
				/* translators: %s: link para novo post */
				wp_kses_post( __( 'Pronto para publicar seu primeiro artigo? <a href="%s">Comece aqui</a>.', 'rootwp' ) ),
				esc_url( admin_url( 'post-new.php' ) )
			);
			?>
		</p>
	<?php elseif ( is_search() ) : ?>
		<p><?php esc_html_e( 'Nenhum resultado para sua busca. Tente outras palavras-chave.', 'rootwp' ); ?></p>
		<?php get_search_form(); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'Não encontramos o que você procura. Que tal fazer uma busca?', 'rootwp' ); ?></p>
		<?php get_search_form(); ?>
	<?php endif; ?>
</section>
