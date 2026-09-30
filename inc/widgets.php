<?php
/**
 * Widgets próprios do tema: Busca, Posts recentes, Comentários recentes,
 * Categorias e Arquivos. Aparecem em Aparência > Widgets com o prefixo
 * "Root WP –".
 *
 * @package rootwp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Base com utilitários de formulário compartilhados pelos widgets do tema.
 */
abstract class Rootwp_Widget extends WP_Widget {

	/**
	 * Valores padrão das opções do widget.
	 *
	 * @var array
	 */
	protected $defaults = array();

	/**
	 * Registra o widget.
	 *
	 * @param string $id          Identificador.
	 * @param string $name        Nome exibido no painel.
	 * @param string $description Descrição.
	 * @param string $classname   Classe CSS do widget.
	 */
	public function __construct( $id, $name, $description, $classname ) {
		parent::__construct(
			'rootwp_' . $id,
			/* translators: %s: nome do widget */
			sprintf( __( 'Root WP – %s', 'rootwp' ), $name ),
			array(
				'classname'                   => $classname,
				'description'                 => $description,
				'customize_selective_refresh' => true,
				'show_instance_in_rest'       => true,
			)
		);
	}

	/**
	 * Opções mescladas com os padrões.
	 *
	 * @param array $instance Opções salvas.
	 * @return array
	 */
	protected function options( $instance ) {
		return wp_parse_args( (array) $instance, $this->defaults );
	}

	/**
	 * Abre o widget e imprime o título.
	 *
	 * @param array  $args  Argumentos da área de widgets.
	 * @param string $title Título.
	 */
	protected function open( $args, $title ) {
		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput
		if ( $title ) {
			echo $args['before_title'] . esc_html( apply_filters( 'widget_title', $title, array(), $this->id_base ) ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput
		}
	}

	/**
	 * Fecha o widget.
	 *
	 * @param array $args Argumentos da área de widgets.
	 */
	protected function close( $args ) {
		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput
	}

	/**
	 * Campo de texto.
	 *
	 * @param array  $o     Opções.
	 * @param string $key   Opção.
	 * @param string $label Rótulo.
	 */
	protected function text_field( $o, $key, $label ) {
		printf(
			'<p><label for="%1$s">%2$s</label><input class="widefat" type="text" id="%1$s" name="%3$s" value="%4$s"></p>',
			esc_attr( $this->get_field_id( $key ) ),
			esc_html( $label ),
			esc_attr( $this->get_field_name( $key ) ),
			esc_attr( $o[ $key ] )
		);
	}

	/**
	 * Campo numérico.
	 *
	 * @param array  $o     Opções.
	 * @param string $key   Opção.
	 * @param string $label Rótulo.
	 * @param int    $min   Mínimo.
	 * @param int    $max   Máximo.
	 */
	protected function number_field( $o, $key, $label, $min, $max ) {
		printf(
			'<p><label for="%1$s">%2$s</label> <input class="tiny-text" type="number" id="%1$s" name="%3$s" value="%4$d" min="%5$d" max="%6$d"></p>',
			esc_attr( $this->get_field_id( $key ) ),
			esc_html( $label ),
			esc_attr( $this->get_field_name( $key ) ),
			(int) $o[ $key ],
			(int) $min,
			(int) $max
		);
	}

	/**
	 * Checkbox.
	 *
	 * @param array  $o     Opções.
	 * @param string $key   Opção.
	 * @param string $label Rótulo.
	 */
	protected function checkbox( $o, $key, $label ) {
		printf(
			'<p><input class="checkbox" type="checkbox" id="%1$s" name="%2$s" value="1" %3$s> <label for="%1$s">%4$s</label></p>',
			esc_attr( $this->get_field_id( $key ) ),
			esc_attr( $this->get_field_name( $key ) ),
			checked( ! empty( $o[ $key ] ), true, false ),
			esc_html( $label )
		);
	}

	/**
	 * Lista de opções (select).
	 *
	 * @param array  $o       Opções.
	 * @param string $key     Opção.
	 * @param string $label   Rótulo.
	 * @param array  $choices valor => rótulo.
	 */
	protected function select_field( $o, $key, $label, $choices ) {
		printf( '<p><label for="%1$s">%2$s</label><select class="widefat" id="%1$s" name="%3$s">', esc_attr( $this->get_field_id( $key ) ), esc_html( $label ), esc_attr( $this->get_field_name( $key ) ) );
		foreach ( $choices as $value => $text ) {
			printf( '<option value="%1$s" %2$s>%3$s</option>', esc_attr( $value ), selected( (string) $o[ $key ], (string) $value, false ), esc_html( $text ) );
		}
		echo '</select></p>';
	}

	/**
	 * Campo de URL com botão para escolher da biblioteca de mídia.
	 *
	 * @param array  $o     Opções.
	 * @param string $key   Opção.
	 * @param string $label Rótulo.
	 * @param string $type  Tipo de mídia (image, video, audio).
	 */
	protected function media_field( $o, $key, $label, $type ) {
		printf(
			'<p><label for="%1$s">%2$s</label><span class="as-widget-media"><input class="widefat" type="url" id="%1$s" name="%3$s" value="%4$s"><button type="button" class="button as-widget-media__button" data-target="%1$s" data-type="%5$s">%6$s</button></span></p>',
			esc_attr( $this->get_field_id( $key ) ),
			esc_html( $label ),
			esc_attr( $this->get_field_name( $key ) ),
			esc_attr( $o[ $key ] ),
			esc_attr( $type ),
			esc_html__( 'Escolher', 'rootwp' )
		);
	}

	/**
	 * Sanitiza as opções conforme o tipo do valor padrão.
	 *
	 * @param array $new_instance Novas opções.
	 * @param array $old_instance Opções anteriores.
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$clean = array();
		foreach ( $this->defaults as $key => $default ) {
			$value = isset( $new_instance[ $key ] ) ? $new_instance[ $key ] : '';
			if ( is_bool( $default ) ) {
				$clean[ $key ] = ! empty( $value );
			} elseif ( is_int( $default ) ) {
				$clean[ $key ] = absint( $value );
			} else {
				$clean[ $key ] = sanitize_text_field( $value );
			}
		}
		return $this->validate( $clean );
	}

	/**
	 * Ajustes finais de cada widget (limites, valores permitidos).
	 *
	 * @param array $clean Opções sanitizadas.
	 * @return array
	 */
	protected function validate( $clean ) {
		return $clean;
	}
}

/**
 * Busca compacta.
 */
class Rootwp_Widget_Search extends Rootwp_Widget {

