<?php
/**
 * Recursos de leitura dos artigos (Root WP > Artigos):
 * - séries de artigos (Parte 1, 2, 3…) com caixa de partes e navegação;
 * - botões A− / A / A+ para o visitante ajustar o tamanho do texto;
 * - indicador de tempo restante de leitura;
 * - botão "Copiar" nos blocos de código (feito em assets/js/main.js).
 *
 * @package rootwp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* --------------------------------------------------------------------------
   Séries
   -------------------------------------------------------------------------- */

/**
 * Registra a taxonomia "Séries" (aparece no editor do post, como as categorias).
 */
function rootwp_register_series() {
	if ( ! rootwp_panel_option( 'series_enabled' ) ) {
		return;
	}

	register_taxonomy(
		'serie',
		'post',
		array(
			'labels'            => array(
				'name'          => __( 'Séries', 'rootwp' ),
				'singular_name' => __( 'Série', 'rootwp' ),
				'menu_name'     => __( 'Séries', 'rootwp' ),
				'all_items'     => __( 'Todas as séries', 'rootwp' ),
				'edit_item'     => __( 'Editar série', 'rootwp' ),
				'view_item'     => __( 'Ver série', 'rootwp' ),
				'update_item'   => __( 'Atualizar série', 'rootwp' ),
				'add_new_item'  => __( 'Adicionar nova série', 'rootwp' ),
				'new_item_name' => __( 'Nome da nova série', 'rootwp' ),
				'search_items'  => __( 'Buscar séries', 'rootwp' ),
				'not_found'     => __( 'Nenhuma série encontrada.', 'rootwp' ),
				'back_to_items' => __( '← Voltar para as séries', 'rootwp' ),
			),
			'description'       => __( 'Agrupa artigos em partes (Parte 1, 2, 3…), na ordem de publicação.', 'rootwp' ),
			'public'            => true,
			'hierarchical'      => true, // Caixa de seleção no editor, como nas categorias.
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'serie' ),
		)
	);

	// Depois de ligar as séries, atualiza os endereços uma única vez (sem isso, /serie/… daria 404).
	if ( '1' !== get_option( 'rootwp_series_rewrite' ) ) {
		flush_rewrite_rules( false );
		update_option( 'rootwp_series_rewrite', '1' );
	}
}
add_action( 'init', 'rootwp_register_series' );

/**
 * Página da série: partes em ordem (da primeira para a última).
 *
 * @param WP_Query $query Consulta.
 */
function rootwp_series_archive_order( $query ) {
	if ( ! is_admin() && $query->is_main_query() && $query->is_tax( 'serie' ) ) {
		$query->set( 'orderby', 'date' );
		$query->set( 'order', 'ASC' );
	}
}
add_action( 'pre_get_posts', 'rootwp_series_archive_order' );

/**
 * Série do post e suas partes em ordem.
 *
 * @param int|null $post_id ID do post.
 * @return array|null array( term, posts, index ) ou null.
 */
function rootwp_series_data( $post_id = null ) {
	if ( ! rootwp_panel_option( 'series_enabled' ) || ! taxonomy_exists( 'serie' ) ) {
		return null;
	}
	$post_id = $post_id ? $post_id : get_the_ID();
	$terms   = get_the_terms( $post_id, 'serie' );
	if ( ! $terms || is_wp_error( $terms ) ) {
		return null;
	}

	$term  = $terms[0];
	$posts = get_posts(
		array(
			'post_type'      => 'post',
			'posts_per_page' => 100,
			'orderby'        => 'date',
			'order'          => 'ASC',
			'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery
				array(
					'taxonomy' => 'serie',
					'terms'    => $term->term_id,
				),
			),
		)
	);
	$index = array_search( $post_id, wp_list_pluck( $posts, 'ID' ), true );
	if ( false === $index || count( $posts ) < 2 ) {
		return null;
	}
	return array(
		'term'  => $term,
		'posts' => $posts,
		'index' => (int) $index,
	);
}

/**
 * Caixa da série no topo do artigo: "Série · Parte 2 de 5" e a lista de partes.
 */
function rootwp_series_box() {
	$data = rootwp_series_data();
	if ( ! $data ) {
		return;
	}
	$total   = count( $data['posts'] );
	$current = $data['index'] + 1;
	?>
	<details class="series-box" <?php echo $total <= 6 ? 'open' : ''; ?>>
		<summary class="series-box__summary">
			<span class="series-box__label"><?php esc_html_e( 'série', 'rootwp' ); ?></span>
			<span class="series-box__title"><?php echo esc_html( $data['term']->name ); ?></span>
			<span class="series-box__part">
				<?php
				/* translators: 1: número da parte, 2: total de partes */
				echo esc_html( sprintf( __( 'Parte %1$d de %2$d', 'rootwp' ), $current, $total ) );
				?>
			</span>
			<span class="series-box__progress" aria-hidden="true"><span style="width: <?php echo esc_attr( round( $current / $total * 100 ) ); ?>%"></span></span>
		</summary>
		<ol class="series-box__list">
			<?php foreach ( $data['posts'] as $i => $part ) : ?>
				<li class="<?php echo $i === $data['index'] ? 'is-current' : ( $i < $data['index'] ? 'is-before' : '' ); ?>">
					<?php if ( $i === $data['index'] ) : ?>
						<span aria-current="page"><?php echo esc_html( get_the_title( $part ) ); ?></span>
					<?php else : ?>
						<a href="<?php echo esc_url( get_permalink( $part ) ); ?>"><?php echo esc_html( get_the_title( $part ) ); ?></a>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ol>
		<a class="series-box__all" href="<?php echo esc_url( get_term_link( $data['term'] ) ); ?>"><?php esc_html_e( 'Ver a série completa', 'rootwp' ); ?> &rarr;</a>
	</details>
	<?php
}

