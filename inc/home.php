<?php
/**
 * Seções da página inicial (Root WP > Página inicial).
 *
 * @package rootwp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Cabeçalho de seção com título, subtítulo e botão opcional.
 *
 * @param string $title    Título.
 * @param string $subtitle Subtítulo.
 * @param string $button   Texto do botão.
 * @param string $url      Link do botão.
 */
function rootwp_home_section_header( $title, $subtitle = '', $button = '', $url = '' ) {
	if ( ! $title && ! $subtitle && ! $button ) {
		return;
	}
	?>
	<div class="section__header">
		<div>
			<?php if ( $title ) : ?>
				<h2 class="section__title"><?php echo esc_html( $title ); ?></h2>
			<?php endif; ?>
			<?php if ( $subtitle ) : ?>
				<p class="section__subtitle"><?php echo esc_html( $subtitle ); ?></p>
			<?php endif; ?>
		</div>
		<?php if ( $button && $url ) : ?>
			<a class="btn btn--ghost" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $button ); ?></a>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Conteúdo escrito no editor da própria página inicial, se houver.
 */
function rootwp_home_content() {
	while ( have_posts() ) {
		the_post();
		if ( trim( get_the_content() ) ) {
			?>
			<section class="section">
				<div class="container"><div class="entry-content home-content"><?php the_content(); ?></div></div>
			</section>
			<?php
		}
	}
	rewind_posts();
}

/**
 * Artigos recentes.
 *
 * @param string $blog_url Link da página do blog.
 */
function rootwp_home_recent( $blog_url ) {
	if ( ! rootwp_panel_option( 'home_recent_enabled' ) ) {
		return;
	}

	$category = (int) rootwp_panel_option( 'home_recent_category' );
	$recent   = new WP_Query(
		array(
			'posts_per_page'      => (int) rootwp_panel_option( 'home_posts_count' ),
			'cat'                 => $category,
			'ignore_sticky_posts' => false,
			'no_found_rows'       => true,
		)
	);

	if ( ! $recent->have_posts() ) {
		return;
	}

	$more_url = $category && get_term( $category, 'category' ) instanceof WP_Term ? get_category_link( $category ) : $blog_url;
	?>
	<section class="section">
		<div class="container">
			<?php
			rootwp_home_section_header(
				rootwp_panel_option( 'home_recent_title' ),
				rootwp_panel_option( 'home_recent_subtitle' ),
				rootwp_panel_option( 'home_recent_button' ),
				$more_url
			);
			?>
			<div class="post-grid">
				<?php
				while ( $recent->have_posts() ) {
					$recent->the_post();
					get_template_part( 'template-parts/content', 'card' );
				}
				wp_reset_postdata();
				?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Categorias escolhidas para "Explore por tema".
 *
 * @return WP_Term[]
 */
function rootwp_home_topic_terms() {
	if ( 'manual' === rootwp_panel_option( 'home_topics_mode' ) ) {
		$ids = array_filter( array_map( 'absint', explode( ',', rootwp_panel_option( 'home_topics_selected' ) ) ) );
		if ( ! $ids ) {
			return array();
		}
		return get_categories(
			array(
				'include'    => $ids,
				'orderby'    => 'include',
				'hide_empty' => false,
			)
		);
	}

	return get_categories(
		array(
			'orderby'    => 'count',
			'order'      => 'DESC',
			'number'     => (int) rootwp_panel_option( 'home_topics_number' ),
			'hide_empty' => true,
		)
	);
}

/**
 * Grade "Explore por tema".
 */
function rootwp_home_topics() {
	if ( ! rootwp_panel_option( 'show_topics' ) ) {
		return;
	}

	$topics = rootwp_home_topic_terms();
	if ( ! $topics ) {
		return;
	}
	?>
	<section class="section section--alt">
		<div class="container">
			<?php rootwp_home_section_header( rootwp_panel_option( 'home_topics_title' ), rootwp_panel_option( 'home_topics_subtitle' ) ); ?>
			<div class="topic-grid">
				<?php foreach ( $topics as $topic ) : ?>
					<a class="topic-card" href="<?php echo esc_url( get_category_link( $topic ) ); ?>"<?php echo rootwp_category_style_attr( $topic->term_id ); // phpcs:ignore WordPress.Security.EscapeOutput ?>>
						<span class="topic-card__name"><?php echo esc_html( $topic->name ); ?></span>
						<?php if ( rootwp_panel_option( 'home_topics_show_count' ) ) : ?>
							<span class="topic-card__count">
								<?php
								/* translators: %d: número de artigos */
								printf( esc_html( _n( '%d artigo', '%d artigos', $topic->count, 'rootwp' ) ), (int) $topic->count );
								?>
							</span>
						<?php endif; ?>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}
