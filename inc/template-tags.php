<?php
/**
 * Funções auxiliares usadas nos templates.
 *
 * @package rootwp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Retorna um ícone SVG inline.
 *
 * @param string $name Nome do ícone.
 * @return string
 */
function rootwp_icon( $name ) {
	$icons = array(
		'menu'     => '<path d="M4 6h16M4 12h16M4 18h16"/>',
		'search'   => '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>',
		'moon'     => '<path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/>',
		'sun'      => '<circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/>',
		'clock'    => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
		'reply'    => '<path d="M9 14 4 9l5-5"/><path d="M4 9h10a6 6 0 0 1 6 6v5"/>',
		'edit'     => '<path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/>',
		'message'  => '<path d="M21 12a8 8 0 0 1-11.6 7.1L4 20l1-4.4A8 8 0 1 1 21 12z"/>',
		'user'     => '<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>',
		'calendar' => '<rect x="3" y="4.5" width="18" height="16.5" rx="2.5"/><path d="M3 9.5h18M8 2.5v4M16 2.5v4"/>',
		'link'     => '<path d="M10 13a5 5 0 0 0 7.5.5l3-3a5 5 0 0 0-7-7l-1.7 1.7"/><path d="M14 11a5 5 0 0 0-7.5-.5l-3 3a5 5 0 0 0 7 7l1.7-1.7"/>',
		'linkedin' => '<path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-4 0v7h-4v-7a6 6 0 0 1 6-6zM2 9h4v12H2zM4 2a2 2 0 1 1 0 4 2 2 0 0 1 0-4z"/>',
		'whatsapp' => '<path d="M3 21l1.7-5A9 9 0 1 1 8 19.3z"/><path d="M9 10c.5 2 2 3.5 4 4l1.2-1.2 2 1-.5 1.7c-3.5.5-8-4-7.5-7.5L10 7.5l1 2z"/>',
		'x'        => '<path d="M4 4l16 16M20 4 4 20"/>',
		'github'   => '<path d="M9 19c-4.3 1.4-4.3-2.5-6-3m12 5v-3.5c0-1 .1-1.4-.5-2 2.8-.3 5.5-1.4 5.5-6a4.6 4.6 0 0 0-1.3-3.2 4.2 4.2 0 0 0-.1-3.2s-1.1-.3-3.5 1.3a12.3 12.3 0 0 0-6.2 0C6.5 2.8 5.4 3.1 5.4 3.1a4.2 4.2 0 0 0-.1 3.2A4.6 4.6 0 0 0 4 9.5c0 4.6 2.7 5.7 5.5 6-.6.6-.6 1.2-.5 2V21"/>',
		'instagram' => '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><path d="M17.5 6.5h.01"/>',
		'youtube'  => '<path d="M22 8.6a3 3 0 0 0-2.1-2.1C18 6 12 6 12 6s-6 0-7.9.5A3 3 0 0 0 2 8.6 31 31 0 0 0 1.5 12 31 31 0 0 0 2 15.4a3 3 0 0 0 2.1 2.1C6 18 12 18 12 18s6 0 7.9-.5a3 3 0 0 0 2.1-2.1 31 31 0 0 0 .5-3.4 31 31 0 0 0-.5-3.4z"/><path d="m10 15 5-3-5-3z"/>',
		'facebook' => '<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>',
		'tiktok'   => '<path d="M9 12a4 4 0 1 0 4 4V2h4a5 5 0 0 0 4 4"/>',
		'telegram' => '<path d="m21 4-18 7 6 2 2 6 3-4 5 4z"/><path d="m9 13 12-9"/>',
		'email'    => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/>',
		'arrow-up' => '<path d="M12 19V5M5 12l7-7 7 7"/>',
		'arrow-right' => '<path d="M5 12h14M12 5l7 7-7 7"/>',
	);

	if ( ! isset( $icons[ $name ] ) ) {
		return '';
	}

	return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $icons[ $name ] . '</svg>';
}