	/**
	 * Padrões.
	 *
	 * @var array
	 */
	protected $defaults = array(
		'title'       => '',
		'placeholder' => '',
	);

	/**
	 * Construtor.
	 */
	public function __construct() {
		parent::__construct( 'search', __( 'Busca', 'rootwp' ), __( 'Campo de busca compacto com botão de lupa.', 'rootwp' ), 'widget_search as-widget-search' );
	}

	/**
	 * Saída.
	 *
	 * @param array $args     Argumentos.
	 * @param array $instance Opções.
	 */
	public function widget( $args, $instance ) {
		$o           = $this->options( $instance );
		$placeholder = $o['placeholder'] ? $o['placeholder'] : __( 'Buscar artigos…', 'rootwp' );
		$field_id    = $this->id . '-field';

		$this->open( $args, $o['title'] );
		?>
		<form role="search" method="get" class="as-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<label class="screen-reader-text" for="<?php echo esc_attr( $field_id ); ?>"><?php esc_html_e( 'Buscar', 'rootwp' ); ?></label>
			<input type="search" id="<?php echo esc_attr( $field_id ); ?>" class="as-search__input" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php echo esc_attr( $placeholder ); ?>">
			<button type="submit" class="as-search__button" aria-label="<?php esc_attr_e( 'Buscar', 'rootwp' ); ?>"><?php echo rootwp_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></button>
		</form>
		<?php
		$this->close( $args );
	}

	/**
	 * Formulário.
	 *
	 * @param array $instance Opções.
	 */
	public function form( $instance ) {
		$o = $this->options( $instance );
		$this->text_field( $o, 'title', __( 'Título (opcional):', 'rootwp' ) );
		$this->text_field( $o, 'placeholder', __( 'Texto de exemplo no campo (vazio = "Buscar artigos…"):', 'rootwp' ) );
	}
}

/**
 * Posts recentes numerados, com data e miniatura opcionais.
 */
class Rootwp_Widget_Recent_Posts extends Rootwp_Widget {

	/**
	 * Padrões.
	 *
	 * @var array
	 */
	protected $defaults = array(
		'title'      => '',
		'number'     => 5,
		'category'   => 0,
		'show_date'  => true,
		'show_thumb' => false,
	);

	/**
	 * Construtor.
	 */
	public function __construct() {
		$this->defaults['title'] = __( 'Posts recentes', 'rootwp' );
		parent::__construct( 'recent_posts', __( 'Posts recentes', 'rootwp' ), __( 'Lista numerada dos últimos artigos.', 'rootwp' ), 'as-widget-recent-posts' );
	}

	/**
	 * Saída.
	 *
	 * @param array $args     Argumentos.
	 * @param array $instance Opções.
	 */
	public function widget( $args, $instance ) {
		$o     = $this->options( $instance );
		$query = new WP_Query(
			array(
				'posts_per_page'      => $o['number'],
				'cat'                 => $o['category'],
				'post__not_in'        => is_singular( 'post' ) ? array( get_queried_object_id() ) : array(),
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
			)
		);

		if ( ! $query->have_posts() ) {
			return;
		}

		$this->open( $args, $o['title'] );
		echo '<ol class="as-recent-posts' . ( $o['show_thumb'] ? ' has-thumbs' : '' ) . '">';
		while ( $query->have_posts() ) {
			$query->the_post();
			echo '<li>';
			if ( $o['show_thumb'] ) {
				echo '<a class="as-recent-posts__thumb" href="' . esc_url( get_permalink() ) . '" tabindex="-1" aria-hidden="true">';
				if ( has_post_thumbnail() ) {
					the_post_thumbnail( 'thumbnail', array( 'alt' => '' ) );
				} else {
					echo '<span>' . esc_html( mb_substr( get_the_title(), 0, 1 ) ) . '</span>';
				}
				echo '</a>';
			}
			echo '<div class="as-recent-posts__body">';
			printf( '<a class="as-recent-posts__title" href="%1$s">%2$s</a>', esc_url( get_permalink() ), esc_html( get_the_title() ) );
			if ( $o['show_date'] ) {
				printf( '<time datetime="%1$s">%2$s</time>', esc_attr( get_the_date( DATE_W3C ) ), esc_html( get_the_date() ) );
			}
			echo '</div></li>';
		}
		echo '</ol>';
		wp_reset_postdata();
		$this->close( $args );
	}

	/**
	 * Formulário.
	 *
	 * @param array $instance Opções.
	 */
	public function form( $instance ) {
		$o          = $this->options( $instance );
		$categories = array( 0 => __( 'Todas', 'rootwp' ) );
		foreach ( get_categories( array( 'hide_empty' => false ) ) as $cat ) {
			$categories[ $cat->term_id ] = $cat->name;
		}
		$this->text_field( $o, 'title', __( 'Título:', 'rootwp' ) );
		$this->number_field( $o, 'number', __( 'Quantidade:', 'rootwp' ), 1, 10 );
		$this->select_field( $o, 'category', __( 'Categoria:', 'rootwp' ), $categories );
		$this->checkbox( $o, 'show_date', __( 'Exibir data', 'rootwp' ) );
		$this->checkbox( $o, 'show_thumb', __( 'Exibir miniatura (no lugar da numeração)', 'rootwp' ) );
	}

