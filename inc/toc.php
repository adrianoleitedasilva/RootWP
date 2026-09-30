<?php
/**
 * Sumário automático: adiciona IDs aos títulos H2/H3 do conteúdo
 * e gera a lista de links exibida ao lado do artigo.
 *
 * @package rootwp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Títulos encontrados no conteúdo do post atual.
 *
 * @var array
 */
$GLOBALS['rootwp_toc'] = array();

/**
 * Adiciona âncoras aos H2/H3 e registra os títulos.
 *
 * @param string $content HTML do conteúdo.
 * @return string
 */
function rootwp_toc_process( $content ) {
	$GLOBALS['rootwp_toc'] = array();
	$used                         = array();

	// Níveis definidos em Tema > Artigos: só H2 ou H2 + H3.
	$levels = 'h2' === rootwp_panel_option( 'toc_levels' ) ? '2' : '23';

	return preg_replace_callback(
		'/<h([' . $levels . '])([^>]*)>(.*?)<\/h\1>/is',
		function ( $m ) use ( &$used ) {
			$level = $m[1];
			$attrs = $m[2];
			$text  = wp_strip_all_tags( $m[3] );

			if ( preg_match( '/\sid=["\']([^"\']+)["\']/', $attrs, $id_match ) ) {
				$id = $id_match[1];
			} else {
				$id    = sanitize_title( $text );
				$id    = $id ? $id : 'secao';
				$base  = $id;
				$i     = 2;
				while ( isset( $used[ $id ] ) ) {
					$id = $base . '-' . $i++;
				}
				$attrs .= ' id="' . esc_attr( $id ) . '"';
			}
			$used[ $id ] = true;

			$GLOBALS['rootwp_toc'][] = array(
				'level' => (int) $level,
				'id'    => $id,
				'text'  => $text,
			);

			return '<h' . $level . $attrs . '>' . $m[3] . '</h' . $level . '>';
		},
		$content
	);
}

/**
 * Filtro do conteúdo: aplica as âncoras apenas no post exibido.
 *
 * @param string $content Conteúdo do post.
 * @return string
 */
function rootwp_toc_add_anchors( $content ) {
	if ( ! rootwp_panel_option( 'toc_enabled' ) || ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}
	return rootwp_toc_process( $content );
}
add_filter( 'the_content', 'rootwp_toc_add_anchors', 20 );

/**
 * Lê os títulos do post atual antes de exibir o conteúdo,
 * para decidir o layout (com ou sem sumário). Usa o mesmo algoritmo
 * do filtro, então os IDs gerados coincidem.
 *
 * @return array
 */
function rootwp_get_toc() {
	static $cache = array();
	if ( ! rootwp_panel_option( 'toc_enabled' ) ) {
		return array();
	}
	$post_id = get_the_ID();
	if ( ! isset( $cache[ $post_id ] ) ) {
		rootwp_toc_process( do_blocks( get_the_content() ) );
		$cache[ $post_id ] = $GLOBALS['rootwp_toc'];
	}
	return $cache[ $post_id ];
}

/**
 * Indica se o post atual tem títulos suficientes para exibir o sumário.
 *
 * @return bool
 */
function rootwp_has_toc() {
	return count( rootwp_get_toc() ) >= (int) rootwp_panel_option( 'toc_min' );
}

/**
 * Exibe o sumário (somente se houver o mínimo de títulos definido no painel).
 */
function rootwp_the_toc() {
	if ( ! rootwp_has_toc() ) {
		return;
	}
	$items = rootwp_get_toc();
	?>
	<nav class="toc" aria-label="<?php esc_attr_e( 'Sumário', 'rootwp' ); ?>">
		<p class="toc__title"><?php echo esc_html( rootwp_panel_option( 'toc_title' ) ); ?></p>
		<ol>
			<?php foreach ( $items as $item ) : ?>
				<li class="toc-h<?php echo (int) $item['level']; ?>">
					<a href="#<?php echo esc_attr( $item['id'] ); ?>"><?php echo esc_html( $item['text'] ); ?></a>
				</li>
			<?php endforeach; ?>
		</ol>
	</nav>
	<?php
}