/**
 * Minutos estimados de leitura (conforme a velocidade definida em Root WP > Artigos).
 *
 * @param int|null $post_id ID do post.
 * @return int
 */
function rootwp_reading_minutes( $post_id = null ) {
	$content = get_post_field( 'post_content', $post_id ? $post_id : get_the_ID() );
	// preg_split em vez de str_word_count para contar corretamente palavras acentuadas.
	$words = count( preg_split( '/\s+/u', trim( wp_strip_all_tags( strip_shortcodes( $content ) ) ), -1, PREG_SPLIT_NO_EMPTY ) );
	$wpm   = max( 100, (int) rootwp_panel_option( 'reading_wpm' ) );
	return max( 1, (int) ceil( $words / $wpm ) );
}

/**
 * Texto do tempo estimado de leitura, ex.: "5 min de leitura".
 *
 * @param int|null $post_id ID do post.
 * @return string
 */
function rootwp_reading_time( $post_id = null ) {
	$minutes = rootwp_reading_minutes( $post_id );

	/* translators: %d: minutos de leitura */
	return sprintf( _n( '%d min de leitura', '%d min de leitura', $minutes, 'rootwp' ), $minutes );
}

/**
 * Exibe a primeira categoria como selo.
 */
function rootwp_category_badge() {
	$categories = get_the_category();
	if ( empty( $categories ) ) {
		return;
	}
	printf(
		'<a class="badge" href="%1$s"%3$s>%2$s</a>',
		esc_url( get_category_link( $categories[0]->term_id ) ),
		esc_html( $categories[0]->name ),
		rootwp_category_style_attr( $categories[0]->term_id ) // phpcs:ignore WordPress.Security.EscapeOutput
	);
}

/**
 * Metadados do post: data, autor e tempo de leitura.
 *
 * @param bool $show_author Exibir autor.
 * @param bool $icons       Ícones (pessoa, calendário, relógio) no lugar dos separadores "•".
 */
function rootwp_entry_meta( $show_author = true, $icons = false ) {
	$sep  = $icons ? '' : '<span class="sep" aria-hidden="true">•</span>';
	$icon = function ( $name ) use ( $icons ) {
		return $icons ? rootwp_icon( $name ) : '';
	};

	echo '<div class="entry-meta' . ( $icons ? ' entry-meta--icons' : '' ) . '">';

	if ( $show_author ) {
		printf(
			'<span class="byline">%3$s<a href="%1$s">%2$s</a></span>%4$s',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_html( get_the_author() ),
			$icon( 'user' ), // phpcs:ignore WordPress.Security.EscapeOutput
			$sep // phpcs:ignore WordPress.Security.EscapeOutput
		);
	}

	printf(
		'<span class="posted-on">%3$s<time datetime="%1$s">%2$s</time></span>',
		esc_attr( get_the_date( DATE_W3C ) ),
		esc_html( get_the_date() ),
		$icon( 'calendar' ) // phpcs:ignore WordPress.Security.EscapeOutput
	);

	if ( rootwp_panel_option( 'reading_time' ) ) {
		printf(
			'%2$s<span class="reading-time">%3$s%1$s</span>',
			esc_html( rootwp_reading_time() ),
			$sep, // phpcs:ignore WordPress.Security.EscapeOutput
			$icon( 'clock' ) // phpcs:ignore WordPress.Security.EscapeOutput
		);
	}

	echo '</div>';
}

/**
 * Trilha de navegação (breadcrumbs) simples.
 */