	/**
	 * Limites.
	 *
	 * @param array $clean Opções.
	 * @return array
	 */
	protected function validate( $clean ) {
		$clean['number'] = min( 10, max( 1, $clean['number'] ) );
		return $clean;
	}
}

/**
 * Comentários recentes com trecho opcional.
 */
class Rootwp_Widget_Recent_Comments extends Rootwp_Widget {

	/**
	 * Padrões.
	 *
	 * @var array
	 */
	protected $defaults = array(
		'title'        => '',
		'number'       => 4,
		'show_excerpt' => true,
	);

	/**
	 * Construtor.
	 */
	public function __construct() {
		$this->defaults['title'] = __( 'Comentários', 'rootwp' );
		parent::__construct( 'recent_comments', __( 'Comentários recentes', 'rootwp' ), __( 'Últimos comentários aprovados, com autor e trecho.', 'rootwp' ), 'as-widget-recent-comments' );
	}

	/**
	 * Saída.
	 *
	 * @param array $args     Argumentos.
	 * @param array $instance Opções.
	 */
	public function widget( $args, $instance ) {
		$o        = $this->options( $instance );
		$comments = get_comments(
			array(
				'number'      => $o['number'],
				'status'      => 'approve',
				'post_status' => 'publish',
				'type'        => 'comment',
			)
		);

		if ( ! $comments ) {
			return;
		}

		$this->open( $args, $o['title'] );
		echo '<ul class="as-recent-comments">';
		foreach ( $comments as $comment ) {
			echo '<li>';
			printf(
				/* translators: 1: autor do comentário, 2: título do post */
				'<p class="as-recent-comments__meta">' . esc_html__( '%1$s em %2$s', 'rootwp' ) . '</p>',
				'<span class="as-recent-comments__author">' . esc_html( get_comment_author( $comment ) ) . '</span>',
				'<a class="as-recent-comments__post" href="' . esc_url( get_comment_link( $comment ) ) . '">' . esc_html( get_the_title( $comment->comment_post_ID ) ) . '</a>'
			);
			if ( $o['show_excerpt'] ) {
				echo '<p class="as-recent-comments__excerpt">' . esc_html( wp_trim_words( $comment->comment_content, 14, '…' ) ) . '</p>';
			}
			echo '</li>';
		}
		echo '</ul>';
		$this->close( $args );
	}

	/**
	 * Formulário.
	 *
	 * @param array $instance Opções.
	 */
	public function form( $instance ) {
		$o = $this->options( $instance );
		$this->text_field( $o, 'title', __( 'Título:', 'rootwp' ) );
		$this->number_field( $o, 'number', __( 'Quantidade:', 'rootwp' ), 1, 10 );
		$this->checkbox( $o, 'show_excerpt', __( 'Exibir trecho do comentário', 'rootwp' ) );
	}

	/**
	 * Limites.
	 *
	 * @param array $clean Opções.
	 * @return array
	 */
	protected function validate( $clean ) {
		$clean['number'] = min( 10, max( 1, $clean['number'] ) );
		return $clean;
	}
}

/**
 * Categorias em pílulas ou em lista.
 */
class Rootwp_Widget_Categories extends Rootwp_Widget {

	/**
	 * Padrões.
	 *
	 * @var array
	 */
	protected $defaults = array(
		'title'      => '',
		'style'      => 'pills',
		'show_count' => false,
		'orderby'    => 'name',
	);

	/**
	 * Construtor.
	 */
	public function __construct() {
		$this->defaults['title'] = __( 'Categorias', 'rootwp' );
		parent::__construct( 'categories', __( 'Categorias', 'rootwp' ), __( 'Categorias em pílulas ou em lista, com contagem opcional.', 'rootwp' ), 'as-widget-categories' );
	}

	/**
	 * Saída.
	 *
	 * @param array $args     Argumentos.
	 * @param array $instance Opções.
	 */
	public function widget( $args, $instance ) {
		$o    = $this->options( $instance );
		$list = wp_list_categories(
			array(
				'title_li'         => '',
				'echo'             => false,
				'show_count'       => $o['show_count'],
				'orderby'          => $o['orderby'],
				'order'            => 'count' === $o['orderby'] ? 'DESC' : 'ASC',
				'hierarchical'     => 'list' === $o['style'],
				'show_option_none' => '',
			)
		);

		if ( ! trim( wp_strip_all_tags( $list ) ) ) {
			return;
		}

		$this->open( $args, $o['title'] );
		echo '<ul class="as-categories as-categories--' . esc_attr( $o['style'] ) . '">' . $list . '</ul>'; // phpcs:ignore WordPress.Security.EscapeOutput
		$this->close( $args );
	}

	/**
	 * Formulário.
	 *
	 * @param array $instance Opções.
	 */
	public function form( $instance ) {
		$o = $this->options( $instance );
		$this->text_field( $o, 'title', __( 'Título:', 'rootwp' ) );
		$this->select_field(
			$o,
			'style',
			__( 'Estilo:', 'rootwp' ),
			array(
				'pills' => __( 'Pílulas (como as bubbles dos cards)', 'rootwp' ),
				'list'  => __( 'Lista', 'rootwp' ),
			)
		);
		$this->select_field(
			$o,
			'orderby',
			__( 'Ordenar por:', 'rootwp' ),
			array(
				'name'  => __( 'Nome', 'rootwp' ),
				'count' => __( 'Quantidade de posts', 'rootwp' ),
			)
		);
		$this->checkbox( $o, 'show_count', __( 'Exibir quantidade de posts', 'rootwp' ) );
	}

	/**
	 * Valores permitidos.
	 *
	 * @param array $clean Opções.
	 * @return array
	 */
	protected function validate( $clean ) {
		$clean['style']   = 'list' === $clean['style'] ? 'list' : 'pills';
		$clean['orderby'] = 'count' === $clean['orderby'] ? 'count' : 'name';
		return $clean;
	}
}

/**
 * Arquivos por mês ou ano.
 */
class Rootwp_Widget_Archives extends Rootwp_Widget {

