<?php
/**
 * Funções e definições do tema Root WP.
 *
 * @package rootwp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ROOTWP_VERSION', '3.3.2' );

/**
 * Configuração do tema.
 */
function rootwp_setup() {
	load_theme_textdomain( 'rootwp', get_template_directory() . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);
	add_image_size( 'rootwp-card', 720, 405, true );
	add_image_size( 'rootwp-wide', 1600, 900, true );
	add_image_size( 'rootwp-share', 1200, 630, true ); // Prévia ao compartilhar links (Open Graph).

	register_nav_menus(
		array(
			'primary' => __( 'Menu principal', 'rootwp' ),
			'footer'  => __( 'Menu do rodapé', 'rootwp' ),
		)
	);

	add_editor_style( array( 'assets/css/fonts.css', 'assets/css/editor.css' ) );

}
add_action( 'after_setup_theme', 'rootwp_setup' );

/**
 * Paleta de cores do editor de blocos, com as cores escolhidas em Root WP > Cores
 * (os slugs não mudam, então blocos já coloridos acompanham a troca de paleta).
 */
function rootwp_editor_palette() {
	$c = rootwp_color_scheme( 'light' );
	add_theme_support(
		'editor-color-palette',
		array(
			array( 'name' => __( 'Principal', 'rootwp' ), 'slug' => 'primary', 'color' => $c['primary'] ),
			array( 'name' => __( 'Principal suave', 'rootwp' ), 'slug' => 'primary-soft', 'color' => $c['primary-soft'] ),
			array( 'name' => __( 'Destaque', 'rootwp' ), 'slug' => 'accent', 'color' => $c['accent'] ),
			array( 'name' => __( 'Texto', 'rootwp' ), 'slug' => 'text', 'color' => $c['text'] ),
			array( 'name' => __( 'Texto secundário', 'rootwp' ), 'slug' => 'muted', 'color' => $c['muted'] ),
			array( 'name' => __( 'Superfície', 'rootwp' ), 'slug' => 'surface', 'color' => $c['surface'] ),
		)
	);
}
add_action( 'after_setup_theme', 'rootwp_editor_palette', 20 );

/**
 * Leva para o editor de blocos as cores, a largura do conteúdo e o tamanho da
 * fonte definidos no painel, para o texto no editor ficar igual ao publicado.
 *
 * @param array $settings Configurações do editor.
 * @return array
 */
function rootwp_editor_settings( $settings ) {
	$sizes = array( 'small' => '1rem', 'medium' => '1.1rem', 'large' => '1.2rem' );
	$width = array( 'narrow' => '660px', 'normal' => '720px', 'wide' => '820px' );
	$size  = rootwp_panel_option( 'article_font_size' );
	$wide  = rootwp_panel_option( 'content_width' );

	$vars = '';
	foreach ( rootwp_color_scheme( 'light' ) as $name => $value ) {
		$vars .= '--c-' . $name . ':' . $value . ';';
	}
	$vars .= '--content:' . ( isset( $width[ $wide ] ) ? $width[ $wide ] : '720px' ) . ';';
	$vars .= '--article-size:' . ( isset( $sizes[ $size ] ) ? $sizes[ $size ] : '1.1rem' ) . ';';

	$settings['styles'][] = array( 'css' => ':root{' . $vars . '}' );
	return $settings;
}
add_filter( 'block_editor_settings_all', 'rootwp_editor_settings' );

/**
 * Largura do conteúdo para embeds.
 */
function rootwp_content_width() {
	$GLOBALS['content_width'] = 720;
}
add_action( 'after_setup_theme', 'rootwp_content_width', 0 );

/**
 * Áreas de widgets.
 */
function rootwp_widgets_init() {
	$defaults = array(
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	);

	register_sidebar(
		array_merge(
			$defaults,
			array(
				'name'        => __( 'Barra lateral do blog', 'rootwp' ),
				'id'          => 'sidebar-1',
				'description' => __( 'Exibida nas listagens do blog, categorias e busca.', 'rootwp' ),
			)
		)
	);

	for ( $i = 1; $i <= 3; $i++ ) {
		register_sidebar(
			array_merge(
				$defaults,
				array(
					/* translators: %d: número da coluna */
					'name' => sprintf( __( 'Rodapé – coluna %d', 'rootwp' ), $i ),
					'id'   => 'footer-' . $i,
				)
			)
		);
	}
}
add_action( 'widgets_init', 'rootwp_widgets_init' );