function rootwp_breadcrumbs() {
	if ( is_front_page() ) {
		return;
	}

	$items   = array();
	$items[] = '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Início', 'rootwp' ) . '</a>';

	$blog_page = (int) get_option( 'page_for_posts' );
	if ( $blog_page && ( is_singular( 'post' ) || is_archive() ) ) {
		$items[] = '<a href="' . esc_url( get_permalink( $blog_page ) ) . '">' . esc_html( get_the_title( $blog_page ) ) . '</a>';
	}

	if ( is_singular( 'post' ) ) {
		$categories = get_the_category();
		if ( ! empty( $categories ) ) {
			$items[] = '<a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '">' . esc_html( $categories[0]->name ) . '</a>';
		}
	}

	echo '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Trilha de navegação', 'rootwp' ) . '">';
	echo implode( ' <span aria-hidden="true">/</span> ', $items ); // phpcs:ignore WordPress.Security.EscapeOutput -- já escapado acima.
	echo '</nav>';
}

/**
 * Paginação numérica.
 */
function rootwp_pagination() {
	the_posts_pagination(
		array(
			'mid_size'           => 1,
			'prev_text'          => '&larr; <span class="screen-reader-text">' . __( 'Anterior', 'rootwp' ) . '</span>',
			'next_text'          => '<span class="screen-reader-text">' . __( 'Próxima', 'rootwp' ) . '</span> &rarr;',
			'screen_reader_text' => __( 'Navegação de posts', 'rootwp' ),
		)
	);
}

/**
 * Redes disponíveis nos botões de compartilhar.
 *
 * @return array rede => array( label, default ).
 */
function rootwp_share_networks() {
	return array(
		'linkedin' => array( 'label' => 'LinkedIn', 'default' => 1 ),
		'whatsapp' => array( 'label' => 'WhatsApp', 'default' => 1 ),
		'x'        => array( 'label' => 'X (Twitter)', 'default' => 1 ),
		'facebook' => array( 'label' => 'Facebook', 'default' => 0 ),
		'telegram' => array( 'label' => 'Telegram', 'default' => 0 ),
		'email'    => array( 'label' => 'E-mail', 'default' => 0 ),
		'copy'     => array( 'label' => __( 'Copiar link', 'rootwp' ), 'default' => 1 ),
	);
}

/**
 * Botões de compartilhamento, conforme Tema > Artigos.
 */
function rootwp_share_links() {
	$url   = rawurlencode( get_permalink() );
	$title = rawurlencode( get_the_title() );
	$urls  = array(
		'linkedin' => 'https://www.linkedin.com/sharing/share-offsite/?url=' . $url,
		'whatsapp' => 'https://api.whatsapp.com/send?text=' . $title . '%20' . $url,
		'x'        => 'https://x.com/intent/post?url=' . $url . '&text=' . $title,
		'facebook' => 'https://www.facebook.com/sharer/sharer.php?u=' . $url,
		'telegram' => 'https://t.me/share/url?url=' . $url . '&text=' . $title,
		'email'    => 'mailto:?subject=' . $title . '&body=' . $url,
	);

	$buttons = '';
	foreach ( rootwp_share_networks() as $network => $data ) {
		if ( ! rootwp_panel_option( 'share_' . $network ) ) {
			continue;
		}

		if ( 'copy' === $network ) {
			$buttons .= sprintf(
				'<button type="button" class="icon-btn social--copy js-copy-link" data-url="%1$s" aria-label="%2$s">%3$s</button>',
				esc_url( get_permalink() ),
				esc_attr__( 'Copiar link', 'rootwp' ),
				rootwp_icon( 'link' )
			);
			continue;
		}

		$buttons .= sprintf(
			'<a class="icon-btn social--%5$s" href="%1$s"%2$s aria-label="%3$s">%4$s</a>',
			esc_url( $urls[ $network ], array( 'https', 'mailto' ) ),
			'email' === $network ? '' : ' target="_blank" rel="noopener noreferrer"',
			/* translators: %s: nome da rede social */
			esc_attr( sprintf( __( 'Compartilhar no %s', 'rootwp' ), $data['label'] ) ),
			rootwp_icon( $network ),
			esc_attr( $network )
		);
	}

	if ( $buttons ) {
		echo '<div class="share"><span>' . esc_html__( 'Compartilhar:', 'rootwp' ) . '</span>' . $buttons . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput
	}
}