	/**
	 * Padrões.
	 *
	 * @var array
	 */
	protected $defaults = array(
		'title'      => '',
		'type'       => 'monthly',
		'limit'      => 12,
		'show_count' => false,
	);

	/**
	 * Construtor.
	 */
	public function __construct() {
		$this->defaults['title'] = __( 'Arquivos', 'rootwp' );
		parent::__construct( 'archives', __( 'Arquivos', 'rootwp' ), __( 'Posts agrupados por mês ou ano.', 'rootwp' ), 'as-widget-archives' );
	}

	/**
	 * Saída.
	 *
	 * @param array $args     Argumentos.
	 * @param array $instance Opções.
	 */
	public function widget( $args, $instance ) {
		$o    = $this->options( $instance );
		$list = wp_get_archives(
			array(
				'type'            => $o['type'],
				'limit'           => $o['limit'],
				'show_post_count' => $o['show_count'],
				'echo'            => false,
			)
		);

		if ( ! $list ) {
			return;
		}

		$this->open( $args, $o['title'] );
		echo '<ul class="as-archives">' . $list . '</ul>'; // phpcs:ignore WordPress.Security.EscapeOutput
		$this->close( $args );
	}

	/**
	 * Formulário.
	 *
	 * @param array $instance Opções.
	 */
	public function form( $instance ) {
		$o = $this->options( $instance );
		$this->text_field( $o, 'title', __( 'Título:', 'rootwp' ) );
		$this->select_field(
			$o,
			'type',
			__( 'Agrupar por:', 'rootwp' ),
			array(
				'monthly' => __( 'Mês', 'rootwp' ),
				'yearly'  => __( 'Ano', 'rootwp' ),
			)
		);
		$this->number_field( $o, 'limit', __( 'Máximo de itens:', 'rootwp' ), 1, 60 );
		$this->checkbox( $o, 'show_count', __( 'Exibir quantidade de posts', 'rootwp' ) );
	}

	/**
	 * Valores permitidos.
	 *
	 * @param array $clean Opções.
	 * @return array
	 */
	protected function validate( $clean ) {
		$clean['type']  = 'yearly' === $clean['type'] ? 'yearly' : 'monthly';
		$clean['limit'] = min( 60, max( 1, $clean['limit'] ) );
		return $clean;
	}
}

/**
 * Nuvem de tags: tamanhos proporcionais ao uso ou pílulas uniformes.
 */
class Rootwp_Widget_Tag_Cloud extends Rootwp_Widget {

	/**
	 * Taxonomia exibida.
	 *
	 * @var string
	 */
	protected $taxonomy = 'post_tag';

	/**
	 * Padrões.
	 *
	 * @var array
	 */
	protected $defaults = array(
		'title'      => '',
		'number'     => 20,
		'orderby'    => 'count',
		'style'      => 'cloud',
		'show_count' => false,
		'hash'       => true,
	);

	/**
	 * Construtor.
	 */
	public function __construct() {
		$this->defaults['title'] = __( 'Tags', 'rootwp' );
		parent::__construct( 'tag_cloud', __( 'Nuvem de tags', 'rootwp' ), __( 'As tags mais usadas, em nuvem ou em pílulas.', 'rootwp' ), 'as-widget-tags' );
	}

	/**
	 * Saída.
	 *
	 * @param array $args     Argumentos.
	 * @param array $instance Opções.
	 */
	public function widget( $args, $instance ) {
		$o = $this->options( $instance );

		// Sempre pega as N mais usadas; a ordem de exibição é aplicada depois.
		$tags = get_terms(
			array(
				'taxonomy'   => $this->taxonomy,
				'orderby'    => 'count',
				'order'      => 'DESC',
				'number'     => $o['number'],
				'hide_empty' => true,
				'exclude'    => ! empty( $o['hide_default'] ) ? array( (int) get_option( 'default_category' ) ) : array(),
			)
		);

		if ( is_wp_error( $tags ) || ! $tags ) {
			return;
		}

		if ( 'name' === $o['orderby'] ) {
			usort(
				$tags,
				function ( $a, $b ) {
					return strcasecmp( remove_accents( $a->name ), remove_accents( $b->name ) );
				}
			);
		}

		// Tamanho de 1 a 5 conforme o uso (escala logarítmica, para uma tag muito
		// usada não deixar todas as outras minúsculas).
		$counts = wp_list_pluck( $tags, 'count' );
		$min    = log( min( $counts ) );
		$spread = log( max( $counts ) ) - $min;

		$this->open( $args, $o['title'] );
		$is_cat = 'category' === $this->taxonomy;
		echo '<ul class="as-tags as-tags--' . esc_attr( $o['style'] ) . ( $is_cat ? ' as-tags--categories' : '' ) . '">';
		foreach ( $tags as $tag ) {
			// Todas com a mesma quantidade: tamanho médio (3), e não o menor.
			$size   = $spread < 0.001 ? 3 : 1 + (int) round( 4 * ( log( $tag->count ) - $min ) / $spread );
			$marker = $is_cat ? '<span class="as-tag__dot" aria-hidden="true"></span>' : '<span class="as-tag__hash" aria-hidden="true">#</span>';
			printf(
				'<li><a class="as-tag as-tag--s%1$d" href="%2$s" aria-label="%3$s"%7$s>%4$s%5$s%6$s</a></li>',
				'cloud' === $o['style'] ? $size : 0,
				esc_url( get_term_link( $tag ) ),
				/* translators: 1: nome da tag, 2: quantidade de artigos */
				esc_attr( sprintf( _n( '%1$s (%2$d artigo)', '%1$s (%2$d artigos)', $tag->count, 'rootwp' ), $tag->name, $tag->count ) ),
				$o['hash'] ? $marker : '',
				esc_html( $tag->name ),
				$o['show_count'] ? ' <span class="count" aria-hidden="true">' . (int) $tag->count . '</span>' : '',
				$is_cat ? rootwp_category_style_attr( $tag->term_id ) : ''
			);
		}
		echo '</ul>';
		$this->close( $args );
	}

