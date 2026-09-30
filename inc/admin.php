<?php
/**
 * Página de administração do tema (menu próprio no painel).
 *
 * @package rootwp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Redes sociais disponíveis no rodapé.
 *
 * @return array
 */
function rootwp_social_networks() {
	return array(
		'linkedin'  => 'LinkedIn',
		'github'    => 'GitHub',
		'instagram' => 'Instagram',
		'youtube'   => 'YouTube',
		'facebook'  => 'Facebook',
		'tiktok'    => 'TikTok',
		'whatsapp'  => 'WhatsApp',
		'x'         => 'X (Twitter)',
		'email'     => 'E-mail',
	);
}

/**
 * Valores padrão do painel do tema.
 *
 * @return array
 */
function rootwp_panel_defaults() {
	$defaults = array(
		'logo_type'           => 'text',
		'logo_text'           => '',
		'logo_image'          => 0,
		'logo_height'         => 44,
		'logo_show_tagline'   => 1,
		'favicon'             => 0,
		'primary_color'       => '#2f4bd8',
		'accent_color'        => '#f5a524',
		'light_bg'            => '#ffffff',
		'light_surface'       => '#f6f7fb',
		'light_text'          => '#1c2033',
		'dark_bg'             => '#0e1120',
		'dark_surface'        => '#161a2e',
		'dark_text'           => '#e6e8f2',
		'dark_mode_default'   => 'auto',
		'dark_mode_toggle'    => 1,
		'article_font_size'   => 'medium',
		'content_width'       => 'normal',
		'sidebar_archive'     => 1,
		'sidebar_single'      => 0,
		'header_sticky'       => 1,
		'header_show_search'  => 1,
		'header_cta_text'     => '',
		'header_cta_url'      => '',
		'header_cta_style'    => 'solid',
		'header_cta_new_tab'  => 0,
		'toc_enabled'         => 1,
		'toc_levels'          => 'h2h3',
		'toc_min'             => 3,
		'toc_title'           => __( 'Neste artigo', 'rootwp' ),
		'reading_time'        => 1,
		'reading_wpm'         => 200,
		'progress_bar'        => 1,
		'author_box'          => 1,
		'share_enabled'       => 1,
		'related_enabled'     => 1,
		'related_count'       => 3,
		'related_by'          => 'category',
		'related_title'       => __( 'Continue aprendendo', 'rootwp' ),
		'old_notice'          => 0,
		'code_copy'           => 1,
		'series_enabled'      => 1,
		'reading_remaining'   => 1,
		'font_resize'         => 1,
		'old_notice_months'   => 24,
		'old_notice_text'     => __( 'Este artigo foi atualizado pela última vez há {tempo}. Algumas informações podem estar desatualizadas.', 'rootwp' ),
		'notice_enabled'        => 0,
		'notice_text'           => '',
		'notice_link_text'      => '',
		'notice_link_url'       => '',
		'notice_new_tab'        => 0,
		'notice_style'          => 'primary',
		'notice_dismissible'    => 1,
		'cta_enabled'           => 0,
		'cta_title'             => '',
		'cta_text'              => '',
		'cta_button_text'       => '',
		'cta_button_url'        => '',
		'cta_new_tab'           => 0,
		'cta_image'             => 0,
		'cta_exclude'           => '',
		'seo_enabled'           => 1,
		'seo_home_description'  => '',
		'seo_default_image'     => 0,
		'seo_twitter'           => '',
		'analytics_id'          => '',
		'analytics_skip_admins' => 1,
		'code_head'             => '',
		'code_body_open'        => '',
		'code_footer'           => '',
		'footer_about_title'  => '',
		'footer_about'        => '',
		'footer_text'         => get_theme_mod( 'footer_text', '' ),
		'footer_show_widgets' => 1,
		'footer_show_menu'    => 1,
		'footer_show_social'  => 1,
		'footer_back_to_top'  => 1,
	);

	// Página inicial: os padrões vêm do que estava salvo no Personalizar (rootwp_option),
	// assim quem já tinha configurado não perde nada na migração para o painel.
	$legacy_count = (int) rootwp_option( 'home_posts_count' );
	$defaults    += array(
		'hero_eyebrow'           => rootwp_option( 'hero_eyebrow' ),
		'hero_title'             => rootwp_option( 'hero_title' ),
		'hero_text'              => rootwp_option( 'hero_text' ),
		'hero_image'             => (int) rootwp_option( 'hero_image' ),
		'hero_button_text'       => rootwp_option( 'hero_button_text' ),
		'hero_button_url'        => rootwp_option( 'hero_button_url' ),
		'hero_button_new_tab'    => 0,
		'hero_button2_text'      => rootwp_option( 'hero_button2_text' ),
		'hero_button2_url'       => rootwp_option( 'hero_button2_url' ),
		'hero_button2_new_tab'   => 0,
		'home_recent_enabled'    => $legacy_count > 0 ? 1 : 0,
		'home_posts_count'       => $legacy_count > 0 ? min( 12, $legacy_count ) : 6,
		'home_recent_title'      => __( 'Artigos recentes', 'rootwp' ),
		'home_recent_subtitle'   => __( 'O que publiquei por último sobre tecnologia e educação.', 'rootwp' ),
		'home_recent_button'     => __( 'Ver todos', 'rootwp' ),
		'home_recent_category'   => 0,
		'show_topics'            => rootwp_option( 'show_topics' ) ? 1 : 0,
		'home_topics_title'      => __( 'Explore por tema', 'rootwp' ),
		'home_topics_subtitle'   => __( 'Encontre conteúdos organizados por assunto.', 'rootwp' ),
		'home_topics_mode'       => 'auto',
		'home_topics_number'     => 8,
		'home_topics_selected'   => '',
		'home_topics_show_count' => 1,
		'home_sections_order'    => 'content,recent,topics',
	);

	foreach ( array_keys( rootwp_social_networks() ) as $network ) {
		$defaults[ 'social_' . $network ] = '';
	}

	foreach ( rootwp_share_networks() as $network => $data ) {
		$defaults[ 'share_' . $network ] = $data['default'];
	}

	return $defaults;
}

/**
 * Lê uma opção do painel do tema.
 *
 * @param string $key Nome da opção.
 * @return mixed
 */
function rootwp_panel_option( $key ) {
	$options  = get_option( 'rootwp_options', array() );
	$defaults = rootwp_panel_defaults();

	if ( is_array( $options ) && array_key_exists( $key, $options ) ) {
		return $options[ $key ];
	}

	return isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';
}

/**
 * Abas do painel: slug => página, rótulo, função de renderização e checkboxes.
 *
 * @return array
 */
function rootwp_panel_tabs() {
	return array(
		'logo'       => array(
			'page'   => 'rootwp',
			'label'  => __( 'Logo e favicon', 'rootwp' ),
			'render' => 'rootwp_render_logo_tab',
			'flags'  => array( 'logo_show_tagline' ),
		),
		'home'       => array(
			'page'   => 'rootwp-home',
			'label'  => __( 'Página inicial', 'rootwp' ),
			'render' => 'rootwp_render_home_tab',
			'flags'  => array( 'hero_button_new_tab', 'hero_button2_new_tab', 'home_recent_enabled', 'show_topics', 'home_topics_show_count' ),
		),
		'appearance' => array(
			'page'   => 'rootwp-appearance',
			'label'  => __( 'Aparência', 'rootwp' ),
			'render' => 'rootwp_render_appearance_tab',
			'flags'  => array( 'dark_mode_toggle', 'sidebar_archive', 'sidebar_single' ),
		),
		'colors'     => array(
			'page'   => 'rootwp-colors',
			'label'  => __( 'Cores', 'rootwp' ),
			'render' => 'rootwp_render_colors_tab',
			'flags'  => array(),
		),
		'header'     => array(
			'page'   => 'rootwp-header',
			'label'  => __( 'Cabeçalho', 'rootwp' ),
			'render' => 'rootwp_render_header_tab',
			'flags'  => array( 'header_sticky', 'header_show_search', 'header_cta_new_tab' ),
		),
		'articles'   => array(
			'page'   => 'rootwp-articles',
			'label'  => __( 'Artigos', 'rootwp' ),
			'render' => 'rootwp_render_articles_tab',
			'flags'  => array_merge(
				array( 'toc_enabled', 'reading_time', 'progress_bar', 'author_box', 'share_enabled', 'related_enabled', 'old_notice', 'code_copy', 'series_enabled', 'reading_remaining', 'font_resize' ),
				array_map(
					function ( $network ) {
						return 'share_' . $network;
					},
					array_keys( rootwp_share_networks() )
				)
			),
		),
		'notices'    => array(
			'page'   => 'rootwp-notices',
			'label'  => __( 'Avisos', 'rootwp' ),
			'render' => 'rootwp_render_notices_tab',
			'flags'  => array( 'notice_enabled', 'notice_new_tab', 'notice_dismissible', 'cta_enabled', 'cta_new_tab' ),
		),
		'seo'        => array(
			'page'   => 'rootwp-seo',
			'label'  => __( 'SEO e integrações', 'rootwp' ),
			'render' => 'rootwp_render_seo_tab',
			'flags'  => array( 'seo_enabled', 'analytics_skip_admins' ),
		),
		'tools'      => array(
			'page'   => 'rootwp-tools',
			'label'  => __( 'Ferramentas', 'rootwp' ),
			'render' => 'rootwp_render_tools_tab',
			'flags'  => array(),
			'form'   => false, // Tem formulários próprios (exportar, importar, restaurar).
		),
		'footer'     => array(
			'page'   => 'rootwp-footer',
			'label'  => __( 'Rodapé', 'rootwp' ),
			'render' => 'rootwp_render_footer_tab',
			'flags'  => array( 'footer_show_widgets', 'footer_show_menu', 'footer_show_social', 'footer_back_to_top' ),
		),
	);
}