/**
 * Redes sociais que cada autor pode preencher no próprio perfil
 * (Usuários > Perfil > Informações de contato).
 *
 * @return array rede => rótulo.
 */
function rootwp_author_networks() {
	return array(
		'linkedin'  => 'LinkedIn',
		'github'    => 'GitHub',
		'instagram' => 'Instagram',
		'youtube'   => 'YouTube',
		'x'         => 'X (Twitter)',
		'facebook'  => 'Facebook',
		'tiktok'    => 'TikTok',
	);
}

/**
 * Adiciona os campos de redes ao perfil do usuário.
 *
 * @param array $methods Campos de contato.
 * @return array
 */
function rootwp_author_contact_methods( $methods ) {
	foreach ( rootwp_author_networks() as $network => $label ) {
		/* translators: %s: nome da rede social */
		$methods[ 'rootwp_' . $network ] = sprintf( __( '%s (link completo)', 'rootwp' ), $label );
	}
	return $methods;
}
add_filter( 'user_contactmethods', 'rootwp_author_contact_methods' );

/**
 * Caixa com informações do autor: foto, nome, bio, redes e link para os artigos.
 */
function rootwp_author_box() {
	$author_id = get_the_author_meta( 'ID' );
	$bio       = get_the_author_meta( 'description', $author_id );
	$website   = get_the_author_meta( 'user_url', $author_id );
	$count     = count_user_posts( $author_id, 'post', true );
	?>
	<aside class="author-box" aria-label="<?php esc_attr_e( 'Sobre o autor', 'rootwp' ); ?>">
		<div class="author-box__avatar"><?php echo get_avatar( $author_id, 96 ); ?></div>
		<div class="author-box__body">
			<span class="author-box__label"><?php esc_html_e( 'Escrito por', 'rootwp' ); ?></span>
			<p class="author-box__name"><a href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>"><?php the_author(); ?></a></p>

			<?php if ( $bio ) : ?>
				<p class="author-box__bio"><?php echo nl2br( esc_html( $bio ) ); ?></p>
			<?php elseif ( current_user_can( 'edit_user', $author_id ) ) : ?>
				<?php // Lembrete visível só para quem pode editar o perfil; visitantes não veem nada. ?>
				<p class="author-box__bio author-box__bio--empty">
					<?php esc_html_e( 'Espaço para a sua bio.', 'rootwp' ); ?>
					<a href="<?php echo esc_url( get_edit_user_link( $author_id ) . '#description' ); ?>"><?php esc_html_e( 'Escrever em Usuários > Perfil', 'rootwp' ); ?></a>
				</p>
			<?php endif; ?>

			<div class="author-box__footer">
				<a class="author-box__more" href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>">
					<?php
					/* translators: %d: quantidade de artigos */
					echo esc_html( sprintf( _n( 'Ver %d artigo', 'Ver os %d artigos', $count, 'rootwp' ), $count ) );
					echo rootwp_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput
					?>
				</a>
				<?php if ( $website ) : ?>
					<a class="author-box__site" href="<?php echo esc_url( $website ); ?>" rel="noopener" target="_blank"><?php echo esc_html( preg_replace( '#^https?://(www\.)?#', '', untrailingslashit( $website ) ) ); ?></a>
				<?php endif; ?>
				<?php rootwp_author_social_links( $author_id, 'author-box__social' ); ?>
			</div>
		</div>
	</aside>
	<?php
}

/**
 * Logo do site (texto ou imagem), conforme o painel do tema.
 */