	/**
	 * Formulário.
	 *
	 * @param array $instance Opções.
	 */
	public function form( $instance ) {
		$o = $this->options( $instance );
		$this->text_field( $o, 'title', __( 'Título:', 'rootwp' ) );
		$this->number_field( $o, 'number', __( 'Quantidade de tags:', 'rootwp' ), 1, 60 );
		$this->select_field(
			$o,
			'style',
			__( 'Estilo:', 'rootwp' ),
			array(
				'cloud' => __( 'Nuvem (tags mais usadas ficam maiores)', 'rootwp' ),
				'pills' => __( 'Pílulas (todas do mesmo tamanho)', 'rootwp' ),
			)
		);
		$this->select_field(
			$o,
			'orderby',
			__( 'Ordem:', 'rootwp' ),
			array(
				'count' => __( 'Mais usadas primeiro', 'rootwp' ),
				'name'  => __( 'Alfabética', 'rootwp' ),
			)
		);
		$this->checkbox( $o, 'hash', __( 'Mostrar "#" antes de cada tag', 'rootwp' ) );
		$this->checkbox( $o, 'show_count', __( 'Mostrar a quantidade de artigos', 'rootwp' ) );
	}

	/**
	 * Valores permitidos.
	 *
	 * @param array $clean Opções.
	 * @return array
	 */
	protected function validate( $clean ) {
		$clean['number']  = min( 60, max( 1, $clean['number'] ) );
		$clean['style']   = 'pills' === $clean['style'] ? 'pills' : 'cloud';
		$clean['orderby'] = 'name' === $clean['orderby'] ? 'name' : 'count';
		return $clean;
	}
}

/**
 * Nuvem de categorias: mesma lógica da nuvem de tags, com a cor de cada
 * categoria e a quantidade de posts visível por padrão.
 */
class Rootwp_Widget_Category_Cloud extends Rootwp_Widget_Tag_Cloud {

	/**
	 * Taxonomia exibida.
	 *
	 * @var string
	 */
	protected $taxonomy = 'category';

	/**
	 * Padrões.
	 *
	 * @var array
	 */
	protected $defaults = array(
		'title'        => '',
		'number'       => 20,
		'orderby'      => 'count',
		'style'        => 'cloud',
		'show_count'   => true,
		'hash'         => true,
		'hide_default' => true,
	);

	/**
	 * Construtor (chama direto a base comum, com id e nome próprios).
	 */
	public function __construct() {
		$this->defaults['title'] = __( 'Categorias', 'rootwp' );
		Rootwp_Widget::__construct( 'category_cloud', __( 'Nuvem de categorias', 'rootwp' ), __( 'Categorias em nuvem ou pílulas, com a quantidade de posts de cada uma.', 'rootwp' ), 'as-widget-tags as-widget-category-cloud' );
	}

	/**
	 * Formulário.
	 *
	 * @param array $instance Opções.
	 */
	public function form( $instance ) {
		$o = $this->options( $instance );
		$this->text_field( $o, 'title', __( 'Título:', 'rootwp' ) );
		$this->number_field( $o, 'number', __( 'Quantidade de categorias:', 'rootwp' ), 1, 60 );
		$this->select_field(
			$o,
			'style',
			__( 'Estilo:', 'rootwp' ),
			array(
				'cloud' => __( 'Nuvem (categorias com mais posts ficam maiores)', 'rootwp' ),
				'pills' => __( 'Pílulas (todas do mesmo tamanho)', 'rootwp' ),
			)
		);
		$this->select_field(
			$o,
			'orderby',
			__( 'Ordem:', 'rootwp' ),
			array(
				'count' => __( 'Com mais posts primeiro', 'rootwp' ),
				'name'  => __( 'Alfabética', 'rootwp' ),
			)
		);
		$this->checkbox( $o, 'show_count', __( 'Mostrar a quantidade de posts', 'rootwp' ) );
		$this->checkbox( $o, 'hash', __( 'Mostrar o marcador na cor da categoria', 'rootwp' ) );
		$this->checkbox( $o, 'hide_default', __( 'Ocultar a categoria padrão ("Sem categoria")', 'rootwp' ) );
	}
}

/**
 * Ícone de play preenchido usado nas capas de vídeo e áudio.
 *
 * @return string
 */
function rootwp_play_icon() {
	return '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M8 5.5v13a1 1 0 0 0 1.5.86l10.5-6.5a1 1 0 0 0 0-1.72L9.5 4.64A1 1 0 0 0 8 5.5z"/></svg>';
}

/**
 * Logo do Spotify (círculo com as três ondas).
 *
 * @return string
 */
function rootwp_spotify_logo() {
	return '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="11" fill="#1ed760"/><path d="M6.5 9.3c3.8-1.1 8-.8 11.2 1M7.2 12.5c3.1-.9 6.4-.6 9.2.9M7.8 15.5c2.5-.7 5-.5 7.2.7" fill="none" stroke="#000" stroke-width="1.6" stroke-linecap="round"/></svg>';
}

/**
 * Extrai o ID de um vídeo do YouTube a partir de qualquer formato de link.
 *
 * @param string $url Link do vídeo.
 * @return string ID ou vazio.
 */
function rootwp_youtube_id( $url ) {
	if ( preg_match( '#(?:youtu\.be/|youtube(?:-nocookie)?\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/|live/|v/))([A-Za-z0-9_-]{11})#', $url, $m ) ) {
		return $m[1];
	}
	return '';
}

/**
 * Converte "1:30", "1m30s", "90" ou o parâmetro t= do link em segundos.
 *
 * @param string $start Tempo digitado.
 * @param string $url   Link (pode conter ?t=).
 * @return int
 */
function rootwp_time_to_seconds( $start, $url = '' ) {
	if ( '' === trim( $start ) && preg_match( '#[?&](?:t|start)=([0-9hms]+)#', $url, $m ) ) {
		$start = $m[1];
	}
	$start = trim( $start );
	if ( preg_match( '/^(\d+):(\d{1,2})(?::(\d{1,2}))?$/', $start, $m ) ) {
		return isset( $m[3] ) ? $m[1] * 3600 + $m[2] * 60 + $m[3] : $m[1] * 60 + $m[2];
	}
	if ( preg_match( '/^(?:(\d+)h)?(?:(\d+)m)?(?:(\d+)s?)?$/', $start, $m ) ) {
		return (int) ( isset( $m[1] ) ? (int) $m[1] * 3600 : 0 ) + ( isset( $m[2] ) ? (int) $m[2] * 60 : 0 ) + ( isset( $m[3] ) ? (int) $m[3] : 0 );
	}
	return 0;
}

/**
 * Vídeo do YouTube ou da biblioteca de mídia.
 */
class Rootwp_Widget_Video extends Rootwp_Widget {