/**
 * Registra o menu do tema.
 */
function rootwp_admin_menu() {
	add_menu_page(
		__( 'Root WP', 'rootwp' ),
		__( 'Root WP', 'rootwp' ),
		'edit_theme_options',
		'rootwp',
		'rootwp_render_panel',
		'dashicons-art',
		'59.6' // logo acima de Aparência (60); o CodePress, se ativo, fica em 59.5
	);

	foreach ( rootwp_panel_tabs() as $tab ) {
		add_submenu_page( 'rootwp', $tab['label'], $tab['label'], 'edit_theme_options', $tab['page'], 'rootwp_render_panel' );
	}
}
add_action( 'admin_menu', 'rootwp_admin_menu' );

/**
 * Tira o separador que o WordPress coloca na posição 59 (entre Comentários e
 * Aparência), para Root WP e CodePress ficarem em sequência com Aparência.
 */
function rootwp_remove_menu_separator() {
	global $menu;
	if ( isset( $menu[59] ) && isset( $menu[59][4] ) && false !== strpos( $menu[59][4], 'wp-menu-separator' ) ) {
		unset( $menu[59] );
	}
}
add_action( 'admin_menu', 'rootwp_remove_menu_separator', 999 );


/**
 * Registra a opção e sua sanitização.
 */
function rootwp_register_settings() {
	register_setting(
		'rootwp_options',
		'rootwp_options',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'rootwp_sanitize_options',
		)
	);
}
add_action( 'admin_init', 'rootwp_register_settings' );

/**
 * Sanitiza as opções. Cada aba envia só os seus campos, então
 * mesclamos com o que já está salvo para não perder a outra aba.
 *
 * @param array $input Dados enviados.
 * @return array
 */
function rootwp_sanitize_options( $input ) {
	$saved = get_option( 'rootwp_options', array() );
	$saved = is_array( $saved ) ? $saved : array();
	$input = is_array( $input ) ? $input : array();
	$tabs  = rootwp_panel_tabs();
	$flags = array_merge( ...array_values( wp_list_pluck( $tabs, 'flags' ) ) );
	$clean = array();

	// Checkbox desmarcado não é enviado: zera os da aba que foi salva.
	if ( isset( $input['_tab'], $tabs[ $input['_tab'] ] ) ) {
		foreach ( $tabs[ $input['_tab'] ]['flags'] as $flag ) {
			$clean[ $flag ] = empty( $input[ $flag ] ) ? 0 : 1;
		}
	}

	foreach ( array_keys( rootwp_panel_defaults() ) as $key ) {
		if ( ! array_key_exists( $key, $input ) ) {
			continue;
		}
		$value = is_string( $input[ $key ] ) ? trim( $input[ $key ] ) : $input[ $key ];

		switch ( $key ) {
			case 'logo_type':
				$clean[ $key ] = 'image' === $value ? 'image' : 'text';
				break;
			case 'logo_image':
			case 'favicon':
				$clean[ $key ] = absint( $value );
				break;
			case 'logo_height':
				$clean[ $key ] = min( 120, max( 20, absint( $value ) ) );
				break;
			case 'primary_color':
			case 'accent_color':
			case 'light_bg':
			case 'light_surface':
			case 'light_text':
			case 'dark_bg':
			case 'dark_surface':
			case 'dark_text':
				$color         = sanitize_hex_color( $value );
				$clean[ $key ] = $color ? strtolower( $color ) : rootwp_color_defaults()[ $key ];
				break;
			case 'dark_mode_default':
				$clean[ $key ] = in_array( $value, array( 'auto', 'light', 'dark' ), true ) ? $value : 'auto';
				break;
			case 'article_font_size':
				$clean[ $key ] = in_array( $value, array( 'small', 'medium', 'large' ), true ) ? $value : 'medium';
				break;
			case 'content_width':
				$clean[ $key ] = in_array( $value, array( 'narrow', 'normal', 'wide' ), true ) ? $value : 'normal';
				break;
			case 'header_cta_style':
				$clean[ $key ] = 'outline' === $value ? 'outline' : 'solid';
				break;
			case 'notice_link_url':
			case 'cta_button_url':
				$clean[ $key ] = esc_url_raw( $value );
				break;
			case 'notice_style':
				$clean[ $key ] = in_array( $value, array( 'primary', 'accent', 'dark', 'soft' ), true ) ? $value : 'primary';
				break;
			case 'cta_text':
				$clean[ $key ] = sanitize_textarea_field( $value );
				break;
			case 'cta_image':
				$clean[ $key ] = absint( $value );
				break;
			case 'cta_exclude':
				$clean[ $key ] = implode( ',', array_filter( array_map( 'absint', (array) ( is_array( $value ) ? $value : explode( ',', (string) $value ) ) ) ) );
				break;
			case 'seo_home_description':
				$clean[ $key ] = sanitize_textarea_field( $value );
				break;
			case 'seo_default_image':
				$clean[ $key ] = absint( $value );
				break;
			case 'seo_twitter':
				$clean[ $key ] = preg_replace( '/[^A-Za-z0-9_]/', '', $value );
				break;
			case 'analytics_id':
				$clean[ $key ] = strtoupper( preg_replace( '/[^A-Za-z0-9-]/', '', $value ) );
				break;
			case 'code_head':
			case 'code_body_open':
			case 'code_footer':
				// Scripts só podem ser salvos por quem tem permissão para HTML livre
				// (administrador em site único). Sem ela, mantém o valor anterior.
				$clean[ $key ] = current_user_can( 'unfiltered_html' ) ? $value : ( isset( $saved[ $key ] ) ? $saved[ $key ] : '' );
				break;
			case 'hero_text':
				$clean[ $key ] = sanitize_textarea_field( $value );
				break;
			case 'hero_image':
			case 'home_recent_category':
				$clean[ $key ] = absint( $value );
				break;
			case 'hero_button_url':
			case 'hero_button2_url':
				$clean[ $key ] = esc_url_raw( $value );
				break;
			case 'home_posts_count':
			case 'home_topics_number':
				$clean[ $key ] = min( 12, max( 1, absint( $value ) ) );
				break;
			case 'home_topics_mode':
				$clean[ $key ] = 'manual' === $value ? 'manual' : 'auto';
				break;
			case 'home_topics_selected':
				// Lista de IDs de categoria na ordem escolhida.
				$ids           = array_filter( array_map( 'absint', explode( ',', (string) $value ) ) );
				$clean[ $key ] = implode( ',', array_unique( $ids ) );
				break;
			case 'home_sections_order':
				$allowed       = array( 'content', 'recent', 'topics' );
				$order         = array_values( array_intersect( array_unique( explode( ',', (string) $value ) ), $allowed ) );
				$clean[ $key ] = implode( ',', array_merge( $order, array_diff( $allowed, $order ) ) );
				break;
			case 'toc_levels':
				$clean[ $key ] = 'h2' === $value ? 'h2' : 'h2h3';
				break;
			case 'related_by':
				$clean[ $key ] = 'tag' === $value ? 'tag' : 'category';
				break;
			case 'toc_min':
			case 'reading_wpm':
			case 'related_count':
			case 'old_notice_months':
				$ranges        = array(
					'toc_min'           => array( 2, 10 ),
					'reading_wpm'       => array( 100, 400 ),
					'related_count'     => array( 1, 6 ),
					'old_notice_months' => array( 3, 120 ),
				);
				$clean[ $key ] = min( $ranges[ $key ][1], max( $ranges[ $key ][0], absint( $value ) ) );
				break;
			case 'old_notice_text':
				$clean[ $key ] = sanitize_textarea_field( $value );
				break;
			case 'header_cta_url':
				$clean[ $key ] = esc_url_raw( $value );
				break;
			case 'footer_about':
				$clean[ $key ] = sanitize_textarea_field( $value );
				break;
			case 'footer_text':
				$clean[ $key ] = wp_kses_post( $value );
				break;
			case 'social_email':
				$clean[ $key ] = sanitize_email( $value );
				break;
			default:
				if ( 0 === strpos( $key, 'social_' ) ) {
					$clean[ $key ] = esc_url_raw( $value );
				} elseif ( in_array( $key, $flags, true ) ) {
					$clean[ $key ] = empty( $value ) ? 0 : 1;
				} else {
					$clean[ $key ] = sanitize_text_field( $value );
				}
		}
	}

	return array_merge( $saved, $clean );
}

/**
 * Scripts do painel (seletor de mídia).
 *
 * @param string $hook Página atual.
 */
function rootwp_admin_assets( $hook ) {
	// Painel do tema e telas de categoria (cor e imagem de capa usam os mesmos seletores).
	$is_category = in_array( $hook, array( 'edit-tags.php', 'term.php' ), true ) && isset( $_GET['taxonomy'] ) && 'category' === $_GET['taxonomy']; // phpcs:ignore WordPress.Security.NonceVerification
	if ( false === strpos( $hook, 'rootwp' ) && ! $is_category ) {
		return;
	}

	wp_enqueue_media();
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_style( 'rootwp-admin', get_template_directory_uri() . '/assets/css/admin.css', array(), ROOTWP_VERSION );
	wp_enqueue_script( 'rootwp-admin', get_template_directory_uri() . '/assets/js/admin.js', array( 'jquery', 'jquery-ui-sortable', 'wp-color-picker' ), ROOTWP_VERSION, true );
	wp_localize_script(
		'rootwp-admin',
		'rootwpAdmin',
		array(
			'title'    => __( 'Escolher logo', 'rootwp' ),
			'button'   => __( 'Usar esta imagem', 'rootwp' ),
			'palettes' => wp_list_pluck( rootwp_color_palettes(), 'colors' ),
			'defaults' => rootwp_color_defaults(),
			'contrast' => array(
				'good' => __( 'boa leitura', 'rootwp' ),
				'ok'   => __( 'só para textos grandes', 'rootwp' ),
				'bad'  => __( 'contraste baixo', 'rootwp' ),
			),
		)
	);
}

