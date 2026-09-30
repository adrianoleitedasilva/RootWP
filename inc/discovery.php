<?php
/**
 * Páginas de descoberta: perfil do autor, busca e 404.
 *
 * @package rootwp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Destaca (com <mark>) as palavras buscadas num texto já sem HTML.
 *
 * @param string $text Texto puro.
 * @return string HTML seguro.
 */
function rootwp_highlight( $text ) {
	$terms = array_filter(
		preg_split( '/\s+/u', trim( get_search_query( false ) ) ),
		function ( $term ) {
			return mb_strlen( $term ) >= 2;
		}
	);
	if ( ! $terms ) {
		return esc_html( $text );
	}

	// Separa o texto puro nos trechos que batem com a busca e escapa cada pedaço.
	$pattern = '/(' . implode( '|', array_map( 'preg_quote', $terms, array_fill( 0, count( $terms ), '/' ) ) ) . ')/iu';
	$parts   = preg_split( $pattern, $text, -1, PREG_SPLIT_DELIM_CAPTURE );
	$html    = '';
	foreach ( $parts as $i => $part ) {
		$html .= 1 === $i % 2 ? '<mark>' . esc_html( $part ) . '</mark>' : esc_html( $part );
	}
	return $html;
}

/**
 * Ícones das redes de um autor (preenchidas em Usuários > Perfil).
 *
 * @param int    $author_id ID do autor.
 * @param string $class     Classe extra da lista.
 */
function rootwp_author_social_links( $author_id, $class = '' ) {
	$items = '';
	$name  = get_the_author_meta( 'display_name', $author_id );
	foreach ( rootwp_author_networks() as $network => $label ) {
		$url = get_the_author_meta( 'rootwp_' . $network, $author_id );
		if ( $url ) {
			$items .= sprintf(
				'<li><a class="icon-btn social--%1$s" href="%2$s" target="_blank" rel="noopener me" aria-label="%3$s">%4$s</a></li>',
				esc_attr( $network ),
				esc_url( $url ),
				/* translators: 1: nome do autor, 2: nome da rede social */
				esc_attr( sprintf( __( '%1$s no %2$s', 'rootwp' ), $name, $label ) ),
				rootwp_icon( $network )
			);
		}
	}
	if ( $items ) {
		echo '<ul class="social-links ' . esc_attr( $class ) . '">' . $items . '</ul>'; // phpcs:ignore WordPress.Security.EscapeOutput
	}
}

/**
 * Categorias mais usadas em pílulas (com a cor de cada uma).
 *
 * @param int $number Quantidade.
 */
function rootwp_category_chips( $number = 8 ) {
	$categories = get_categories(
		array(
			'orderby'    => 'count',
			'order'      => 'DESC',
			'number'     => $number,
			'hide_empty' => true,
			'exclude'    => array( (int) get_option( 'default_category' ) ),
		)
	);
	if ( ! $categories ) {
		return;
	}
	echo '<ul class="as-tags as-tags--pills as-tags--categories discovery-chips">';
	foreach ( $categories as $category ) {
		printf(
			'<li><a class="as-tag" href="%1$s"%2$s><span class="as-tag__dot" aria-hidden="true"></span>%3$s <span class="count">%4$d</span></a></li>',
			esc_url( get_category_link( $category ) ),
			rootwp_category_style_attr( $category->term_id ), // phpcs:ignore WordPress.Security.EscapeOutput
			esc_html( $category->name ),
			(int) $category->count
		);
	}
	echo '</ul>';
}

/**
 * Bloco de sugestões para quando não há o que mostrar (busca vazia e 404):
 * temas para explorar e artigos recentes.
 */
function rootwp_suggestions() {
	$recent = new WP_Query(
		array(
			'posts_per_page'      => 3,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);
	?>
	<div class="suggestions">
		<?php
		ob_start();
		rootwp_category_chips();
		$chips = ob_get_clean();
		if ( $chips ) :
			?>
			<section class="suggestions__block">
				<h2 class="suggestions__title"><?php esc_html_e( 'Explore por tema', 'rootwp' ); ?></h2>
				<?php echo $chips; // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</section>
		<?php endif; ?>

		<?php if ( $recent->have_posts() ) : ?>
			<section class="suggestions__block">
				<h2 class="suggestions__title"><?php esc_html_e( 'Artigos recentes', 'rootwp' ); ?></h2>
				<div class="post-grid">
					<?php
					while ( $recent->have_posts() ) {
						$recent->the_post();
						get_template_part( 'template-parts/content', 'card' );
					}
					wp_reset_postdata();
					?>
				</div>
			</section>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Categorias presentes nos resultados da busca atual (para refinar).
 *
 * @return array id => array( nome, quantidade ).
 */
function rootwp_search_categories() {
	$ids = get_posts(
		array(
			's'              => get_search_query( false ),
			'post_type'      => 'post',
			'posts_per_page' => 200,
			'fields'         => 'ids',
			'no_found_rows'  => true,
		)
	);
	$found = array();
	foreach ( $ids as $id ) {
		foreach ( get_the_category( $id ) as $category ) {
			if ( ! isset( $found[ $category->term_id ] ) ) {
				$found[ $category->term_id ] = array( $category->name, 0 );
			}
			$found[ $category->term_id ][1]++;
		}
	}
	uasort(
		$found,
		function ( $a, $b ) {
			return $b[1] - $a[1];
		}
	);
	return $found;
}

/**
 * Filtro por tema na página do autor (?tema=ID). Não usa ?cat= porque o
 * WordPress transformaria a página do autor numa página de categoria.
 *
 * @param array $vars Variáveis de consulta públicas.
 * @return array
 */
function rootwp_author_query_vars( $vars ) {
	$vars[] = 'tema';
	return $vars;
}
add_filter( 'query_vars', 'rootwp_author_query_vars' );

/**
 * Aplica o filtro por tema aos artigos da página do autor.
 *
 * @param WP_Query $query Consulta.
 */
function rootwp_author_filter( $query ) {
	if ( ! is_admin() && $query->is_main_query() && $query->is_author() && $query->get( 'tema' ) ) {
		$query->set( 'category__in', array( absint( $query->get( 'tema' ) ) ) );
	}
}
add_action( 'pre_get_posts', 'rootwp_author_filter' );