	/**
	 * Padrões.
	 *
	 * @var array
	 */
	protected $defaults = array(
		'title'       => '',
		'source'      => 'youtube',
		'youtube_url' => '',
		'file_url'    => '',
		'poster_url'  => '',
		'format'      => 'landscape',
		'start'       => '',
		'caption'     => '',
		'file_mode'   => 'controls',
		'lite'        => true,
	);

	/**
	 * Construtor.
	 */
	public function __construct() {
		parent::__construct( 'video', __( 'Vídeo', 'rootwp' ), __( 'Vídeo do YouTube (inclusive Shorts) ou da biblioteca de mídia.', 'rootwp' ), 'as-widget-video' );
	}

	/**
	 * Saída.
	 *
	 * @param array $args     Argumentos.
	 * @param array $instance Opções.
	 */
	public function widget( $args, $instance ) {
		$o     = $this->options( $instance );
		$class = 'as-embed as-embed--' . ( 'portrait' === $o['format'] ? 'portrait' : 'landscape' );
		$label = $o['title'] ? $o['title'] : __( 'Vídeo', 'rootwp' );
		$html  = '';

		if ( 'file' === $o['source'] && $o['file_url'] ) {
			$ambient = 'ambient' === $o['file_mode'];
			$html    = sprintf(
				'<div class="%1$s"><video src="%2$s"%3$s preload="metadata" playsinline %4$s></video></div>',
				esc_attr( $class ),
				esc_url( $o['file_url'] ),
				$o['poster_url'] ? ' poster="' . esc_url( $o['poster_url'] ) . '"' : '',
				$ambient ? 'autoplay muted loop' : 'controls'
			);
		} elseif ( 'youtube' === $o['source'] ) {
			$id = rootwp_youtube_id( $o['youtube_url'] );
			if ( $id ) {
				// Player "limpo": os controles do YouTube ficam ocultos e o tema mostra
				// só play/pausa e tela cheia (ver assets/js/main.js). Pausado, volta a capa.
				$html = sprintf(
					'<div class="%1$s as-video" data-video="%2$s" data-start="%3$d" data-preload="%4$d">'
					. '<div class="as-video__player"></div>'
					. '<div class="as-video__shield" aria-hidden="true"></div>'
					. '<button type="button" class="as-video__cover" aria-label="%5$s"><img src="%6$s" alt="" loading="lazy" decoding="async"><span class="as-embed__play">%7$s</span></button>'
					. '<div class="as-video__bar">'
					. '<button type="button" class="as-video__btn as-video__toggle" aria-label="%8$s">%9$s</button>'
					. '<div class="as-video__volume">'
					. '<button type="button" class="as-video__btn as-video__mute" aria-label="%12$s">%13$s</button>'
					. '<input type="range" class="as-video__range" min="0" max="100" step="1" value="100" aria-label="%14$s">'
					. '</div>'
					. '<button type="button" class="as-video__btn as-video__fs" aria-label="%10$s">%11$s</button>'
					. '</div></div>',
					esc_attr( $class ),
					esc_attr( $id ),
					(int) rootwp_time_to_seconds( $o['start'], $o['youtube_url'] ),
					$o['lite'] ? 0 : 1,
					/* translators: %s: título do vídeo */
					esc_attr( sprintf( __( 'Assistir: %s', 'rootwp' ), $label ) ),
					esc_url( 'https://i.ytimg.com/vi/' . $id . '/hqdefault.jpg' ),
					rootwp_play_icon(),
					esc_attr__( 'Reproduzir / pausar', 'rootwp' ),
					'<svg class="icon-pause" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M7 5h3.5v14H7zM13.5 5H17v14h-3.5z"/></svg>'
					. '<svg class="icon-play" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M8 5.5v13a1 1 0 0 0 1.5.86l10.5-6.5a1 1 0 0 0 0-1.72L9.5 4.64A1 1 0 0 0 8 5.5z"/></svg>',
					esc_attr__( 'Tela cheia', 'rootwp' ),
					'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M4 9V4h5M20 9V4h-5M4 15v5h5M20 15v5h-5"/></svg>',
					esc_attr__( 'Ativar / desativar som', 'rootwp' ),
					'<svg class="icon-volume" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path fill="currentColor" d="M4 9h4l5-4v14l-5-4H4z"/><path d="M16.5 8.5a5 5 0 0 1 0 7M19 6a8.5 8.5 0 0 1 0 12"/></svg>'
					. '<svg class="icon-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path fill="currentColor" d="M4 9h4l5-4v14l-5-4H4z"/><path d="m17 9 5 6M22 9l-5 6"/></svg>',
					esc_attr__( 'Volume', 'rootwp' )
				);
			}
		}

		if ( ! $html ) {
			return;
		}

		$this->open( $args, $o['title'] );
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput
		if ( $o['caption'] ) {
			echo '<p class="as-embed__caption">' . esc_html( $o['caption'] ) . '</p>';
		}
		$this->close( $args );
	}

