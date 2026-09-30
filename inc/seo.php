<?php
/**
 * SEO e integrações (Root WP > SEO e integrações):
 * - meta description, Open Graph e cartões do X (prévia ao compartilhar links);
 * - Google Analytics 4 ou Google Tag Manager;
 * - códigos extras no <head>, logo após o <body> e antes do </body>.
 *
 * @package rootwp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Há um plugin de SEO ativo? Nesse caso o tema não gera as meta tags.
 *
 * @return bool
 */
function rootwp_seo_plugin_active() {
	return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'SEOPRESS_VERSION' )
		|| defined( 'AIOSEO_VERSION' ) || defined( 'THE_SEO_FRAMEWORK_VERSION' ) || class_exists( 'Jetpack_SEO' );
}

/**
 * Texto curto e limpo para descrição (sem HTML, uma linha, até 160 caracteres).
 *
 * @param string $text Texto.
 * @return string
 */
function rootwp_seo_clean( $text ) {
	$text = trim( preg_replace( '/\s+/u', ' ', wp_strip_all_tags( strip_shortcodes( (string) $text ) ) ) );
	return mb_strlen( $text ) > 160 ? rtrim( mb_substr( $text, 0, 157 ) ) . '…' : $text;
}

/**
 * Descrição da página atual.
 *
 * @return string
 */
function rootwp_seo_description() {
	if ( is_singular() ) {
		$post = get_queried_object();
		$text = has_excerpt( $post ) ? $post->post_excerpt : $post->post_content;
	} elseif ( is_front_page() || is_home() ) {
		$text = rootwp_panel_option( 'seo_home_description' );
		$text = $text ? $text : get_bloginfo( 'description' );
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$text = term_description();
	} elseif ( is_author() ) {
		$text = get_the_author_meta( 'description', get_queried_object_id() );
	} else {
		$text = '';
	}

	$text = rootwp_seo_clean( $text );
	return $text ? $text : rootwp_seo_clean( get_bloginfo( 'description' ) );
}

/**
 * Imagem para a prévia: destacada do post > capa da categoria > padrão do painel > ícone do site.
 *
 * @return array|null array( url, largura, altura ).
 */
function rootwp_seo_image() {
	$id = 0;
	if ( is_singular() && has_post_thumbnail() ) {
		$id = get_post_thumbnail_id( get_queried_object_id() );
	} elseif ( is_category() ) {
		$id = (int) rootwp_category_meta( get_queried_object_id(), 'cover' );
	}
	if ( ! $id ) {
		$id = (int) rootwp_panel_option( 'seo_default_image' );
	}
	if ( ! $id ) {
		$id = (int) rootwp_panel_option( 'favicon' );
	}
	if ( ! $id ) {
		$id = (int) get_option( 'site_icon' );
	}
	if ( ! $id ) {
		return null;
	}
	// Versão recortada em 1200x630 (formato das prévias); se ainda não existir, a "large".
	$image = wp_get_attachment_image_src( $id, 'rootwp-share' );
	if ( ! $image || empty( $image[3] ) ) {
		$image = wp_get_attachment_image_src( $id, 'large' );
	}
	return $image ? array( $image[0], (int) $image[1], (int) $image[2] ) : null;
}

/**
 * Endereço canônico da página atual.
 *
 * @return string
 */
function rootwp_seo_url() {
	if ( is_singular() ) {
		return get_permalink( get_queried_object_id() );
	}
	if ( is_category() || is_tag() || is_tax() ) {
		return get_term_link( get_queried_object() );
	}
	if ( is_author() ) {
		return get_author_posts_url( get_queried_object_id() );
	}
	if ( is_home() && ! is_front_page() ) {
		return get_permalink( (int) get_option( 'page_for_posts' ) );
	}
	if ( is_front_page() ) {
		return home_url( '/' );
	}
	return home_url( trailingslashit( $GLOBALS['wp']->request ) );
}

/**
 * Imprime as meta tags de SEO e de compartilhamento.
 */