/**
 * Aba "Cores".
 */
function rootwp_render_colors_tab() {
	$fields = array(
		'light' => array(
			'title'  => __( 'Modo claro', 'rootwp' ),
			'fields' => array(
				'primary_color' => array( __( 'Principal', 'rootwp' ), __( 'Links, botões, bubbles e destaques. No modo escuro é clareada automaticamente.', 'rootwp' ) ),
				'accent_color'  => array( __( 'Destaque', 'rootwp' ), __( 'Detalhes secundários: barra de progresso de leitura e box "Atenção".', 'rootwp' ) ),
				'light_bg'      => array( __( 'Fundo', 'rootwp' ), '' ),
				'light_surface' => array( __( 'Superfície', 'rootwp' ), __( 'Fundo de cartões, caixa do autor, rodapé e seções alternadas.', 'rootwp' ) ),
				'light_text'    => array( __( 'Texto', 'rootwp' ), '' ),
			),
			'checks' => array(
				'light-text'   => __( 'Texto sobre o fundo', 'rootwp' ),
				'light-link'   => __( 'Links sobre o fundo', 'rootwp' ),
				'light-button' => __( 'Texto dos botões', 'rootwp' ),
			),
		),
		'dark'  => array(
			'title'  => __( 'Modo escuro', 'rootwp' ),
			'fields' => array(
				'dark_bg'      => array( __( 'Fundo', 'rootwp' ), '' ),
				'dark_surface' => array( __( 'Superfície', 'rootwp' ), '' ),
				'dark_text'    => array( __( 'Texto', 'rootwp' ), '' ),
			),
			'checks' => array(
				'dark-text'   => __( 'Texto sobre o fundo', 'rootwp' ),
				'dark-link'   => __( 'Links sobre o fundo', 'rootwp' ),
				'dark-button' => __( 'Texto dos botões', 'rootwp' ),
			),
		),
	);
	?>
	<div class="rootwp-card">
		<h2><?php esc_html_e( 'Paletas prontas', 'rootwp' ); ?></h2>
		<p class="description"><?php esc_html_e( 'Combinações testadas nos modos claro e escuro. Clique para aplicar e depois ajuste o que quiser; nada muda no site até você salvar.', 'rootwp' ); ?></p>
		<div class="rootwp-palettes">
			<?php foreach ( rootwp_color_palettes() as $key => $palette ) : ?>
				<button type="button" class="rootwp-palette" data-palette="<?php echo esc_attr( $key ); ?>">
					<span class="rootwp-palette__swatches">
						<?php foreach ( array( 'primary_color', 'accent_color', 'light_surface', 'light_text', 'dark_bg' ) as $swatch ) : ?>
							<span style="background: <?php echo esc_attr( $palette['colors'][ $swatch ] ); ?>"></span>
						<?php endforeach; ?>
					</span>
					<span class="rootwp-palette__name"><?php echo esc_html( $palette['label'] ); ?></span>
				</button>
			<?php endforeach; ?>
		</div>
	</div>

	<div class="rootwp-colors">
		<div class="rootwp-colors__fields">
			<?php foreach ( $fields as $mode => $group ) : ?>
				<div class="rootwp-card">
					<h2><?php echo esc_html( $group['title'] ); ?></h2>
					<table class="form-table" role="presentation">
						<?php foreach ( $group['fields'] as $key => $field ) : ?>
							<tr>
								<th scope="row"><label for="as-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field[0] ); ?></label></th>
								<td>
									<input type="text" id="as-<?php echo esc_attr( $key ); ?>" class="rootwp-color-live" data-key="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( rootwp_field_name( $key ) ); ?>" value="<?php echo esc_attr( rootwp_color( $key ) ); ?>" data-default-color="<?php echo esc_attr( rootwp_color_defaults()[ $key ] ); ?>">
									<?php if ( $field[1] ) : ?>
										<p class="description"><?php echo esc_html( $field[1] ); ?></p>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</table>
					<ul class="rootwp-contrast">
						<?php foreach ( $group['checks'] as $check => $label ) : ?>
							<li data-check="<?php echo esc_attr( $check ); ?>"><span><?php echo esc_html( $label ); ?></span> <strong></strong></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endforeach; ?>

			<p>
				<button type="button" class="button rootwp-colors-reset"><?php esc_html_e( 'Restaurar cores padrão', 'rootwp' ); ?></button>
				<span class="description"><?php esc_html_e( 'Volta para a paleta Azul. Clique em "Salvar alterações" para aplicar.', 'rootwp' ); ?></span>
			</p>
		</div>

		<div class="rootwp-colors__preview">
			<div class="rootwp-card rootwp-sticky">
				<h2><?php esc_html_e( 'Prévia', 'rootwp' ); ?></h2>
				<?php foreach ( array( 'light' => __( 'Claro', 'rootwp' ), 'dark' => __( 'Escuro', 'rootwp' ) ) as $mode => $label ) : ?>
					<div class="rootwp-preview" data-mode="<?php echo esc_attr( $mode ); ?>">
						<span class="rootwp-preview__mode"><?php echo esc_html( $label ); ?></span>
						<div class="rootwp-preview__card">
							<span class="rootwp-preview__badge"><?php esc_html_e( 'tecnologia', 'rootwp' ); ?></span>
							<strong class="rootwp-preview__title"><?php esc_html_e( 'Título do artigo', 'rootwp' ); ?></strong>
							<p class="rootwp-preview__text"><?php esc_html_e( 'Um trecho do texto com um', 'rootwp' ); ?> <a href="#" tabindex="-1"><?php esc_html_e( 'link no meio', 'rootwp' ); ?></a>.</p>
							<p class="rootwp-preview__muted"><?php esc_html_e( '30 de setembro · 5 min de leitura', 'rootwp' ); ?></p>
							<span class="rootwp-preview__button"><?php esc_html_e( 'Ler artigo', 'rootwp' ); ?></span>
							<span class="rootwp-preview__progress"></span>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<?php
}
add_action( 'admin_enqueue_scripts', 'rootwp_admin_assets' );

/**
 * Renderiza a página do tema.
 */
function rootwp_render_panel() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$page = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : 'rootwp'; // phpcs:ignore WordPress.Security.NonceVerification
	$tabs = rootwp_panel_tabs();
	$tab  = 'logo';
	foreach ( $tabs as $key => $item ) {
		if ( $item['page'] === $page ) {
			$tab = $key;
		}
	}
	?>
	<div class="wrap rootwp-panel">
		<h1><?php esc_html_e( 'Root WP', 'rootwp' ); ?></h1>

		<nav class="nav-tab-wrapper">
			<?php foreach ( $tabs as $key => $item ) : ?>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=' . $item['page'] ) ); ?>" class="nav-tab <?php echo $key === $tab ? 'nav-tab-active' : ''; ?>"><?php echo esc_html( $item['label'] ); ?></a>
			<?php endforeach; ?>
		</nav>

		<?php
		settings_errors();
		rootwp_tools_message();
		?>

		<?php if ( isset( $tabs[ $tab ]['form'] ) && false === $tabs[ $tab ]['form'] ) : ?>
			<?php call_user_func( $tabs[ $tab ]['render'] ); ?>
		<?php else : ?>
			<form method="post" action="options.php">
				<?php settings_fields( 'rootwp_options' ); ?>
				<input type="hidden" name="rootwp_options[_tab]" value="<?php echo esc_attr( $tab ); ?>">
				<?php
				call_user_func( $tabs[ $tab ]['render'] );
				submit_button( __( 'Salvar alterações', 'rootwp' ) );
				?>
			</form>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Nome do campo no formulário.
 *
 * @param string $key Opção.
 * @return string
 */
function rootwp_field_name( $key ) {
	return 'rootwp_options[' . $key . ']';
}

/**
 * Aba "Logo".
 */
