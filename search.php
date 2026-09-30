<?php
/**
 * Resultados da busca: campo grande para refinar, filtro por categoria,
 * resultados em lista com o termo destacado e sugestões quando não há nada.
 *
 * @package rootwp
 */

get_header();

$query      = get_search_query( false );
$total      = (int) $wp_query->found_posts;
$current    = (int) get_query_var( 'cat' );
$categories = $query ? rootwp_search_categories() : array();
$sidebar    = rootwp_has_sidebar( 'archive' );
?>

<main id="primary" class="site-main site-main--search">
	<header class="search-hero">
		<div class="container">
			<span class="search-hero__label"><?php esc_html_e( 'busca', 'rootwp' ); ?></span>
			<h1 class="search-hero__title">
				<?php
				if ( $query ) {
					/* translators: %s: termo buscado */
					printf( esc_html__( 'Resultados para “%s”', 'rootwp' ), '<span>' . esc_html( $query ) . '</span>' );
				} else {
					esc_html_e( 'O que você está procurando?', 'rootwp' );
				}
				?>
			</h1>

			<form role="search" method="get" class="search-form search-form--large" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<label class="screen-reader-text" for="search-page-field"><?php esc_html_e( 'Buscar por:', 'rootwp' ); ?></label>
				<?php echo rootwp_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<input type="search" id="search-page-field" class="search-field" name="s" value="<?php echo esc_attr( $query ); ?>" placeholder="<?php esc_attr_e( 'Buscar artigos…', 'rootwp' ); ?>">
				<?php if ( $current ) : ?>
					<input type="hidden" name="cat" value="<?php echo (int) $current; ?>">
				<?php endif; ?>
				<button type="submit" class="btn search-submit"><?php esc_html_e( 'Buscar', 'rootwp' ); ?></button>
			</form>

			<?php if ( $query ) : ?>
				<p class="search-hero__count">
					<?php
					if ( $total ) {
						/* translators: %d: número de resultados */
						printf( esc_html( _n( '%d artigo encontrado', '%d artigos encontrados', $total, 'rootwp' ) ), (int) $total );
					} else {
						esc_html_e( 'Nenhum artigo encontrado.', 'rootwp' );
					}
					?>
				</p>
			<?php endif; ?>

			<?php if ( count( $categories ) > 1 || $current ) : ?>
				<?php // Refinar por categoria: só as que têm resultados para este termo. ?>
				<ul class="search-filters">
					<li><a class="<?php echo $current ? '' : 'is-active'; ?>" href="<?php echo esc_url( add_query_arg( 's', rawurlencode( $query ), home_url( '/' ) ) ); ?>"><?php esc_html_e( 'Todas', 'rootwp' ); ?></a></li>
					<?php foreach ( $categories as $id => $data ) : ?>
						<li>
							<a class="<?php echo $current === $id ? 'is-active' : ''; ?>" href="<?php echo esc_url( add_query_arg( array( 's' => rawurlencode( $query ), 'cat' => $id ), home_url( '/' ) ) ); ?>"<?php echo rootwp_category_style_attr( $id ); // phpcs:ignore WordPress.Security.EscapeOutput ?>>
								<?php echo esc_html( $data[0] ); ?> <span class="count"><?php echo (int) $data[1]; ?></span>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	</header>

	<div class="container">
		<div class="layout<?php echo $sidebar ? '' : ' layout--full'; ?>">
			<div>
				<?php if ( have_posts() ) : ?>
					<div class="post-list">
						<?php
						while ( have_posts() ) :
							the_post();
							get_template_part( 'template-parts/content', 'list', array( 'highlight' => true ) );
						endwhile;
						?>
					</div>
					<?php rootwp_pagination(); ?>
				<?php else : ?>
					<div class="search-empty">
						<p class="search-empty__tips">
							<?php esc_html_e( 'Dicas: confira a ortografia, use menos palavras ou termos mais gerais, como “javascript” em vez de “erro no fetch do javascript”.', 'rootwp' ); ?>
						</p>
						<?php rootwp_suggestions(); ?>
					</div>
				<?php endif; ?>
			</div>

			<?php
			if ( $sidebar ) {
				get_sidebar();
			}
			?>
		</div>
	</div>
</main>

<?php
get_footer();
