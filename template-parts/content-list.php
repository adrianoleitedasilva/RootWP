<?php
/**
 * Post em formato de lista (layout "Lista" das categorias):
 * imagem à esquerda, resumo à direita.
 *
 * @package rootwp
 */

// Na busca, $args['highlight'] destaca as palavras buscadas no título e no resumo.
$highlight = ! empty( $args['highlight'] );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'list-item' ); ?>>
	<a class="list-item__thumb<?php echo has_post_thumbnail() ? '' : ' card__thumb--placeholder'; ?>" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
		<?php
		if ( has_post_thumbnail() ) {
			the_post_thumbnail( 'rootwp-card', array( 'loading' => 'lazy' ) );
		} else {
			echo esc_html( mb_strtoupper( mb_substr( get_the_title(), 0, 1 ) ) );
		}
		?>
	</a>

	<div class="list-item__body">
		<div class="card__badges"><?php rootwp_category_badge(); ?><?php echo rootwp_series_badge(); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
		<?php if ( $highlight ) : ?>
			<h2 class="list-item__title"><a href="<?php the_permalink(); ?>"><?php echo rootwp_highlight( get_the_title() ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a></h2>
			<div class="list-item__excerpt"><p><?php echo rootwp_highlight( wp_strip_all_tags( get_the_excerpt() ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p></div>
		<?php else : ?>
			<h2 class="list-item__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			<div class="list-item__excerpt"><?php the_excerpt(); ?></div>
		<?php endif; ?>
		<?php rootwp_entry_meta( false, true ); // Com ícones de calendário e relógio. ?>
	</div>
</article>