function rootwp_render_logo_tab() {
	$type    = rootwp_panel_option( 'logo_type' );
	$image   = (int) rootwp_panel_option( 'logo_image' );
	$preview = $image ? wp_get_attachment_image_url( $image, 'medium' ) : '';
	?>
	<div class="rootwp-card">
		<h2><?php esc_html_e( 'Tipo de logo', 'rootwp' ); ?></h2>
		<div class="rootwp-choice">
			<label>
				<input type="radio" name="<?php echo esc_attr( rootwp_field_name( 'logo_type' ) ); ?>" value="text" <?php checked( $type, 'text' ); ?>>
				<span><strong><?php esc_html_e( 'Texto', 'rootwp' ); ?></strong><?php esc_html_e( 'Exibe o nome em JetBrains Mono.', 'rootwp' ); ?></span>
			</label>
			<label>
				<input type="radio" name="<?php echo esc_attr( rootwp_field_name( 'logo_type' ) ); ?>" value="image" <?php checked( $type, 'image' ); ?>>
				<span><strong><?php esc_html_e( 'Imagem', 'rootwp' ); ?></strong><?php esc_html_e( 'Usa um arquivo enviado (PNG, SVG, JPG).', 'rootwp' ); ?></span>
			</label>
		</div>
	</div>

	<div class="rootwp-card" data-logo-panel="text">
		<h2><?php esc_html_e( 'Logo em texto', 'rootwp' ); ?></h2>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><label for="as-logo-text"><?php esc_html_e( 'Texto do logo', 'rootwp' ); ?></label></th>
				<td>
					<input type="text" id="as-logo-text" class="regular-text" name="<?php echo esc_attr( rootwp_field_name( 'logo_text' ) ); ?>" value="<?php echo esc_attr( rootwp_panel_option( 'logo_text' ) ); ?>" placeholder="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
					<p class="description"><?php esc_html_e( 'Vazio = usa o título do site.', 'rootwp' ); ?></p>
				</td>
			</tr>
		</table>
	</div>

	<div class="rootwp-card" data-logo-panel="image">
		<h2><?php esc_html_e( 'Logo em imagem', 'rootwp' ); ?></h2>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><?php esc_html_e( 'Imagem', 'rootwp' ); ?></th>
				<td>
					<div class="rootwp-media">
						<div class="rootwp-media__preview"><?php if ( $preview ) : ?><img src="<?php echo esc_url( $preview ); ?>" alt=""><?php endif; ?></div>
						<input type="hidden" class="rootwp-media__id" name="<?php echo esc_attr( rootwp_field_name( 'logo_image' ) ); ?>" value="<?php echo esc_attr( $image ); ?>">
						<button type="button" class="button rootwp-media__select"><?php esc_html_e( 'Escolher imagem', 'rootwp' ); ?></button>
						<button type="button" class="button-link-delete rootwp-media__remove" <?php echo $image ? '' : 'hidden'; ?>><?php esc_html_e( 'Remover', 'rootwp' ); ?></button>
					</div>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="as-logo-height"><?php esc_html_e( 'Altura máxima', 'rootwp' ); ?></label></th>
				<td>
					<input type="number" id="as-logo-height" class="small-text" min="20" max="120" name="<?php echo esc_attr( rootwp_field_name( 'logo_height' ) ); ?>" value="<?php echo esc_attr( rootwp_panel_option( 'logo_height' ) ); ?>"> px
				</td>
			</tr>
		</table>
	</div>

	<div class="rootwp-card">
		<h2><?php esc_html_e( 'Descrição', 'rootwp' ); ?></h2>
		<label>
			<input type="checkbox" name="<?php echo esc_attr( rootwp_field_name( 'logo_show_tagline' ) ); ?>" value="1" <?php checked( rootwp_panel_option( 'logo_show_tagline' ), 1 ); ?>>
			<?php esc_html_e( 'Exibir a descrição do site abaixo do logo', 'rootwp' ); ?>
		</label>
	</div>

	<?php
	$favicon     = (int) rootwp_panel_option( 'favicon' );
	$favicon_url = $favicon ? wp_get_attachment_image_url( $favicon, array( 64, 64 ) ) : '';
	$core_icon   = (int) get_option( 'site_icon' );
	?>
	<div class="rootwp-card">
		<h2><?php esc_html_e( 'Favicon', 'rootwp' ); ?></h2>
		<p class="description"><?php esc_html_e( 'Ícone exibido na aba do navegador, nos favoritos e na tela inicial do celular. Use uma imagem quadrada PNG de pelo menos 512 × 512 px.', 'rootwp' ); ?></p>
		<div class="rootwp-media rootwp-media--icon" data-title="<?php esc_attr_e( 'Escolher favicon', 'rootwp' ); ?>">
			<div class="rootwp-media__preview"><?php if ( $favicon_url ) : ?><img src="<?php echo esc_url( $favicon_url ); ?>" alt=""><?php endif; ?></div>
			<input type="hidden" class="rootwp-media__id" name="<?php echo esc_attr( rootwp_field_name( 'favicon' ) ); ?>" value="<?php echo esc_attr( $favicon ); ?>">
			<button type="button" class="button rootwp-media__select"><?php esc_html_e( 'Escolher imagem', 'rootwp' ); ?></button>
			<button type="button" class="button-link-delete rootwp-media__remove" <?php echo $favicon ? '' : 'hidden'; ?>><?php esc_html_e( 'Remover', 'rootwp' ); ?></button>
		</div>
		<?php if ( $core_icon && ! $favicon ) : ?>
			<p class="description"><?php esc_html_e( 'No momento está sendo usado o "Ícone do site" definido em Configurações > Geral. Ao escolher uma imagem aqui, ela passa a ter prioridade.', 'rootwp' ); ?></p>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Grupo de opções em cartões (radio).
 *
 * @param string $key     Opção.
 * @param array  $choices valor => array( título, descrição ).
 */
function rootwp_render_choice( $key, $choices ) {
	$current = rootwp_panel_option( $key );
	echo '<div class="rootwp-choice">';
	foreach ( $choices as $value => $choice ) {
		printf(
			'<label><input type="radio" name="%1$s" value="%2$s" %3$s><span><strong>%4$s</strong>%5$s</span></label>',
			esc_attr( rootwp_field_name( $key ) ),
			esc_attr( $value ),
			checked( $current, $value, false ),
			esc_html( $choice[0] ),
			esc_html( $choice[1] )
		);
	}
	echo '</div>';
}

/**
 * Checkbox simples.
 *
 * @param string $key   Opção.
 * @param string $label Rótulo.
 */
function rootwp_render_checkbox( $key, $label ) {
	printf(
		'<p><label><input type="checkbox" name="%1$s" value="1" %2$s> %3$s</label></p>',
		esc_attr( rootwp_field_name( $key ) ),
		checked( rootwp_panel_option( $key ), 1, false ),
		esc_html( $label )
	);
}

/**
 * Aba "Página inicial".
 */
function rootwp_render_home_tab() {
	$image     = (int) rootwp_panel_option( 'hero_image' );
	$preview   = $image ? wp_get_attachment_image_url( $image, 'medium' ) : '';
	$sections  = array(
		'content' => array( __( 'Conteúdo da página', 'rootwp' ), __( 'O que você escrever no editor da página "Início".', 'rootwp' ) ),
		'recent'  => array( __( 'Artigos recentes', 'rootwp' ), '' ),
		'topics'  => array( __( 'Explore por tema', 'rootwp' ), '' ),
	);
	$order     = explode( ',', rootwp_panel_option( 'home_sections_order' ) );
	$selected  = array_filter( array_map( 'absint', explode( ',', rootwp_panel_option( 'home_topics_selected' ) ) ) );
	$all_cats  = get_categories( array( 'hide_empty' => false ) );
	$cat_names = array( 0 => __( 'Todas as categorias', 'rootwp' ) );
	foreach ( $all_cats as $cat ) {
		$cat_names[ $cat->term_id ] = $cat->name;
	}
	// Categorias marcadas primeiro (na ordem salva), depois as demais em ordem alfabética.
	$ordered_cats = array();
	foreach ( $selected as $id ) {
		if ( isset( $cat_names[ $id ] ) ) {
			$ordered_cats[ $id ] = $cat_names[ $id ];
		}
	}
	foreach ( $all_cats as $cat ) {
		if ( ! isset( $ordered_cats[ $cat->term_id ] ) ) {
			$ordered_cats[ $cat->term_id ] = $cat->name;
		}
	}
	?>
	<?php if ( 'page' !== get_option( 'show_on_front' ) ) : ?>
		<div class="notice notice-warning inline">
			<p>
				<?php
				printf(
					/* translators: %s: link para Configurações > Leitura */
					esc_html__( 'Seu site está exibindo "os posts mais recentes" como página inicial, então estas opções não aparecem. Para usá-las, escolha uma página estática em %s.', 'rootwp' ),
					'<a href="' . esc_url( admin_url( 'options-reading.php' ) ) . '">' . esc_html__( 'Configurações > Leitura', 'rootwp' ) . '</a>'
				);
				?>
			</p>
		</div>
	<?php endif; ?>

	<div class="rootwp-card">
		<h2><?php esc_html_e( 'Topo da página (destaque)', 'rootwp' ); ?></h2>
		<table class="form-table" role="presentation">
			<?php rootwp_render_field_row( 'hero_eyebrow', __( 'Chamada acima do título', 'rootwp' ) ); ?>
			<?php rootwp_render_field_row( 'hero_title', __( 'Título principal', 'rootwp' ) ); ?>
			<tr>
				<th scope="row"><label for="as-hero-text"><?php esc_html_e( 'Texto de apresentação', 'rootwp' ); ?></label></th>
				<td><textarea id="as-hero-text" class="large-text" rows="3" name="<?php echo esc_attr( rootwp_field_name( 'hero_text' ) ); ?>"><?php echo esc_textarea( rootwp_panel_option( 'hero_text' ) ); ?></textarea></td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Imagem ao lado', 'rootwp' ); ?></th>
				<td>
					<div class="rootwp-media" data-title="<?php esc_attr_e( 'Imagem de destaque', 'rootwp' ); ?>">
						<div class="rootwp-media__preview"><?php if ( $preview ) : ?><img src="<?php echo esc_url( $preview ); ?>" alt=""><?php endif; ?></div>
						<input type="hidden" class="rootwp-media__id" name="<?php echo esc_attr( rootwp_field_name( 'hero_image' ) ); ?>" value="<?php echo esc_attr( $image ); ?>">
						<button type="button" class="button rootwp-media__select"><?php esc_html_e( 'Escolher imagem', 'rootwp' ); ?></button>
						<button type="button" class="button-link-delete rootwp-media__remove" <?php echo $image ? '' : 'hidden'; ?>><?php esc_html_e( 'Remover', 'rootwp' ); ?></button>
					</div>
					<p class="description"><?php esc_html_e( 'Opcional. Sem imagem, o texto ocupa a largura toda.', 'rootwp' ); ?></p>
				</td>
			</tr>
		</table>

		<h3><?php esc_html_e( 'Botões', 'rootwp' ); ?></h3>
		<table class="form-table" role="presentation">
			<?php
			rootwp_render_field_row( 'hero_button_text', __( 'Botão 1 – texto', 'rootwp' ) );
			rootwp_render_field_row( 'hero_button_url', __( 'Botão 1 – link', 'rootwp' ), 'url', array( 'description' => __( 'Vazio = página do blog.', 'rootwp' ) ) );
			?>
			<tr><th scope="row"></th><td><?php rootwp_render_checkbox( 'hero_button_new_tab', __( 'Abrir o botão 1 em nova aba', 'rootwp' ) ); ?></td></tr>
			<?php
			rootwp_render_field_row( 'hero_button2_text', __( 'Botão 2 – texto', 'rootwp' ) );
			rootwp_render_field_row( 'hero_button2_url', __( 'Botão 2 – link', 'rootwp' ), 'url', array( 'description' => __( 'Vazio = botão oculto.', 'rootwp' ) ) );
			?>
			<tr><th scope="row"></th><td><?php rootwp_render_checkbox( 'hero_button2_new_tab', __( 'Abrir o botão 2 em nova aba', 'rootwp' ) ); ?></td></tr>
		</table>
	</div>

	<div class="rootwp-card">
		<h2><?php esc_html_e( 'Ordem das seções', 'rootwp' ); ?></h2>
		<p class="description"><?php esc_html_e( 'Arraste para definir a ordem em que aparecem abaixo do topo.', 'rootwp' ); ?></p>
		<input type="hidden" id="as-sections-order" name="<?php echo esc_attr( rootwp_field_name( 'home_sections_order' ) ); ?>" value="<?php echo esc_attr( rootwp_panel_option( 'home_sections_order' ) ); ?>">
		<ul class="rootwp-sortable" data-target="as-sections-order">
			<?php foreach ( $order as $key ) : ?>
				<?php if ( isset( $sections[ $key ] ) ) : ?>
					<li data-value="<?php echo esc_attr( $key ); ?>">
						<span class="dashicons dashicons-menu" aria-hidden="true"></span>
						<strong><?php echo esc_html( $sections[ $key ][0] ); ?></strong>
						<?php if ( $sections[ $key ][1] ) : ?>
							<span class="description"><?php echo esc_html( $sections[ $key ][1] ); ?></span>
						<?php endif; ?>
					</li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
	</div>

	<div class="rootwp-card">
		<h2><?php esc_html_e( 'Artigos recentes', 'rootwp' ); ?></h2>
		<?php rootwp_render_checkbox( 'home_recent_enabled', __( 'Exibir a seção', 'rootwp' ) ); ?>
		<table class="form-table" role="presentation">
			<?php
			rootwp_render_field_row( 'home_recent_title', __( 'Título', 'rootwp' ) );
			rootwp_render_field_row( 'home_recent_subtitle', __( 'Subtítulo', 'rootwp' ) );
			rootwp_render_field_row( 'home_recent_button', __( 'Texto do botão', 'rootwp' ), 'text', array( 'description' => __( 'Vazio = botão oculto.', 'rootwp' ) ) );
			rootwp_render_field_row( 'home_posts_count', __( 'Quantidade', 'rootwp' ), 'number', array( 'min' => 1, 'max' => 12 ) );
			?>
			<tr>
				<th scope="row"><label for="as-home-recent-category"><?php esc_html_e( 'Categoria', 'rootwp' ); ?></label></th>
				<td>
					<select id="as-home-recent-category" name="<?php echo esc_attr( rootwp_field_name( 'home_recent_category' ) ); ?>">
						<?php foreach ( $cat_names as $id => $name ) : ?>
							<option value="<?php echo esc_attr( $id ); ?>" <?php selected( (int) rootwp_panel_option( 'home_recent_category' ), $id ); ?>><?php echo esc_html( $name ); ?></option>
						<?php endforeach; ?>
					</select>
					<p class="description"><?php esc_html_e( 'Com uma categoria escolhida, o botão leva para a página dela.', 'rootwp' ); ?></p>
				</td>
			</tr>
		</table>
	</div>

	<div class="rootwp-card">
		<h2><?php esc_html_e( 'Explore por tema', 'rootwp' ); ?></h2>
		<?php rootwp_render_checkbox( 'show_topics', __( 'Exibir a seção', 'rootwp' ) ); ?>
		<table class="form-table" role="presentation">
			<?php
			rootwp_render_field_row( 'home_topics_title', __( 'Título', 'rootwp' ) );
			rootwp_render_field_row( 'home_topics_subtitle', __( 'Subtítulo', 'rootwp' ) );
			?>
		</table>
		<?php rootwp_render_checkbox( 'home_topics_show_count', __( 'Mostrar a quantidade de artigos em cada tema', 'rootwp' ) ); ?>
		<?php
		rootwp_render_choice(
			'home_topics_mode',
			array(
				'auto'   => array( __( 'Automático', 'rootwp' ), __( 'As categorias com mais artigos.', 'rootwp' ) ),
				'manual' => array( __( 'Escolher manualmente', 'rootwp' ), __( 'Você marca quais aparecem e arrasta para ordenar.', 'rootwp' ) ),
			)
		);
		?>
		<div data-topics-panel="auto">
			<table class="form-table" role="presentation">
				<?php rootwp_render_field_row( 'home_topics_number', __( 'Quantidade', 'rootwp' ), 'number', array( 'min' => 1, 'max' => 12 ) ); ?>
			</table>
		</div>
		<div data-topics-panel="manual">
			<input type="hidden" id="as-topics-selected" name="<?php echo esc_attr( rootwp_field_name( 'home_topics_selected' ) ); ?>" value="<?php echo esc_attr( rootwp_panel_option( 'home_topics_selected' ) ); ?>">
			<?php if ( $ordered_cats ) : ?>
				<ul class="rootwp-sortable rootwp-sortable--checks" data-target="as-topics-selected">
					<?php foreach ( $ordered_cats as $id => $name ) : ?>
						<li data-value="<?php echo esc_attr( $id ); ?>">
							<span class="dashicons dashicons-menu" aria-hidden="true"></span>
							<label><input type="checkbox" <?php checked( in_array( $id, $selected, true ) ); ?>> <?php echo esc_html( $name ); ?></label>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php else : ?>
				<p><?php esc_html_e( 'Nenhuma categoria criada ainda.', 'rootwp' ); ?></p>
			<?php endif; ?>
		</div>
	</div>
	<?php
}

/**
 * Aba "Aparência".
 */
function rootwp_render_appearance_tab() {
	?>
	<div class="rootwp-card">
		<h2><?php esc_html_e( 'Modo escuro', 'rootwp' ); ?></h2>
		<p class="description"><?php esc_html_e( 'Tema exibido na primeira visita. Depois que o visitante usa o botão, a escolha dele é lembrada.', 'rootwp' ); ?></p>
		<?php
		rootwp_render_choice(
			'dark_mode_default',
			array(
				'auto'  => array( __( 'Seguir o sistema', 'rootwp' ), __( 'Usa a preferência do dispositivo.', 'rootwp' ) ),
				'light' => array( __( 'Claro', 'rootwp' ), __( 'Sempre começa no modo claro.', 'rootwp' ) ),
				'dark'  => array( __( 'Escuro', 'rootwp' ), __( 'Sempre começa no modo escuro.', 'rootwp' ) ),
			)
		);
		rootwp_render_checkbox( 'dark_mode_toggle', __( 'Exibir o botão de alternar modo escuro no cabeçalho', 'rootwp' ) );
		?>
	</div>

	<div class="rootwp-card">
		<h2><?php esc_html_e( 'Tamanho da fonte do artigo', 'rootwp' ); ?></h2>
		<?php
		rootwp_render_choice(
			'article_font_size',
			array(
				'small'  => array( __( 'Pequeno', 'rootwp' ), '16px' ),
				'medium' => array( __( 'Médio', 'rootwp' ), '18px' ),
				'large'  => array( __( 'Grande', 'rootwp' ), '20px' ),
			)
		);
		?>
	</div>

	<div class="rootwp-card">
		<h2><?php esc_html_e( 'Largura do conteúdo', 'rootwp' ); ?></h2>
		<p class="description"><?php esc_html_e( 'Largura da coluna de texto em artigos e páginas.', 'rootwp' ); ?></p>
		<?php
		rootwp_render_choice(
			'content_width',
			array(
				'narrow' => array( __( 'Estreita', 'rootwp' ), '660px' ),
				'normal' => array( __( 'Normal', 'rootwp' ), '720px' ),
				'wide'   => array( __( 'Larga', 'rootwp' ), '820px' ),
			)
		);
		?>
	</div>

	<div class="rootwp-card">
		<h2><?php esc_html_e( 'Barra lateral', 'rootwp' ); ?></h2>
		<p class="description">
			<?php
			printf(
				/* translators: %s: link para a tela de widgets */
				esc_html__( 'Os widgets são configurados em %s. A barra só aparece se tiver algum widget.', 'rootwp' ),
				'<a href="' . esc_url( admin_url( 'widgets.php' ) ) . '">' . esc_html__( 'Aparência > Widgets', 'rootwp' ) . '</a>'
			);
			?>
		</p>
		<?php
		rootwp_render_checkbox( 'sidebar_archive', __( 'Exibir na listagem de posts (blog, categorias, busca)', 'rootwp' ) );
		rootwp_render_checkbox( 'sidebar_single', __( 'Exibir nos artigos (acima do sumário, em telas largas)', 'rootwp' ) );
		?>
	</div>
	<?php
}

/**
 * Aba "Cabeçalho".
 */
function rootwp_render_header_tab() {
	?>
	<div class="rootwp-card">
		<h2><?php esc_html_e( 'Comportamento', 'rootwp' ); ?></h2>
		<?php
		rootwp_render_checkbox( 'header_sticky', __( 'Manter o cabeçalho fixo no topo ao rolar a página', 'rootwp' ) );
		rootwp_render_checkbox( 'header_show_search', __( 'Exibir o botão de busca', 'rootwp' ) );
		?>
	</div>

	<div class="rootwp-card">
		<h2><?php esc_html_e( 'Botão de destaque', 'rootwp' ); ?></h2>
		<p class="description"><?php esc_html_e( 'Aparece ao lado do menu. Preencha texto e link para exibir, ou deixe em branco para ocultar.', 'rootwp' ); ?></p>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><label for="as-cta-text"><?php esc_html_e( 'Texto', 'rootwp' ); ?></label></th>
				<td><input type="text" id="as-cta-text" class="regular-text" name="<?php echo esc_attr( rootwp_field_name( 'header_cta_text' ) ); ?>" value="<?php echo esc_attr( rootwp_panel_option( 'header_cta_text' ) ); ?>" placeholder="<?php esc_attr_e( 'Ex.: Contato', 'rootwp' ); ?>"></td>
			</tr>
			<tr>
				<th scope="row"><label for="as-cta-url"><?php esc_html_e( 'Link', 'rootwp' ); ?></label></th>
				<td>
					<input type="url" id="as-cta-url" class="regular-text" name="<?php echo esc_attr( rootwp_field_name( 'header_cta_url' ) ); ?>" value="<?php echo esc_attr( rootwp_panel_option( 'header_cta_url' ) ); ?>" placeholder="https://">
					<?php rootwp_render_checkbox( 'header_cta_new_tab', __( 'Abrir em nova aba', 'rootwp' ) ); ?>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Estilo', 'rootwp' ); ?></th>
				<td>
					<?php
					rootwp_render_choice(
						'header_cta_style',
						array(
							'solid'   => array( __( 'Preenchido', 'rootwp' ), __( 'Fundo na cor principal.', 'rootwp' ) ),
							'outline' => array( __( 'Contorno', 'rootwp' ), __( 'Só a borda, mais discreto.', 'rootwp' ) ),
						)
					);
					?>
				</td>
			</tr>
		</table>
	</div>
	<?php
}

/**
 * Campo de texto ou número em linha de tabela.
 *
 * @param string $key   Opção.
 * @param string $label Rótulo.
 * @param string $type  text|number.
 * @param array  $attrs Atributos extras (min, max, class, suffix, description).
 */
function rootwp_render_field_row( $key, $label, $type = 'text', $attrs = array() ) {
	$id    = 'as-' . str_replace( '_', '-', $key );
	$class = 'number' === $type ? 'small-text' : 'regular-text';
	?>
	<tr>
		<th scope="row"><label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $label ); ?></label></th>
		<td>
			<input type="<?php echo esc_attr( $type ); ?>" id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $class ); ?>"
				name="<?php echo esc_attr( rootwp_field_name( $key ) ); ?>" value="<?php echo esc_attr( rootwp_panel_option( $key ) ); ?>"
				<?php echo isset( $attrs['min'] ) ? 'min="' . (int) $attrs['min'] . '"' : ''; ?>
				<?php echo isset( $attrs['max'] ) ? 'max="' . (int) $attrs['max'] . '"' : ''; ?>>
			<?php echo isset( $attrs['suffix'] ) ? esc_html( $attrs['suffix'] ) : ''; ?>
			<?php if ( ! empty( $attrs['description'] ) ) : ?>
				<p class="description"><?php echo esc_html( $attrs['description'] ); ?></p>
			<?php endif; ?>
		</td>
	</tr>
	<?php
}