function rootwp_site_logo() {
	$image = (int) rootwp_panel_option( 'logo_image' );
	$name  = rootwp_panel_option( 'logo_text' );
	$name  = $name ? $name : get_bloginfo( 'name' );
	// O logo só é o h1 na página inicial estática (front-page.php). Quando a home é a
	// lista de posts, o index.php já tem o próprio h1 e a página não pode ter dois.
	$tag   = is_front_page() && 'page' === get_option( 'show_on_front' ) ? 'h1' : 'p';
	?>
	<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
		<?php if ( 'image' === rootwp_panel_option( 'logo_type' ) && $image ) : ?>
			<?php
			echo wp_get_attachment_image(
				$image,
				'full',
				false,
				array(
					'class'   => 'site-logo__img',
					'alt'     => get_bloginfo( 'name' ),
					'loading' => 'eager',
					'style'   => 'max-height:' . (int) rootwp_panel_option( 'logo_height' ) . 'px',
				)
			);
			if ( 'h1' === $tag ) {
				echo '<h1 class="screen-reader-text">' . esc_html( get_bloginfo( 'name' ) ) . '</h1>';
			}
			?>
		<?php else : ?>
			<<?php echo $tag; // phpcs:ignore WordPress.Security.EscapeOutput ?> class="site-title"><?php echo esc_html( $name ); ?></<?php echo $tag; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
		<?php endif; ?>
	</a>
	<?php if ( rootwp_panel_option( 'logo_show_tagline' ) && get_bloginfo( 'description' ) ) : ?>
		<p class="site-description"><?php bloginfo( 'description' ); ?></p>
	<?php endif; ?>
	<?php
}

/**
 * Ícones das redes sociais configuradas no painel.
 */
function rootwp_social_links() {
	$items = '';
	foreach ( rootwp_social_networks() as $network => $label ) {
		$value = rootwp_panel_option( 'social_' . $network );
		if ( ! $value ) {
			continue;
		}
		$href   = 'email' === $network ? 'mailto:' . antispambot( $value ) : $value;
		$items .= sprintf(
			'<li><a class="icon-btn social--%5$s" href="%1$s" aria-label="%2$s"%3$s>%4$s</a></li>',
			esc_url( $href, array( 'http', 'https', 'mailto' ) ),
			esc_attr( $label ),
			'email' === $network ? '' : ' target="_blank" rel="noopener me"',
			rootwp_icon( $network ),
			esc_attr( $network )
		);
	}

	if ( $items ) {
		echo '<ul class="social-links">' . $items . '</ul>'; // phpcs:ignore WordPress.Security.EscapeOutput
	}
}

/**
 * Posts relacionados (mesma categoria).
 *
 * @param int $count Quantidade.
 * @return WP_Query
 */
function rootwp_related_posts_query( $count = 3 ) {
	$args = array(
		'post__not_in'        => array( get_the_ID() ),
		'posts_per_page'      => $count,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	);

	$tags = wp_get_post_tags( get_the_ID(), array( 'fields' => 'ids' ) );
	if ( 'tag' === rootwp_panel_option( 'related_by' ) && $tags ) {
		$args['tag__in'] = $tags;
	} else {
		$args['category__in'] = wp_get_post_categories( get_the_ID() );
	}

	return new WP_Query( $args );
}

/**
 * Aviso de conteúdo possivelmente desatualizado (Tema > Artigos).
 */
function rootwp_old_post_notice() {
	if ( ! rootwp_panel_option( 'old_notice' ) ) {
		return;
	}

	$modified = (int) get_post_modified_time( 'U', true );
	$limit    = strtotime( '-' . (int) rootwp_panel_option( 'old_notice_months' ) . ' months' );
	if ( ! $modified || $modified > $limit ) {
		return;
	}

	$text = str_replace( '{tempo}', human_time_diff( $modified ), rootwp_panel_option( 'old_notice_text' ) );
	?>
	<div class="old-post-notice" role="note">
		<?php echo rootwp_icon( 'clock' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<p><?php echo esc_html( $text ); ?></p>
	</div>
	<?php
}
