<?php
/**
 * Layouts por categoria e templates de post.
 *
 * - Em Posts > Categorias, cada categoria define: layout da listagem, template
 *   padrão dos posts, cor e imagem de capa.
 * - No editor do post, "Modelo" permite trocar o template só daquele post.
 * - Prioridade: modelo do post > template da categoria > padrão.
 *
 * @package rootwp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Layouts disponíveis para a página da categoria.
 *
 * @return array
 */
function rootwp_category_layouts() {
	return array(
		'grid'     => __( 'Grade — cards em colunas, o mais recente em destaque', 'rootwp' ),
		'list'     => __( 'Lista — imagem à esquerda e resumo à direita', 'rootwp' ),
		'magazine' => __( 'Revista — um destaque grande e dois menores, depois a grade', 'rootwp' ),
		'compact'  => __( 'Compacta — só data e título, agrupados por mês', 'rootwp' ),
	);
}

/**
 * Templates de post: variante => array( rótulo, arquivo do modelo ).
 *
 * @return array
 */
function rootwp_post_templates() {
	return array(
		'default'  => array( __( 'Padrão', 'rootwp' ), '' ),
		'focus'    => array( __( 'Leitura focada', 'rootwp' ), 'templates/post-focus.php' ),
		'cover'    => array( __( 'Capa em tela cheia', 'rootwp' ), 'templates/post-cover.php' ),
		'tutorial' => array( __( 'Tutorial', 'rootwp' ), 'templates/post-tutorial.php' ),
		'video'    => array( __( 'Vídeo', 'rootwp' ), 'templates/post-video.php' ),
	);
}

/**
 * Lê um campo da categoria.
 *
 * @param int    $term_id ID da categoria.
 * @param string $key     layout|post_template|color|cover.
 * @return mixed
 */
function rootwp_category_meta( $term_id, $key ) {
	return get_term_meta( $term_id, 'rootwp_' . $key, true );
}

/**
 * Cor definida para a categoria (ou vazio).
 *
 * @param int $term_id ID da categoria.
 * @return string
 */
function rootwp_category_color( $term_id ) {
	$color = sanitize_hex_color( rootwp_category_meta( $term_id, 'color' ) );
	return $color ? $color : '';
}

/**
 * Atributo style com a cor da categoria, para bubbles e cartões.
 *
 * @param int $term_id ID da categoria.
 * @return string
 */
function rootwp_category_style_attr( $term_id ) {
	$color = rootwp_category_color( $term_id );
	if ( ! $color ) {
		return '';
	}
	return ' style="--cat: ' . esc_attr( $color ) . '; --cat-fg: ' . esc_attr( rootwp_contrast_text( $color ) ) . '"';
}

/**
 * Cor de texto legível sobre um fundo: branco para cores escuras,
 * quase preto para cores claras (luminância relativa, WCAG).
 *
 * @param string $hex Cor de fundo (#rgb ou #rrggbb).
 * @return string
 */
function rootwp_contrast_text( $hex ) {
	$hex = ltrim( $hex, '#' );
	if ( 3 === strlen( $hex ) ) {
		$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
	}
	$channels = array_map(
		function ( $c ) {
			$c = hexdec( $c ) / 255;
			return $c <= 0.03928 ? $c / 12.92 : pow( ( $c + 0.055 ) / 1.055, 2.4 );
		},
		str_split( $hex, 2 )
	);
	$luminance = 0.2126 * $channels[0] + 0.7152 * $channels[1] + 0.0722 * $channels[2];

	// Contraste com branco vs. com #1c2033 (texto do tema): fica o maior.
	$with_white = 1.05 / ( $luminance + 0.05 );
	$with_dark  = ( $luminance + 0.05 ) / 0.0636; // #1c2033: luminância 0,0136 + 0,05
	return $with_white >= $with_dark ? '#ffffff' : '#1c2033';
}

/**
 * "N artigos" no topo da página da categoria.
 *
 * @param WP_Term $term Categoria.
 */
function rootwp_category_count( $term ) {
	/* translators: %d: número de artigos */
	echo '<p class="category-count">' . esc_html( sprintf( _n( '%d artigo', '%d artigos', $term->count, 'rootwp' ), $term->count ) ) . '</p>';
}