/**
 * Aba "Artigos".
 */
function rootwp_render_articles_tab() {
	?>
	<div class="rootwp-card">
		<h2><?php esc_html_e( 'Sumário', 'rootwp' ); ?></h2>
		<?php rootwp_render_checkbox( 'toc_enabled', __( 'Gerar sumário automático ao lado do artigo (em telas largas)', 'rootwp' ) ); ?>
		<?php
		rootwp_render_choice(
			'toc_levels',
			array(
				'h2'   => array( __( 'Só títulos principais', 'rootwp' ), __( 'Usa os títulos H2.', 'rootwp' ) ),
				'h2h3' => array( __( 'Títulos e subtítulos', 'rootwp' ), __( 'Usa H2 e H3, com recuo nos subtítulos.', 'rootwp' ) ),
			)
		);
		?>
		<table class="form-table" role="presentation">
			<?php
			rootwp_render_field_row( 'toc_title', __( 'Título do sumário', 'rootwp' ) );
			rootwp_render_field_row(
				'toc_min',
				__( 'Mínimo de títulos', 'rootwp' ),
				'number',
				array(
					'min'         => 2,
					'max'         => 10,
					'description' => __( 'Artigos com menos títulos que isso não exibem o sumário.', 'rootwp' ),
				)
			);
			?>
		</table>
	</div>

	<div class="rootwp-card">
		<h2><?php esc_html_e( 'Leitura', 'rootwp' ); ?></h2>
		<?php
		rootwp_render_checkbox( 'reading_time', __( 'Exibir tempo estimado de leitura (no artigo e nos cards)', 'rootwp' ) );
		rootwp_render_checkbox( 'progress_bar', __( 'Exibir barra de progresso de leitura no topo da tela', 'rootwp' ) );
		rootwp_render_checkbox( 'reading_remaining', __( 'Mostrar o tempo restante de leitura ("faltam 3 min") no canto da tela', 'rootwp' ) );
		rootwp_render_checkbox( 'font_resize', __( 'Mostrar os botões A− A A+ para o visitante ajustar o tamanho do texto', 'rootwp' ) );
		rootwp_render_checkbox( 'code_copy', __( 'Mostrar o botão "Copiar" nos blocos de código', 'rootwp' ) );
		?>
		<table class="form-table" role="presentation">
			<?php
			rootwp_render_field_row(
				'reading_wpm',
				__( 'Velocidade de leitura', 'rootwp' ),
				'number',
				array(
					'min'         => 100,
					'max'         => 400,
					'suffix'      => __( 'palavras por minuto', 'rootwp' ),
					'description' => __( 'Média de um adulto: 200 a 250. Para conteúdo técnico, valores menores são mais realistas.', 'rootwp' ),
				)
			);
			?>
		</table>
	</div>

	<div class="rootwp-card">
		<h2><?php esc_html_e( 'Séries de artigos', 'rootwp' ); ?></h2>
		<p class="description"><?php esc_html_e( 'Agrupe artigos em partes (Parte 1, 2, 3…). No editor do post, marque a série na caixa "Séries", como faz com as categorias. A ordem das partes é a data de publicação.', 'rootwp' ); ?></p>
		<?php rootwp_render_checkbox( 'series_enabled', __( 'Ativar séries: caixa "Parte X de Y" no topo do artigo, navegação entre as partes e página da série', 'rootwp' ) ); ?>
		<?php if ( taxonomy_exists( 'serie' ) ) : ?>
			<p><a class="button" href="<?php echo esc_url( admin_url( 'edit-tags.php?taxonomy=serie' ) ); ?>"><?php esc_html_e( 'Gerenciar séries', 'rootwp' ); ?></a></p>
		<?php endif; ?>
	</div>

	<div class="rootwp-card">
		<h2><?php esc_html_e( 'Compartilhamento', 'rootwp' ); ?></h2>
		<?php rootwp_render_checkbox( 'share_enabled', __( 'Exibir botões de compartilhar no fim do artigo', 'rootwp' ) ); ?>
		<div class="rootwp-checks">
			<?php foreach ( rootwp_share_networks() as $network => $data ) : ?>
				<?php rootwp_render_checkbox( 'share_' . $network, $data['label'] ); ?>
			<?php endforeach; ?>
		</div>
	</div>

	<div class="rootwp-card">
		<h2><?php esc_html_e( 'Autor', 'rootwp' ); ?></h2>
		<?php rootwp_render_checkbox( 'author_box', __( 'Exibir a caixa "Escrito por" no fim do artigo', 'rootwp' ) ); ?>
	</div>

	<div class="rootwp-card">
		<h2><?php esc_html_e( 'Posts relacionados', 'rootwp' ); ?></h2>
		<?php rootwp_render_checkbox( 'related_enabled', __( 'Exibir posts relacionados no fim do artigo', 'rootwp' ) ); ?>
		<?php
		rootwp_render_choice(
			'related_by',
			array(
				'category' => array( __( 'Mesma categoria', 'rootwp' ), __( 'Mais abrangente.', 'rootwp' ) ),
				'tag'      => array( __( 'Mesmas tags', 'rootwp' ), __( 'Mais preciso; usa a categoria se o post não tiver tags.', 'rootwp' ) ),
			)
		);
		?>
		<table class="form-table" role="presentation">
			<?php
			rootwp_render_field_row( 'related_title', __( 'Título da seção', 'rootwp' ) );
			rootwp_render_field_row( 'related_count', __( 'Quantidade', 'rootwp' ), 'number', array( 'min' => 1, 'max' => 6 ) );
			?>
		</table>
	</div>

	<div class="rootwp-card">
		<h2><?php esc_html_e( 'Aviso em posts antigos', 'rootwp' ); ?></h2>
		<p class="description"><?php esc_html_e( 'Mostra um alerta no topo de artigos que não são atualizados há muito tempo. Conta a partir da última atualização do post.', 'rootwp' ); ?></p>
		<?php rootwp_render_checkbox( 'old_notice', __( 'Exibir o aviso', 'rootwp' ) ); ?>
		<table class="form-table" role="presentation">
			<?php
			rootwp_render_field_row(
				'old_notice_months',
				__( 'Considerar antigo após', 'rootwp' ),
				'number',
				array(
					'min'    => 3,
					'max'    => 120,
					'suffix' => __( 'meses', 'rootwp' ),
				)
			);
			?>
			<tr>
				<th scope="row"><label for="as-old-notice-text"><?php esc_html_e( 'Texto do aviso', 'rootwp' ); ?></label></th>
				<td>
					<textarea id="as-old-notice-text" class="large-text" rows="2" name="<?php echo esc_attr( rootwp_field_name( 'old_notice_text' ) ); ?>"><?php echo esc_textarea( rootwp_panel_option( 'old_notice_text' ) ); ?></textarea>
					<p class="description"><?php esc_html_e( 'Use {tempo} para o tempo desde a atualização (ex.: "2 anos").', 'rootwp' ); ?></p>
				</td>
			</tr>
		</table>
	</div>
	<?php
}