	/**
	 * Formulário.
	 *
	 * @param array $instance Opções.
	 */
	public function form( $instance ) {
		$o = $this->options( $instance );
		$this->text_field( $o, 'title', __( 'Título:', 'rootwp' ) );
		$this->select_field(
			$o,
			'source',
			__( 'Origem do vídeo:', 'rootwp' ),
			array(
				'youtube' => 'YouTube',
				'file'    => __( 'Biblioteca de mídia (MP4 / WebM)', 'rootwp' ),
			)
		);

		echo '<fieldset class="as-widget-group"><legend>YouTube</legend>';
		$this->text_field( $o, 'youtube_url', __( 'Link do vídeo (aceita Shorts):', 'rootwp' ) );
		$this->text_field( $o, 'start', __( 'Começar em (ex.: 1:30) — opcional:', 'rootwp' ) );
		$this->checkbox( $o, 'lite', __( 'Carregar o YouTube só ao clicar (recomendado: página mais rápida e sem cookies antes do clique)', 'rootwp' ) );
		echo '</fieldset>';

		echo '<fieldset class="as-widget-group"><legend>' . esc_html__( 'Biblioteca de mídia', 'rootwp' ) . '</legend>';
		$this->media_field( $o, 'file_url', __( 'Arquivo de vídeo:', 'rootwp' ), 'video' );
		$this->media_field( $o, 'poster_url', __( 'Imagem de capa (opcional):', 'rootwp' ), 'image' );
		$this->select_field(
			$o,
			'file_mode',
			__( 'Reprodução:', 'rootwp' ),
			array(
				'controls' => __( 'Com controles (o visitante dá play)', 'rootwp' ),
				'ambient'  => __( 'Automática, sem som e em loop (decorativo)', 'rootwp' ),
			)
		);
		echo '</fieldset>';

		$this->select_field(
			$o,
			'format',
			__( 'Formato:', 'rootwp' ),
			array(
				'landscape' => __( 'Horizontal (16:9)', 'rootwp' ),
				'portrait'  => __( 'Vertical (9:16) — Shorts e Reels', 'rootwp' ),
			)
		);
		$this->text_field( $o, 'caption', __( 'Legenda (opcional):', 'rootwp' ) );
	}

	/**
	 * Valores permitidos.
	 *
	 * @param array $clean Opções.
	 * @return array
	 */
	protected function validate( $clean ) {
		$clean['source']      = 'file' === $clean['source'] ? 'file' : 'youtube';
		$clean['format']      = 'portrait' === $clean['format'] ? 'portrait' : 'landscape';
		$clean['file_mode']   = 'ambient' === $clean['file_mode'] ? 'ambient' : 'controls';
		$clean['youtube_url'] = esc_url_raw( $clean['youtube_url'] );
		$clean['file_url']    = esc_url_raw( $clean['file_url'] );
		$clean['poster_url']  = esc_url_raw( $clean['poster_url'] );
		return $clean;
	}
}

/**
 * Player do Spotify: playlist, álbum, música, artista, podcast ou episódio.
 */
class Rootwp_Widget_Spotify extends Rootwp_Widget {

	/**
	 * Padrões.
	 *
	 * @var array
	 */
	protected $defaults = array(
		'title' => '',
		'url'   => '',
		'size'  => 'full',
		'theme' => 'color',
		'lite'  => true,
	);

	/**
	 * Construtor.
	 */
	public function __construct() {
		parent::__construct( 'spotify', 'Spotify', __( 'Playlist, álbum, música ou podcast do Spotify.', 'rootwp' ), 'as-widget-spotify' );
	}

	/**
	 * Tipo e ID a partir do link ou URI do Spotify.
	 *
	 * @param string $url Link.
	 * @return array|null array( tipo, id ).
	 */
	public static function parse( $url ) {
		if ( preg_match( '#(?:open\.spotify\.com/(?:intl-[a-z-]+/)?(?:embed/)?|spotify:)(playlist|album|track|artist|show|episode)[/:]([A-Za-z0-9]+)#', $url, $m ) ) {
			return array( $m[1], $m[2] );
		}
		return null;
	}