/**
 * Variante de template do post: modelo do post > categoria > padrão.
 *
 * @param int|null $post_id ID do post.
 * @return string
 */
function rootwp_post_variant( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$slug    = get_page_template_slug( $post_id );

	foreach ( rootwp_post_templates() as $variant => $data ) {
		if ( $data[1] && $data[1] === $slug ) {
			return $variant;
		}
	}

	// Sem modelo no post: usa a primeira categoria que tiver um template definido.
	foreach ( get_the_category( $post_id ) as $category ) {
		$variant = rootwp_category_meta( $category->term_id, 'post_template' );
		if ( $variant && isset( rootwp_post_templates()[ $variant ] ) ) {
			return $variant;
		}
	}

	return 'default';
}

/**
 * Classe no body com a variante do post, para o CSS.
 *
 * @param array $classes Classes.
 * @return array
 */
function rootwp_post_variant_body_class( $classes ) {
	if ( is_singular( 'post' ) ) {
		$classes[] = 'post-variant-' . rootwp_post_variant( get_queried_object_id() );
	}
	return $classes;
}
add_filter( 'body_class', 'rootwp_post_variant_body_class' );

/* --------------------------------------------------------------------------
   Campos na tela da categoria
   -------------------------------------------------------------------------- */

/**
 * Campos do formulário (usados em "adicionar" e "editar").
 *
 * @param WP_Term|null $term Categoria (null ao adicionar).
 */
function rootwp_category_fields( $term = null ) {
	$id       = $term ? $term->term_id : 0;
	$layout   = $id ? rootwp_category_meta( $id, 'layout' ) : '';
	$template = $id ? rootwp_category_meta( $id, 'post_template' ) : '';
	$color    = $id ? rootwp_category_color( $id ) : '';
	$cover    = $id ? (int) rootwp_category_meta( $id, 'cover' ) : 0;
	$preview  = $cover ? wp_get_attachment_image_url( $cover, 'medium' ) : '';
	$is_edit  = (bool) $term;

	$fields = array(
		'layout'   => array(
			__( 'Layout da página da categoria', 'rootwp' ),
			function () use ( $layout ) {
				echo '<select name="rootwp_cat[layout]" id="rootwp-cat-layout">';
				echo '<option value="">' . esc_html__( 'Padrão (grade)', 'rootwp' ) . '</option>';
				foreach ( rootwp_category_layouts() as $value => $label ) {
					printf( '<option value="%1$s" %2$s>%3$s</option>', esc_attr( $value ), selected( $layout, $value, false ), esc_html( $label ) );
				}
				echo '</select>';
			},
			'',
		),
		'template' => array(
			__( 'Template padrão dos posts', 'rootwp' ),
			function () use ( $template ) {
				echo '<select name="rootwp_cat[post_template]" id="rootwp-cat-template">';
				foreach ( rootwp_post_templates() as $value => $data ) {
					printf( '<option value="%1$s" %2$s>%3$s</option>', esc_attr( 'default' === $value ? '' : $value ), selected( $template, 'default' === $value ? '' : $value, false ), esc_html( $data[0] ) );
				}
				echo '</select>';
			},
			__( 'Usado pelos posts desta categoria que não tiverem um "Modelo" próprio escolhido no editor.', 'rootwp' ),
		),
		'color'    => array(
			__( 'Cor da categoria', 'rootwp' ),
			function () use ( $color ) {
				echo '<input type="text" class="rootwp-color" name="rootwp_cat[color]" id="rootwp-cat-color" value="' . esc_attr( $color ) . '">';
			},
			__( 'Aplicada nas bubbles da categoria, nos cartões de tema e no topo da página da categoria. Vazio = cor principal do site.', 'rootwp' ),
		),
		'cover'    => array(
			__( 'Imagem de capa', 'rootwp' ),
			function () use ( $cover, $preview ) {
				?>
				<div class="rootwp-media" data-title="<?php esc_attr_e( 'Imagem de capa da categoria', 'rootwp' ); ?>">
					<div class="rootwp-media__preview"><?php if ( $preview ) : ?><img src="<?php echo esc_url( $preview ); ?>" alt=""><?php endif; ?></div>
					<input type="hidden" class="rootwp-media__id" name="rootwp_cat[cover]" value="<?php echo esc_attr( $cover ? $cover : '' ); ?>">
					<button type="button" class="button rootwp-media__select"><?php esc_html_e( 'Escolher imagem', 'rootwp' ); ?></button>
					<button type="button" class="button-link-delete rootwp-media__remove" <?php echo $cover ? '' : 'hidden'; ?>><?php esc_html_e( 'Remover', 'rootwp' ); ?></button>
				</div>
				<?php
			},
			__( 'Exibida como faixa no topo da página da categoria.', 'rootwp' ),
		),
	);

	wp_nonce_field( 'rootwp_category_meta', 'rootwp_category_nonce' );

	foreach ( $fields as $key => $field ) {
		if ( $is_edit ) {
			echo '<tr class="form-field rootwp-term-field"><th scope="row"><label for="rootwp-cat-' . esc_attr( $key ) . '">' . esc_html( $field[0] ) . '</label></th><td>';
			$field[1]();
			echo $field[2] ? '<p class="description">' . esc_html( $field[2] ) . '</p>' : '';
			echo '</td></tr>';
		} else {
			echo '<div class="form-field rootwp-term-field"><label for="rootwp-cat-' . esc_attr( $key ) . '">' . esc_html( $field[0] ) . '</label>';
			$field[1]();
			echo $field[2] ? '<p>' . esc_html( $field[2] ) . '</p>' : '';
			echo '</div>';
		}
	}
}
add_action( 'category_add_form_fields', 'rootwp_category_fields' );
add_action( 'category_edit_form_fields', 'rootwp_category_fields' );