/**
 * Aba "Avisos".
 */
function rootwp_render_notices_tab() {
	$image    = (int) rootwp_panel_option( 'cta_image' );
	$preview  = $image ? wp_get_attachment_image_url( $image, 'medium' ) : '';
	$excluded = array_filter( array_map( 'absint', explode( ',', rootwp_panel_option( 'cta_exclude' ) ) ) );
	?>
	<div class="rootwp-card">
		<h2><?php esc_html_e( 'Barra de aviso no topo', 'rootwp' ); ?></h2>
		<p class="description"><?php esc_html_e( 'Uma faixa acima do cabeçalho, em todas as páginas. Bom para divulgar um curso, evento, material novo ou aviso importante.', 'rootwp' ); ?></p>
		<?php rootwp_render_checkbox( 'notice_enabled', __( 'Exibir a barra de aviso', 'rootwp' ) ); ?>
		<table class="form-table" role="presentation">
			<?php
			rootwp_render_field_row( 'notice_text', __( 'Texto', 'rootwp' ), 'text', array( 'description' => __( 'Curto, de preferência em uma linha. Ex.: "Novo curso de JavaScript com inscrições abertas".', 'rootwp' ) ) );
			rootwp_render_field_row( 'notice_link_text', __( 'Texto do link', 'rootwp' ), 'text', array( 'description' => __( 'Ex.: "Saiba mais". Vazio = sem link.', 'rootwp' ) ) );
			rootwp_render_field_row( 'notice_link_url', __( 'Link', 'rootwp' ), 'url' );
			?>
			<tr><th scope="row"></th><td><?php rootwp_render_checkbox( 'notice_new_tab', __( 'Abrir o link em nova aba', 'rootwp' ) ); ?></td></tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Cor', 'rootwp' ); ?></th>
				<td>
					<?php
					rootwp_render_choice(
						'notice_style',
						array(
							'primary' => array( __( 'Cor principal', 'rootwp' ), __( 'Chama bastante atenção.', 'rootwp' ) ),
							'accent'  => array( __( 'Cor de destaque', 'rootwp' ), __( 'Para promoções e novidades.', 'rootwp' ) ),
							'dark'    => array( __( 'Escura', 'rootwp' ), __( 'Sóbria, combina com tudo.', 'rootwp' ) ),
							'soft'    => array( __( 'Suave', 'rootwp' ), __( 'Tom claro, discreta.', 'rootwp' ) ),
						)
					);
					?>
				</td>
			</tr>
			<tr><th scope="row"></th><td><?php rootwp_render_checkbox( 'notice_dismissible', __( 'Permitir fechar (o visitante não vê de novo até você mudar o texto ou o link)', 'rootwp' ) ); ?></td></tr>
		</table>
	</div>

	<div class="rootwp-card">
		<h2><?php esc_html_e( 'Chamada no fim dos artigos', 'rootwp' ); ?></h2>
		<p class="description"><?php esc_html_e( 'Um bloco em destaque logo depois do texto de cada artigo: newsletter, curso, mentoria, redes sociais…', 'rootwp' ); ?></p>
		<?php rootwp_render_checkbox( 'cta_enabled', __( 'Exibir a chamada', 'rootwp' ) ); ?>
		<table class="form-table" role="presentation">
			<?php rootwp_render_field_row( 'cta_title', __( 'Título', 'rootwp' ), 'text', array( 'description' => __( 'Ex.: "Gostou do conteúdo?"', 'rootwp' ) ) ); ?>
			<tr>
				<th scope="row"><label for="as-cta-text-field"><?php esc_html_e( 'Texto', 'rootwp' ); ?></label></th>
				<td><textarea id="as-cta-text-field" class="large-text" rows="3" name="<?php echo esc_attr( rootwp_field_name( 'cta_text' ) ); ?>"><?php echo esc_textarea( rootwp_panel_option( 'cta_text' ) ); ?></textarea></td>
			</tr>
			<?php
			rootwp_render_field_row( 'cta_button_text', __( 'Texto do botão', 'rootwp' ), 'text', array( 'description' => __( 'Ex.: "Quero receber". Vazio = sem botão.', 'rootwp' ) ) );
			rootwp_render_field_row( 'cta_button_url', __( 'Link do botão', 'rootwp' ), 'url' );
			?>
			<tr><th scope="row"></th><td><?php rootwp_render_checkbox( 'cta_new_tab', __( 'Abrir o link em nova aba', 'rootwp' ) ); ?></td></tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Imagem', 'rootwp' ); ?></th>
				<td>
					<div class="rootwp-media" data-title="<?php esc_attr_e( 'Imagem da chamada', 'rootwp' ); ?>">
						<div class="rootwp-media__preview"><?php if ( $preview ) : ?><img src="<?php echo esc_url( $preview ); ?>" alt=""><?php endif; ?></div>
						<input type="hidden" class="rootwp-media__id" name="<?php echo esc_attr( rootwp_field_name( 'cta_image' ) ); ?>" value="<?php echo esc_attr( $image ? $image : '' ); ?>">
						<button type="button" class="button rootwp-media__select"><?php esc_html_e( 'Escolher imagem', 'rootwp' ); ?></button>
						<button type="button" class="button-link-delete rootwp-media__remove" <?php echo $image ? '' : 'hidden'; ?>><?php esc_html_e( 'Remover', 'rootwp' ); ?></button>
					</div>
					<p class="description"><?php esc_html_e( 'Opcional: capa do curso, ebook, sua foto…', 'rootwp' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Não exibir em', 'rootwp' ); ?></th>
				<td>
					<input type="hidden" name="<?php echo esc_attr( rootwp_field_name( 'cta_exclude' ) ); ?>[]" value="">
					<div class="rootwp-checks">
						<?php foreach ( get_categories( array( 'hide_empty' => false ) ) as $category ) : ?>
							<p><label><input type="checkbox" name="<?php echo esc_attr( rootwp_field_name( 'cta_exclude' ) ); ?>[]" value="<?php echo esc_attr( $category->term_id ); ?>" <?php checked( in_array( (int) $category->term_id, $excluded, true ) ); ?>> <?php echo esc_html( $category->name ); ?></label></p>
						<?php endforeach; ?>
					</div>
					<p class="description"><?php esc_html_e( 'Marque as categorias em que a chamada não deve aparecer.', 'rootwp' ); ?></p>
				</td>
			</tr>
		</table>
	</div>
	<?php
}

/**
 * Aba "SEO e integrações".
 */
function rootwp_render_seo_tab() {
	$image   = (int) rootwp_panel_option( 'seo_default_image' );
	$preview = $image ? wp_get_attachment_image_url( $image, 'medium' ) : '';
	$type    = rootwp_analytics_type();
	$can_code = current_user_can( 'unfiltered_html' );
	$codes   = array(
		'code_head'      => array( __( 'No <head>', 'rootwp' ), __( 'Verificações (Google Search Console, Pinterest, Meta), pixels e estilos extras.', 'rootwp' ) ),
		'code_body_open' => array( __( 'Logo após o <body>', 'rootwp' ), __( 'Códigos que pedem para ficar no início da página (ex.: noscript de ferramentas).', 'rootwp' ) ),
		'code_footer'    => array( __( 'Antes do </body>', 'rootwp' ), __( 'Chat, widgets de terceiros e scripts que podem carregar por último.', 'rootwp' ) ),
	);
	?>
	<div class="rootwp-card">
		<h2><?php esc_html_e( 'Prévia ao compartilhar links (SEO)', 'rootwp' ); ?></h2>
		<p class="description"><?php esc_html_e( 'Gera a descrição da página e as informações que WhatsApp, LinkedIn, Facebook, X e Google usam para montar a prévia: título, resumo e imagem.', 'rootwp' ); ?></p>
		<?php if ( rootwp_seo_plugin_active() ) : ?>
			<div class="notice notice-info inline"><p><?php esc_html_e( 'Um plugin de SEO está ativo. Para não duplicar, o tema não gera essas informações enquanto ele estiver ligado; as opções abaixo ficam sem efeito.', 'rootwp' ); ?></p></div>
		<?php endif; ?>
		<?php rootwp_render_checkbox( 'seo_enabled', __( 'Gerar descrição e prévia de compartilhamento', 'rootwp' ) ); ?>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><label for="as-seo-home"><?php esc_html_e( 'Descrição da página inicial', 'rootwp' ); ?></label></th>
				<td>
					<textarea id="as-seo-home" class="large-text" rows="2" maxlength="300" name="<?php echo esc_attr( rootwp_field_name( 'seo_home_description' ) ); ?>" placeholder="<?php echo esc_attr( get_bloginfo( 'description' ) ); ?>"><?php echo esc_textarea( rootwp_panel_option( 'seo_home_description' ) ); ?></textarea>
					<p class="description"><?php esc_html_e( 'Até ~160 caracteres. Vazio = descrição do site (Configurações > Geral). Nos posts, é usado o Resumo ou o início do texto.', 'rootwp' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Imagem padrão', 'rootwp' ); ?></th>
				<td>
					<div class="rootwp-media" data-title="<?php esc_attr_e( 'Imagem padrão para compartilhamento', 'rootwp' ); ?>">
						<div class="rootwp-media__preview"><?php if ( $preview ) : ?><img src="<?php echo esc_url( $preview ); ?>" alt=""><?php endif; ?></div>
						<input type="hidden" class="rootwp-media__id" name="<?php echo esc_attr( rootwp_field_name( 'seo_default_image' ) ); ?>" value="<?php echo esc_attr( $image ? $image : '' ); ?>">
						<button type="button" class="button rootwp-media__select"><?php esc_html_e( 'Escolher imagem', 'rootwp' ); ?></button>
						<button type="button" class="button-link-delete rootwp-media__remove" <?php echo $image ? '' : 'hidden'; ?>><?php esc_html_e( 'Remover', 'rootwp' ); ?></button>
					</div>
					<p class="description"><?php esc_html_e( 'Usada quando o post não tem imagem destacada (e nas páginas sem imagem). Ideal: 1200 × 630 px.', 'rootwp' ); ?></p>
				</td>
			</tr>
			<?php rootwp_render_field_row( 'seo_twitter', __( 'Perfil no X (Twitter)', 'rootwp' ), 'text', array( 'description' => __( 'Opcional, sem o @. Aparece como autor da prévia no X.', 'rootwp' ) ) ); ?>
		</table>
		<p class="description">
			<?php esc_html_e( 'Para testar a prévia depois de salvar:', 'rootwp' ); ?>
			<a href="https://www.opengraph.xyz/" target="_blank" rel="noopener">opengraph.xyz</a> ·
			<a href="https://www.linkedin.com/post-inspector/" target="_blank" rel="noopener">LinkedIn Post Inspector</a> ·
			<a href="https://developers.facebook.com/tools/debug/" target="_blank" rel="noopener">Facebook Debugger</a>
			<?php esc_html_e( '(o site precisa estar publicado na internet; em localhost eles não conseguem acessar).', 'rootwp' ); ?>
		</p>
	</div>

	<div class="rootwp-card">
		<h2><?php esc_html_e( 'Estatísticas (Google)', 'rootwp' ); ?></h2>
		<table class="form-table" role="presentation">
			<?php
			rootwp_render_field_row(
				'analytics_id',
				__( 'ID do Analytics ou Tag Manager', 'rootwp' ),
				'text',
				array( 'description' => __( 'Cole só o ID: G-XXXXXXXXXX (Google Analytics 4) ou GTM-XXXXXXX (Tag Manager). O tema insere o código certo.', 'rootwp' ) )
			);
			?>
			<tr>
				<th scope="row"></th>
				<td>
					<?php if ( rootwp_panel_option( 'analytics_id' ) ) : ?>
						<p class="rootwp-status rootwp-status--<?php echo $type ? 'ok' : 'bad'; ?>">
							<?php
							if ( 'ga4' === $type ) {
								esc_html_e( '✓ Google Analytics 4 ativo.', 'rootwp' );
							} elseif ( 'gtm' === $type ) {
								esc_html_e( '✓ Google Tag Manager ativo.', 'rootwp' );
							} else {
								esc_html_e( '⚠ ID não reconhecido: use o formato G-… ou GTM-…', 'rootwp' );
							}
							?>
						</p>
					<?php endif; ?>
					<?php rootwp_render_checkbox( 'analytics_skip_admins', __( 'Não contar as visitas de quem está logado (você e outros autores)', 'rootwp' ) ); ?>
				</td>
			</tr>
		</table>
	</div>

	<div class="rootwp-card">
		<h2><?php esc_html_e( 'Códigos extras', 'rootwp' ); ?></h2>
		<p class="description"><?php esc_html_e( 'Cole aqui códigos de verificação, pixels ou ferramentas. Eles aparecem em todas as páginas do site.', 'rootwp' ); ?></p>
		<?php if ( ! $can_code ) : ?>
			<div class="notice notice-warning inline"><p><?php esc_html_e( 'Sua conta não tem permissão para salvar scripts; estes campos ficam somente leitura.', 'rootwp' ); ?></p></div>
		<?php endif; ?>
		<table class="form-table" role="presentation">
			<?php foreach ( $codes as $key => $field ) : ?>
				<tr>
					<th scope="row"><label for="as-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field[0] ); ?></label></th>
					<td>
						<textarea id="as-<?php echo esc_attr( $key ); ?>" class="large-text code" rows="4" spellcheck="false" name="<?php echo esc_attr( rootwp_field_name( $key ) ); ?>" <?php echo $can_code ? '' : 'readonly'; ?>><?php echo esc_textarea( rootwp_panel_option( $key ) ); ?></textarea>
						<p class="description"><?php echo esc_html( $field[1] ); ?></p>
					</td>
				</tr>
			<?php endforeach; ?>
		</table>
		<p class="description"><?php esc_html_e( 'Atenção: um código com erro pode quebrar o visual ou o funcionamento do site. Cole apenas códigos de fontes confiáveis.', 'rootwp' ); ?></p>
	</div>
	<?php
}

/**
 * Aba "Rodapé".
 */
function rootwp_render_footer_tab() {
	$flags = array(
		'footer_show_widgets' => __( 'Exibir colunas de widgets (Aparência > Widgets)', 'rootwp' ),
		'footer_show_menu'    => __( 'Exibir o menu do rodapé', 'rootwp' ),
		'footer_show_social'  => __( 'Exibir ícones das redes sociais (na linha do copyright)', 'rootwp' ),
		'footer_back_to_top'  => __( 'Exibir link "Voltar ao topo"', 'rootwp' ),
	);
	?>
	<div class="rootwp-card">
		<h2><?php esc_html_e( 'Sobre', 'rootwp' ); ?></h2>
		<p class="description"><?php esc_html_e( 'Bloco de apresentação exibido na primeira coluna do rodapé. Deixe o texto vazio para ocultar.', 'rootwp' ); ?></p>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><label for="as-footer-about-title"><?php esc_html_e( 'Título', 'rootwp' ); ?></label></th>
				<td><input type="text" id="as-footer-about-title" class="regular-text" name="<?php echo esc_attr( rootwp_field_name( 'footer_about_title' ) ); ?>" value="<?php echo esc_attr( rootwp_panel_option( 'footer_about_title' ) ); ?>" placeholder="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"></td>
			</tr>
			<tr>
				<th scope="row"><label for="as-footer-about"><?php esc_html_e( 'Texto', 'rootwp' ); ?></label></th>
				<td><textarea id="as-footer-about" class="large-text" rows="3" name="<?php echo esc_attr( rootwp_field_name( 'footer_about' ) ); ?>"><?php echo esc_textarea( rootwp_panel_option( 'footer_about' ) ); ?></textarea></td>
			</tr>
		</table>
	</div>

	<div class="rootwp-card">
		<h2><?php esc_html_e( 'Redes sociais', 'rootwp' ); ?></h2>
		<p class="description"><?php esc_html_e( 'Deixe em branco as que não quiser exibir.', 'rootwp' ); ?></p>
		<table class="form-table" role="presentation">
			<?php foreach ( rootwp_social_networks() as $network => $label ) : ?>
				<tr>
					<th scope="row"><label for="as-social-<?php echo esc_attr( $network ); ?>"><?php echo esc_html( $label ); ?></label></th>
					<td><input type="<?php echo 'email' === $network ? 'email' : 'url'; ?>" id="as-social-<?php echo esc_attr( $network ); ?>" class="regular-text" name="<?php echo esc_attr( rootwp_field_name( 'social_' . $network ) ); ?>" value="<?php echo esc_attr( rootwp_panel_option( 'social_' . $network ) ); ?>" placeholder="<?php echo 'email' === $network ? 'voce@exemplo.com' : ( 'whatsapp' === $network ? 'https://wa.me/5511999999999' : 'https://' ); ?>"></td>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>

	<div class="rootwp-card">
		<h2><?php esc_html_e( 'Direitos autorais', 'rootwp' ); ?></h2>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><label for="as-footer-text"><?php esc_html_e( 'Texto da linha final', 'rootwp' ); ?></label></th>
				<td>
					<textarea id="as-footer-text" class="large-text" rows="2" name="<?php echo esc_attr( rootwp_field_name( 'footer_text' ) ); ?>"><?php echo esc_textarea( rootwp_panel_option( 'footer_text' ) ); ?></textarea>
					<p class="description"><?php esc_html_e( 'Aceita HTML simples. Use {ano} para o ano atual. Vazio = "© ano Nome do site".', 'rootwp' ); ?></p>
				</td>
			</tr>
		</table>
	</div>

	<div class="rootwp-card">
		<h2><?php esc_html_e( 'Exibição', 'rootwp' ); ?></h2>
		<?php foreach ( $flags as $key => $label ) : ?>
			<p>
				<label>
					<input type="checkbox" name="<?php echo esc_attr( rootwp_field_name( $key ) ); ?>" value="1" <?php checked( rootwp_panel_option( $key ), 1 ); ?>>
					<?php echo esc_html( $label ); ?>
				</label>
			</p>
		<?php endforeach; ?>
	</div>
	<?php
}

/**
 * Atalho "Tema" na barra de administração do site.
 *
 * @param WP_Admin_Bar $bar Barra.
 */
function rootwp_admin_bar( $bar ) {
	if ( ! current_user_can( 'edit_theme_options' ) || is_admin() ) {
		return;
	}
	$bar->add_node(
		array(
			'id'    => 'rootwp-panel',
			'title' => __( 'Opções do tema', 'rootwp' ),
			'href'  => admin_url( 'admin.php?page=rootwp' ),
		)
	);
}
add_action( 'admin_bar_menu', 'rootwp_admin_bar', 80 );