function rootwp_seo_meta() {
	if ( ! rootwp_panel_option( 'seo_enabled' ) || rootwp_seo_plugin_active() || is_404() ) {
		return;
	}

	$tags        = array();
	$description = rootwp_seo_description();
	$image       = rootwp_seo_image();
	$is_article  = is_singular( 'post' );
	$twitter     = ltrim( rootwp_panel_option( 'seo_twitter' ), '@' );

	if ( $description ) {
		$tags[] = array( 'name', 'description', $description );
	}

	$tags[] = array( 'property', 'og:locale', get_locale() );
	$tags[] = array( 'property', 'og:site_name', get_bloginfo( 'name' ) );
	$tags[] = array( 'property', 'og:type', $is_article ? 'article' : ( is_author() ? 'profile' : 'website' ) );
	$tags[] = array( 'property', 'og:title', wp_get_document_title() );
	$tags[] = array( 'property', 'og:url', rootwp_seo_url() );
	if ( $description ) {
		$tags[] = array( 'property', 'og:description', $description );
	}
	if ( $image ) {
		$tags[] = array( 'property', 'og:image', $image[0] );
		$tags[] = array( 'property', 'og:image:width', $image[1] );
		$tags[] = array( 'property', 'og:image:height', $image[2] );
	}

	if ( $is_article ) {
		$post   = get_queried_object();
		$tags[] = array( 'property', 'article:published_time', get_post_time( DATE_W3C, true, $post ) );
		$tags[] = array( 'property', 'article:modified_time', get_post_modified_time( DATE_W3C, true, $post ) );
		foreach ( get_the_category( $post->ID ) as $category ) {
			$tags[] = array( 'property', 'article:section', $category->name );
			break;
		}
		foreach ( (array) get_the_tags( $post->ID ) as $tag ) {
			if ( $tag ) {
				$tags[] = array( 'property', 'article:tag', $tag->name );
			}
		}
	}

	$tags[] = array( 'name', 'twitter:card', $image && $image[1] >= 300 ? 'summary_large_image' : 'summary' );
	if ( $twitter ) {
		$tags[] = array( 'name', 'twitter:site', '@' . $twitter );
	}

	echo "\n<!-- Root WP: SEO e compartilhamento -->\n";
	foreach ( $tags as $tag ) {
		printf( '<meta %1$s="%2$s" content="%3$s">' . "\n", esc_attr( $tag[0] ), esc_attr( $tag[1] ), esc_attr( $tag[2] ) );
	}
}
add_action( 'wp_head', 'rootwp_seo_meta', 5 );

/**
 * Resultados de busca não devem ir para o Google (evita páginas duplicadas/ralas).
 *
 * @param array $robots Diretivas.
 * @return array
 */
function rootwp_seo_robots( $robots ) {
	if ( rootwp_panel_option( 'seo_enabled' ) && ! rootwp_seo_plugin_active() && is_search() ) {
		$robots['noindex'] = true;
		$robots['follow']  = true;
	}
	return $robots;
}
add_filter( 'wp_robots', 'rootwp_seo_robots' );

/**
 * O visitante atual deve ser contado nas estatísticas?
 *
 * @return bool
 */
function rootwp_track_visitor() {
	return ! ( rootwp_panel_option( 'analytics_skip_admins' ) && current_user_can( 'edit_posts' ) );
}

/**
 * Tipo do ID de estatísticas: ga4 (G-...), gtm (GTM-...) ou vazio.
 *
 * @return string
 */
function rootwp_analytics_type() {
	$id = strtoupper( trim( rootwp_panel_option( 'analytics_id' ) ) );
	if ( preg_match( '/^G-[A-Z0-9]+$/', $id ) ) {
		return 'ga4';
	}
	if ( preg_match( '/^GTM-[A-Z0-9]+$/', $id ) ) {
		return 'gtm';
	}
	return '';
}

/**
 * Google Analytics 4 / Tag Manager e código extra no <head>.
 */
function rootwp_head_code() {
	$type = rootwp_analytics_type();
	$id   = strtoupper( trim( rootwp_panel_option( 'analytics_id' ) ) );

	if ( $type && rootwp_track_visitor() ) {
		if ( 'ga4' === $type ) {
			?>
<!-- Google Analytics 4 (Root WP) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( $id ); ?>"></script>
<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config',<?php echo wp_json_encode( $id ); ?>);</script>
			<?php
		} else {
			?>
<!-- Google Tag Manager (Root WP) -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer',<?php echo wp_json_encode( $id ); ?>);</script>
			<?php
		}
	}

	$code = rootwp_panel_option( 'code_head' );
	if ( $code ) {
		echo "\n" . $code . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput -- código colado por administrador com permissão unfiltered_html.
	}
}
add_action( 'wp_head', 'rootwp_head_code', 20 );

/**
 * Tag Manager (noscript) e código extra logo após o <body>.
 */
function rootwp_body_open_code() {
	if ( 'gtm' === rootwp_analytics_type() && rootwp_track_visitor() ) {
		$id = strtoupper( trim( rootwp_panel_option( 'analytics_id' ) ) );
		printf(
			'<noscript><iframe src="%s" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>' . "\n",
			esc_url( 'https://www.googletagmanager.com/ns.html?id=' . $id )
		);
	}
	$code = rootwp_panel_option( 'code_body_open' );
	if ( $code ) {
		echo $code . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput -- código colado por administrador com permissão unfiltered_html.
	}
}
add_action( 'wp_body_open', 'rootwp_body_open_code', 1 );

/**
 * Código extra antes do </body>.
 */
function rootwp_footer_code() {
	$code = rootwp_panel_option( 'code_footer' );
	if ( $code ) {
		echo "\n" . $code . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput -- código colado por administrador com permissão unfiltered_html.
	}
}
add_action( 'wp_footer', 'rootwp_footer_code', 100 );