	/**
	 * Capa e título via oEmbed do Spotify (guardados por 1 semana).
	 *
	 * @param string $type Tipo.
	 * @param string $id   ID.
	 * @return array
	 */
	protected function info( $type, $id ) {
		$key  = 'as_spotify_' . md5( $type . $id );
		$info = get_transient( $key );
		if ( false === $info ) {
			$info     = array();
			$response = wp_remote_get( 'https://open.spotify.com/oembed?url=' . rawurlencode( 'https://open.spotify.com/' . $type . '/' . $id ), array( 'timeout' => 4 ) );
			if ( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {
				$data = json_decode( wp_remote_retrieve_body( $response ), true );
				$info = array(
					'title' => isset( $data['title'] ) ? $data['title'] : '',
					'thumb' => isset( $data['thumbnail_url'] ) ? $data['thumbnail_url'] : '',
				);
			}
			// Em caso de falha, tenta de novo em 1 hora em vez de 1 semana.
			set_transient( $key, $info, $info ? WEEK_IN_SECONDS : HOUR_IN_SECONDS );
		}
		return $info;
	}

	/**
	 * Saída.
	 *
	 * @param array $args     Argumentos.
	 * @param array $instance Opções.
	 */
	public function widget( $args, $instance ) {
		$o      = $this->options( $instance );
		$parsed = self::parse( $o['url'] );
		if ( ! $parsed ) {
			return;
		}

		list( $type, $id ) = $parsed;
		$height = 'compact' === $o['size'] ? 152 : 352;
		$src    = 'https://open.spotify.com/embed/' . $type . '/' . $id . '?utm_source=generator' . ( 'dark' === $o['theme'] ? '&theme=0' : '' );
		$types  = array(
			'playlist' => 'Playlist',
			'album'    => __( 'Álbum', 'rootwp' ),
			'track'    => __( 'Música', 'rootwp' ),
			'artist'   => __( 'Artista', 'rootwp' ),
			'show'     => 'Podcast',
			'episode'  => __( 'Episódio', 'rootwp' ),
		);

		$this->open( $args, $o['title'] );
		echo '<div class="as-spotify as-spotify--' . esc_attr( $o['size'] ) . '" style="height:' . (int) $height . 'px">';

		if ( $o['lite'] ) {
			$info  = $this->info( $type, $id );
			$name  = ! empty( $info['title'] ) ? $info['title'] : ( $o['title'] ? $o['title'] : 'Spotify' );
			$thumb = ! empty( $info['thumb'] ) ? $info['thumb'] : '';
			printf(
				'<button type="button" class="as-spotify__facade%5$s" data-embed="%1$s" data-title="%2$s" data-height="%3$d" aria-label="%4$s">',
				esc_url( $src ),
				esc_attr( $name ),
				(int) $height,
				/* translators: %s: nome da playlist, álbum etc. */
				esc_attr( sprintf( __( 'Ouvir no Spotify: %s', 'rootwp' ), $name ) ),
				$thumb ? '' : ' no-cover'
			);
			if ( $thumb ) {
				// Mesma arte, desfocada, como fundo do cartão.
				echo '<img class="as-spotify__backdrop" src="' . esc_url( $thumb ) . '" alt="" loading="lazy" decoding="async">';
				echo '<img class="as-spotify__cover" src="' . esc_url( $thumb ) . '" alt="" loading="lazy" decoding="async">';
			}
			echo '<span class="as-spotify__text">';
			echo '<span class="as-spotify__type">' . esc_html( $types[ $type ] ) . '</span>';
			echo '<span class="as-spotify__name">' . esc_html( $name ) . '</span>';
			echo '<span class="as-spotify__brand">' . rootwp_spotify_logo() . esc_html__( 'Ouvir no Spotify', 'rootwp' ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput
			echo '</span>';
			echo '<span class="as-spotify__play">' . rootwp_play_icon() . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput
			echo '</button>';
		} else {
			printf(
				'<iframe src="%1$s" title="%2$s" height="%3$d" loading="lazy" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"></iframe>',
				esc_url( $src ),
				esc_attr( $o['title'] ? $o['title'] : 'Spotify' ),
				(int) $height
			);
		}

		echo '</div>';
		$this->close( $args );
	}

	/**
	 * Formulário.
	 *
	 * @param array $instance Opções.
	 */
	public function form( $instance ) {
		$o = $this->options( $instance );
		$this->text_field( $o, 'title', __( 'Título:', 'rootwp' ) );
		$this->text_field( $o, 'url', __( 'Link do Spotify (playlist, álbum, música, artista, podcast ou episódio):', 'rootwp' ) );
		if ( $o['url'] && ! self::parse( $o['url'] ) ) {
			echo '<p class="as-widget-error">' . esc_html__( 'Link não reconhecido. Copie pelo Spotify em Compartilhar > Copiar link.', 'rootwp' ) . '</p>';
		}
		$this->select_field(
			$o,
			'size',
			__( 'Tamanho:', 'rootwp' ),
			array(
				'full'    => __( 'Completo, com a lista de faixas (352px)', 'rootwp' ),
				'compact' => __( 'Compacto, só a faixa atual (152px)', 'rootwp' ),
			)
		);
		$this->select_field(
			$o,
			'theme',
			__( 'Cor do player:', 'rootwp' ),
			array(
				'color' => __( 'Colorido (padrão do Spotify)', 'rootwp' ),
				'dark'  => __( 'Escuro', 'rootwp' ),
			)
		);
		$this->checkbox( $o, 'lite', __( 'Carregar o player só ao clicar (recomendado)', 'rootwp' ) );
	}

	/**
	 * Valores permitidos.
	 *
	 * @param array $clean Opções.
	 * @return array
	 */
	protected function validate( $clean ) {
		$clean['url']   = esc_url_raw( $clean['url'] );
		$clean['size']  = 'compact' === $clean['size'] ? 'compact' : 'full';
		$clean['theme'] = 'dark' === $clean['theme'] ? 'dark' : 'color';
		return $clean;
	}
}

/**
 * Registra os widgets do tema.
 */
function rootwp_register_widgets() {
	register_widget( 'Rootwp_Widget_Search' );
	register_widget( 'Rootwp_Widget_Recent_Posts' );
	register_widget( 'Rootwp_Widget_Recent_Comments' );
	register_widget( 'Rootwp_Widget_Categories' );
	register_widget( 'Rootwp_Widget_Archives' );
	register_widget( 'Rootwp_Widget_Tag_Cloud' );
	register_widget( 'Rootwp_Widget_Category_Cloud' );
	register_widget( 'Rootwp_Widget_Video' );
	register_widget( 'Rootwp_Widget_Spotify' );
}
add_action( 'widgets_init', 'rootwp_register_widgets' );

/**
 * Seletor de mídia nos formulários de widget (Aparência > Widgets e Personalizar).
 *
 * @param string $hook Página atual.
 */
function rootwp_widgets_admin_assets( $hook = '' ) {
	if ( 'widgets.php' !== $hook && 'customize_controls_enqueue_scripts' !== current_action() ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_script( 'rootwp-widgets-admin', get_template_directory_uri() . '/assets/js/widgets-admin.js', array( 'jquery' ), ROOTWP_VERSION, true );
	wp_register_style( 'rootwp-widgets-admin', false, array(), ROOTWP_VERSION );
	wp_enqueue_style( 'rootwp-widgets-admin' );
	wp_add_inline_style(
		'rootwp-widgets-admin',
		'.as-widget-group{border:1px solid #dcdcde;border-radius:4px;padding:0 12px;margin:0 0 12px}.as-widget-group legend{padding:0 4px;font-weight:600}.as-widget-media{display:flex;gap:6px}.as-widget-media input{flex:1}.as-widget-error{color:#b32d2e}'
	);
}
add_action( 'admin_enqueue_scripts', 'rootwp_widgets_admin_assets' );
add_action( 'customize_controls_enqueue_scripts', 'rootwp_widgets_admin_assets' );