/**
 * Fontes do tema (JetBrains Mono nos títulos, Google Sans no corpo), hospedadas no
 * próprio tema em assets/fonts: nenhuma requisição ao Google, o que deixa o site mais
 * rápido e evita enviar o IP do visitante a terceiros (LGPD).
 *
 * @return string URL do CSS com os @font-face.
 */
function rootwp_fonts_url() {
	return get_template_directory_uri() . '/assets/css/fonts.css';
}

/**
 * Pré-carrega as duas fontes mais usadas (latin), para o texto aparecer já com
 * a fonte certa, sem "piscar".
 */
function rootwp_preload_fonts() {
	foreach ( array( 'google-sans-latin.woff2', 'jetbrains-mono-latin.woff2' ) as $file ) {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "
",
			esc_url( get_template_directory_uri() . '/assets/fonts/' . $file )
		);
	}
}
add_action( 'wp_head', 'rootwp_preload_fonts', 1 );

/**
 * Scripts e estilos.
 */
function rootwp_scripts() {
	wp_enqueue_style( 'rootwp-fonts', rootwp_fonts_url(), array(), ROOTWP_VERSION );
	wp_enqueue_style( 'rootwp-style', get_stylesheet_uri(), array( 'rootwp-fonts' ), ROOTWP_VERSION );

	wp_enqueue_script(
		'rootwp-main',
		get_template_directory_uri() . '/assets/js/main.js',
		array(),
		ROOTWP_VERSION,
		array( 'strategy' => 'defer', 'in_footer' => true )
	);
	// Textos usados pelo JavaScript (copiar código, tempo restante, tamanho do texto).
	wp_localize_script(
		'rootwp-main',
		'rootwpReading',
		array(
			'copy'      => rootwp_panel_option( 'code_copy' ) ? __( 'Copiar', 'rootwp' ) : '',
			'copied'    => __( 'Copiado!', 'rootwp' ),
			'copyLabel' => __( 'Copiar o código', 'rootwp' ),
			/* translators: %d: minutos restantes de leitura */
			'remaining' => __( 'faltam %d min', 'rootwp' ),
			'lastMin'   => __( 'falta menos de 1 min', 'rootwp' ),
			'done'      => __( 'leitura concluída ✓', 'rootwp' ),
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'rootwp_scripts' );

/**
 * Aplica o modo escuro antes da renderização para evitar "piscar" a tela.
 */
function rootwp_theme_mode_script() {
	$mode = rootwp_panel_option( 'dark_mode_default' );
	// Sem o botão, o visitante não tem como mudar: ignora escolhas antigas salvas.
	$remember = rootwp_panel_option( 'dark_mode_toggle' ) ? 'true' : 'false';
	?>
	<script>
	(function () {
		var mode = <?php echo wp_json_encode( $mode ); ?>, dark = mode === 'dark';
		try {
			var saved = <?php echo $remember; // phpcs:ignore WordPress.Security.EscapeOutput ?> ? localStorage.getItem('as-theme') : null;
			if (saved) dark = saved === 'dark';
			else if (mode === 'auto') dark = window.matchMedia('(prefers-color-scheme: dark)').matches;
		} catch (e) {}
		document.documentElement.setAttribute('data-theme', dark ? 'dark' : 'light');
	})();
	</script>
	<?php
}
add_action( 'wp_head', 'rootwp_theme_mode_script', 0 );

/**
 * Variáveis de CSS vindas do painel (cor, fonte e largura do conteúdo).
 */
function rootwp_panel_css() {
	$sizes = array( 'small' => '1rem', 'medium' => '1.1rem', 'large' => '1.2rem' );
	$width = array( 'narrow' => '660px', 'normal' => '720px', 'wide' => '820px' );
	$size  = rootwp_panel_option( 'article_font_size' );
	$wide  = rootwp_panel_option( 'content_width' );

	$css  = ':root{';
	$css .= '--content:' . ( isset( $width[ $wide ] ) ? $width[ $wide ] : '720px' ) . ';';
	$css .= '--article-size:' . ( isset( $sizes[ $size ] ) ? $sizes[ $size ] : '1.1rem' ) . ';';
	$css .= '}';
	$css .= rootwp_color_css(); // Root WP > Cores.

	wp_add_inline_style( 'rootwp-style', $css );
}
add_action( 'wp_enqueue_scripts', 'rootwp_panel_css', 20 );

/**
 * Classes no body conforme as opções do cabeçalho.
 *
 * @param array $classes Classes.
 * @return array
 */
function rootwp_body_classes( $classes ) {
	if ( ! rootwp_panel_option( 'header_sticky' ) ) {
		$classes[] = 'header-static';
	}
	return $classes;
}
add_filter( 'body_class', 'rootwp_body_classes' );

/**
 * Favicon definido no painel do tema. Tem prioridade sobre o
 * "Ícone do site" do WordPress (Configurações > Geral).
 */
function rootwp_favicon() {
	$id = (int) rootwp_panel_option( 'favicon' );
	if ( ! $id || ! wp_attachment_is_image( $id ) ) {
		return;
	}

	$icons = array(
		array( 'icon', 32 ),
		array( 'icon', 192 ),
		array( 'apple-touch-icon', 180 ),
	);

	foreach ( $icons as $icon ) {
		$url = wp_get_attachment_image_url( $id, array( $icon[1], $icon[1] ) );
		if ( $url ) {
			printf(
				'<link rel="%1$s" href="%2$s" sizes="%3$dx%3$d" type="%4$s">' . "\n",
				esc_attr( $icon[0] ),
				esc_url( $url ),
				(int) $icon[1],
				esc_attr( get_post_mime_type( $id ) )
			);
		}
	}
}

/**
 * Troca o ícone padrão do WordPress pelo do tema quando houver um definido.
 */
function rootwp_setup_favicon() {
	if ( ! rootwp_panel_option( 'favicon' ) ) {
		return;
	}
	foreach ( array( 'wp_head', 'admin_head', 'login_head' ) as $hook ) {
		remove_action( $hook, 'wp_site_icon', 99 );
		add_action( $hook, 'rootwp_favicon', 99 );
	}
}
add_action( 'init', 'rootwp_setup_favicon' );

/**
 * Bloco de busca: coloca um placeholder quando o campo está vazio.
 *
 * @param string $content Bloco renderizado.
 * @return string
 */
function rootwp_search_block( $content ) {
	return str_replace( 'placeholder=""', 'placeholder="' . esc_attr__( 'Buscar artigos…', 'rootwp' ) . '"', $content );
}
add_filter( 'render_block_core/search', 'rootwp_search_block' );

/**
 * Envolve o contador de posts de categorias e arquivos em <span class="count">,
 * dentro do link, para poder estilizá-lo como badge.
 *
 * @param string $html Lista gerada pelo WordPress.
 * @return string
 */
function rootwp_wrap_post_count( $html ) {
	return preg_replace( '#</a>(?:\s|&nbsp;)*\((\d+)\)#', ' <span class="count">$1</span></a>', $html );
}
add_filter( 'wp_list_categories', 'rootwp_wrap_post_count' );
add_filter( 'get_archives_link', 'rootwp_wrap_post_count' );

/**
 * Indica se a barra lateral deve aparecer no contexto informado.
 *
 * @param string $context "archive" (listagens) ou "single" (artigos).
 * @return bool
 */
function rootwp_has_sidebar( $context ) {
	return is_active_sidebar( 'sidebar-1' ) && (bool) rootwp_panel_option( 'sidebar_' . $context );
}

/**
 * Estilos de bloco didáticos: selecione um bloco Grupo ou Parágrafo
 * e escolha "Dica", "Atenção" ou "Importante" na aba Estilos.
 */
function rootwp_block_styles() {
	$styles = array(
		'dica'       => __( 'Dica', 'rootwp' ),
		'atencao'    => __( 'Atenção', 'rootwp' ),
		'importante' => __( 'Importante', 'rootwp' ),
	);
	foreach ( array( 'core/group', 'core/paragraph' ) as $block ) {
		foreach ( $styles as $name => $label ) {
			register_block_style( $block, array( 'name' => $name, 'label' => $label ) );
		}
	}
}
add_action( 'init', 'rootwp_block_styles' );

/**
 * Tamanho do resumo e texto de "leia mais".
 */
add_filter( 'excerpt_length', fn() => 26, 999 );
add_filter( 'excerpt_more', fn() => '&hellip;' );

require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/toc.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/admin.php';
require get_template_directory() . '/inc/widgets.php';
require get_template_directory() . '/inc/home.php';
require get_template_directory() . '/inc/categories.php';
require get_template_directory() . '/inc/colors.php';
require get_template_directory() . '/inc/comments.php';
require get_template_directory() . '/inc/discovery.php';
require get_template_directory() . '/inc/seo.php';
require get_template_directory() . '/inc/notices.php';
require get_template_directory() . '/inc/tools.php';
require get_template_directory() . '/inc/reading.php';