/**
 * Navegação entre as partes, no fim do artigo.
 */
function rootwp_series_nav() {
	$data = rootwp_series_data();
	if ( ! $data ) {
		return;
	}
	$prev = $data['index'] > 0 ? $data['posts'][ $data['index'] - 1 ] : null;
	$next = isset( $data['posts'][ $data['index'] + 1 ] ) ? $data['posts'][ $data['index'] + 1 ] : null;
	?>
	<nav class="series-nav" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: nome da série */ __( 'Partes da série %s', 'rootwp' ), $data['term']->name ) ); ?>">
		<?php if ( $prev ) : ?>
			<a class="series-nav__link series-nav__link--prev" href="<?php echo esc_url( get_permalink( $prev ) ); ?>">
				<span class="series-nav__dir">
					<?php
					/* translators: %d: número da parte */
					echo esc_html( sprintf( __( '← Parte %d', 'rootwp' ), $data['index'] ) );
					?>
				</span>
				<span class="series-nav__title"><?php echo esc_html( get_the_title( $prev ) ); ?></span>
			</a>
		<?php endif; ?>
		<?php if ( $next ) : ?>
			<a class="series-nav__link series-nav__link--next" href="<?php echo esc_url( get_permalink( $next ) ); ?>">
				<span class="series-nav__dir">
					<?php
					/* translators: %d: número da parte */
					echo esc_html( sprintf( __( 'Parte %d →', 'rootwp' ), $data['index'] + 2 ) );
					?>
				</span>
				<span class="series-nav__title"><?php echo esc_html( get_the_title( $next ) ); ?></span>
			</a>
		<?php else : ?>
			<p class="series-nav__done">
				<?php
				/* translators: %s: nome da série */
				echo esc_html( sprintf( __( 'Você chegou à última parte de "%s". 🎉', 'rootwp' ), $data['term']->name ) );
				?>
			</p>
		<?php endif; ?>
	</nav>
	<?php
}

/**
 * Selo "Parte N" nos cards de posts que fazem parte de uma série.
 *
 * @return string
 */
function rootwp_series_badge() {
	$data = rootwp_series_data();
	if ( ! $data ) {
		return '';
	}
	/* translators: 1: nome da série, 2: número da parte */
	return '<span class="series-badge">' . esc_html( sprintf( __( '%1$s · parte %2$d', 'rootwp' ), $data['term']->name, $data['index'] + 1 ) ) . '</span>';
}

/* --------------------------------------------------------------------------
   Tamanho do texto (A− / A / A+) e tempo restante
   -------------------------------------------------------------------------- */

/**
 * Botões para o visitante ajustar o tamanho do texto do artigo.
 */
function rootwp_reader_tools() {
	if ( ! rootwp_panel_option( 'font_resize' ) ) {
		return;
	}
	?>
	<div class="reader-tools" role="group" aria-label="<?php esc_attr_e( 'Tamanho do texto', 'rootwp' ); ?>">
		<button type="button" class="reader-tools__btn" data-font-step="-1" aria-label="<?php esc_attr_e( 'Diminuir o texto', 'rootwp' ); ?>">A<span aria-hidden="true">−</span></button>
		<button type="button" class="reader-tools__btn reader-tools__btn--reset" data-font-step="0" aria-label="<?php esc_attr_e( 'Tamanho padrão do texto', 'rootwp' ); ?>">A</button>
		<button type="button" class="reader-tools__btn" data-font-step="1" aria-label="<?php esc_attr_e( 'Aumentar o texto', 'rootwp' ); ?>">A<span aria-hidden="true">+</span></button>
	</div>
	<?php
}

/**
 * Aplica o tamanho escolhido antes da página aparecer (evita o texto "pular").
 */
function rootwp_reader_font_script() {
	if ( ! is_singular( 'post' ) || ! rootwp_panel_option( 'font_resize' ) ) {
		return;
	}
	?>
	<script>try{var s=parseInt(localStorage.getItem('rootwp-font-step'),10);if(s){document.documentElement.style.setProperty('--reader-scale',1+s*0.1);}}catch(e){}</script>
	<?php
}
add_action( 'wp_head', 'rootwp_reader_font_script', 1 );

/**
 * Indicador "faltam X min", no canto da tela, durante a leitura.
 */
function rootwp_reading_remaining() {
	if ( ! is_singular( 'post' ) || ! rootwp_panel_option( 'reading_remaining' ) ) {
		return;
	}
	printf(
		'<div class="reading-remaining" data-minutes="%d" aria-hidden="true" hidden></div>' . "\n",
		(int) rootwp_reading_minutes( get_queried_object_id() )
	);
}
add_action( 'wp_footer', 'rootwp_reading_remaining', 5 );