/**
 * Salva os campos da categoria.
 *
 * @param int $term_id ID da categoria.
 */
function rootwp_save_category_fields( $term_id ) {
	if ( ! isset( $_POST['rootwp_category_nonce'], $_POST['rootwp_cat'] )
		|| ! wp_verify_nonce( sanitize_key( $_POST['rootwp_category_nonce'] ), 'rootwp_category_meta' )
		|| ! current_user_can( 'manage_categories' ) ) {
		return;
	}

	$data   = wp_unslash( (array) $_POST['rootwp_cat'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$clean  = array(
		'layout'        => isset( $data['layout'] ) && isset( rootwp_category_layouts()[ $data['layout'] ] ) ? $data['layout'] : '',
		'post_template' => isset( $data['post_template'] ) && isset( rootwp_post_templates()[ $data['post_template'] ] ) && 'default' !== $data['post_template'] ? $data['post_template'] : '',
		'color'         => isset( $data['color'] ) ? (string) sanitize_hex_color( $data['color'] ) : '',
		'cover'         => isset( $data['cover'] ) ? absint( $data['cover'] ) : 0,
	);

	foreach ( $clean as $key => $value ) {
		if ( $value ) {
			update_term_meta( $term_id, 'rootwp_' . $key, $value );
		} else {
			delete_term_meta( $term_id, 'rootwp_' . $key );
		}
	}
}
add_action( 'created_category', 'rootwp_save_category_fields' );
add_action( 'edited_category', 'rootwp_save_category_fields' );

/**
 * Coluna "Layout" na lista de categorias, para ver tudo de relance.
 *
 * @param array $columns Colunas.
 * @return array
 */
function rootwp_category_columns( $columns ) {
	$columns['rootwp_layout'] = __( 'Layout / template', 'rootwp' );
	return $columns;
}
add_filter( 'manage_edit-category_columns', 'rootwp_category_columns' );

/**
 * Conteúdo da coluna "Layout / template".
 *
 * @param string $content Conteúdo.
 * @param string $column  Coluna.
 * @param int    $term_id ID.
 * @return string
 */
function rootwp_category_column_content( $content, $column, $term_id ) {
	if ( 'rootwp_layout' !== $column ) {
		return $content;
	}
	$layouts   = array(
		'grid'     => __( 'Grade', 'rootwp' ),
		'list'     => __( 'Lista', 'rootwp' ),
		'magazine' => __( 'Revista', 'rootwp' ),
		'compact'  => __( 'Compacta', 'rootwp' ),
	);
	$templates = rootwp_post_templates();
	$layout    = rootwp_category_meta( $term_id, 'layout' );
	$template  = rootwp_category_meta( $term_id, 'post_template' );
	$color     = rootwp_category_color( $term_id );

	return sprintf(
		'%1$s%2$s<br><span class="description">%3$s</span>',
		$color ? '<span style="display:inline-block;width:10px;height:10px;border-radius:50%;margin-right:6px;background:' . esc_attr( $color ) . '"></span>' : '',
		esc_html( isset( $layouts[ $layout ] ) ? $layouts[ $layout ] : $layouts['grid'] ),
		/* translators: %s: nome do template */
		esc_html( sprintf( __( 'Posts: %s', 'rootwp' ), isset( $templates[ $template ] ) ? $templates[ $template ][0] : $templates['default'][0] ) )
	);
}
add_filter( 'manage_category_custom_column', 'rootwp_category_column_content', 10, 3 );

/* --------------------------------------------------------------------------
   Caixa "Tutorial" no editor do post
   -------------------------------------------------------------------------- */

/**
 * Níveis de dificuldade.
 *
 * @return array
 */
function rootwp_tutorial_levels() {
	return array(
		'iniciante'     => __( 'Iniciante', 'rootwp' ),
		'intermediario' => __( 'Intermediário', 'rootwp' ),
		'avancado'      => __( 'Avançado', 'rootwp' ),
	);
}

/**
 * Registra a caixa.
 */
function rootwp_tutorial_meta_box() {
	add_meta_box( 'rootwp_tutorial', __( 'Tutorial', 'rootwp' ), 'rootwp_tutorial_meta_box_html', 'post', 'side' );
}
add_action( 'add_meta_boxes', 'rootwp_tutorial_meta_box' );

/**
 * Conteúdo da caixa.
 *
 * @param WP_Post $post Post.
 */
function rootwp_tutorial_meta_box_html( $post ) {
	$level    = get_post_meta( $post->ID, 'rootwp_level', true );
	$duration = get_post_meta( $post->ID, 'rootwp_duration', true );
	wp_nonce_field( 'rootwp_tutorial', 'rootwp_tutorial_nonce' );
	?>
	<p class="description"><?php esc_html_e( 'Exibido no topo quando o post usa o template Tutorial.', 'rootwp' ); ?></p>
	<p>
		<label for="rootwp-level"><strong><?php esc_html_e( 'Nível', 'rootwp' ); ?></strong></label><br>
		<select id="rootwp-level" name="rootwp_level" style="width:100%">
			<option value=""><?php esc_html_e( '— Não informar —', 'rootwp' ); ?></option>
			<?php foreach ( rootwp_tutorial_levels() as $value => $label ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $level, $value ); ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>
	</p>
	<p>
		<label for="rootwp-duration"><strong><?php esc_html_e( 'Tempo estimado', 'rootwp' ); ?></strong></label><br>
		<input type="text" id="rootwp-duration" name="rootwp_duration" value="<?php echo esc_attr( $duration ); ?>" placeholder="<?php esc_attr_e( 'Ex.: 30 min', 'rootwp' ); ?>" style="width:100%">
	</p>
	<?php
}

/**
 * Salva a caixa "Tutorial".
 *
 * @param int $post_id ID do post.
 */
function rootwp_save_tutorial_meta( $post_id ) {
	if ( ! isset( $_POST['rootwp_tutorial_nonce'] )
		|| ! wp_verify_nonce( sanitize_key( $_POST['rootwp_tutorial_nonce'] ), 'rootwp_tutorial' )
		|| ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		|| ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$level    = isset( $_POST['rootwp_level'] ) ? sanitize_key( wp_unslash( $_POST['rootwp_level'] ) ) : '';
	$duration = isset( $_POST['rootwp_duration'] ) ? sanitize_text_field( wp_unslash( $_POST['rootwp_duration'] ) ) : '';

	if ( isset( rootwp_tutorial_levels()[ $level ] ) ) {
		update_post_meta( $post_id, 'rootwp_level', $level );
	} else {
		delete_post_meta( $post_id, 'rootwp_level' );
	}

	if ( $duration ) {
		update_post_meta( $post_id, 'rootwp_duration', $duration );
	} else {
		delete_post_meta( $post_id, 'rootwp_duration' );
	}
}
add_action( 'save_post_post', 'rootwp_save_tutorial_meta' );

/**
 * Quadro "Nível / Tempo / Passos" do template Tutorial.
 */
function rootwp_tutorial_info() {
	$levels   = rootwp_tutorial_levels();
	$level    = get_post_meta( get_the_ID(), 'rootwp_level', true );
	$duration = get_post_meta( get_the_ID(), 'rootwp_duration', true );
	$steps    = count(
		array_filter(
			rootwp_get_toc(),
			function ( $item ) {
				return 2 === $item['level'];
			}
		)
	);

	$items = array();
	if ( isset( $levels[ $level ] ) ) {
		$items[] = array( __( 'Nível', 'rootwp' ), $levels[ $level ], 'level-' . $level );
	}
	if ( $duration ) {
		$items[] = array( __( 'Tempo estimado', 'rootwp' ), $duration, 'duration' );
	}
	if ( $steps ) {
		/* translators: %d: número de passos */
		$items[] = array( __( 'Passos', 'rootwp' ), sprintf( _n( '%d passo', '%d passos', $steps, 'rootwp' ), $steps ), 'steps' );
	}

	if ( ! $items ) {
		return;
	}

	echo '<dl class="tutorial-info">';
	foreach ( $items as $item ) {
		printf( '<div class="tutorial-info__item tutorial-info__item--%3$s"><dt>%1$s</dt><dd>%2$s</dd></div>', esc_html( $item[0] ), esc_html( $item[1] ), esc_attr( $item[2] ) );
	}
	echo '</dl>';
}

/* --------------------------------------------------------------------------
   Template Vídeo: o primeiro vídeo do post vira destaque no topo
   -------------------------------------------------------------------------- */

/**
 * Encontra o primeiro bloco de vídeo (YouTube, Vimeo ou arquivo) do post.
 *
 * @param int $post_id ID do post.
 * @return array|null Bloco.
 */
function rootwp_find_video_block( $post_id ) {
	foreach ( parse_blocks( get_post_field( 'post_content', $post_id ) ) as $block ) {
		$provider = isset( $block['attrs']['providerNameSlug'] ) ? $block['attrs']['providerNameSlug'] : '';
		if ( 'core/video' === $block['blockName']
			|| ( 'core/embed' === $block['blockName'] && in_array( $provider, array( 'youtube', 'vimeo' ), true ) ) ) {
			return $block;
		}
	}
	return null;
}

/**
 * Identificador de um bloco pelo tipo e atributos (URL, ID do arquivo...).
 * Não usa o HTML porque o WordPress transforma o link em player antes de
 * processar os blocos, e o HTML muda no caminho.
 *
 * @param array $block Bloco.
 * @return string
 */
function rootwp_block_key( $block ) {
	return $block['blockName'] . '|' . wp_json_encode( isset( $block['attrs'] ) ? $block['attrs'] : array() );
}

/**
 * HTML do vídeo em destaque e, a partir daqui, remove esse bloco do conteúdo
 * para ele não aparecer duas vezes.
 *
 * @return string
 */
function rootwp_featured_video() {
	$block = rootwp_find_video_block( get_the_ID() );
	if ( ! $block ) {
		return '';
	}

	$html = render_block( $block );
	$html = isset( $GLOBALS['wp_embed'] ) ? $GLOBALS['wp_embed']->autoembed( $html ) : $html;

	// Só depois de gerar o destaque: a partir daqui, o mesmo bloco é pulado no conteúdo.
	$GLOBALS['rootwp_skip_block'] = rootwp_block_key( $block );

	return $html;
}

/**
 * Pula (uma vez) o bloco que já foi exibido como vídeo em destaque.
 *
 * @param string $content Bloco renderizado.
 * @param array  $block   Bloco.
 * @return string
 */
function rootwp_skip_featured_video_block( $content, $block ) {
	if ( ! empty( $GLOBALS['rootwp_skip_block'] ) && rootwp_block_key( $block ) === $GLOBALS['rootwp_skip_block'] ) {
		$GLOBALS['rootwp_skip_block'] = '';
		return '';
	}
	return $content;
}
add_filter( 'render_block', 'rootwp_skip_featured_video_block', 5, 2 );
