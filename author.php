<?php
/**
 * Página do autor em formato de perfil: capa, cartão com foto, bio, redes e
 * números; artigos em lista com filtro por tema; e uma coluna própria com
 * "Sobre", temas e os mais comentados.
 *
 * @package rootwp
 */

get_header();

$author    = get_queried_object();
$author_id = $author->ID;
$bio       = get_the_author_meta( 'description', $author_id );
$website   = get_the_author_meta( 'user_url', $author_id );
$count     = (int) count_user_posts( $author_id, 'post', true );
$current   = (int) get_query_var( 'tema' ); // Filtro por tema (inc/discovery.php).
$base_url  = get_author_posts_url( $author_id );

// Todos os posts do autor (só IDs), para números, temas e comentários.
$ids      = $count ? get_posts(
	array(
		'author'         => $author_id,
		'posts_per_page' => 500,
		'fields'         => 'ids',
		'orderby'        => 'date',
		'order'          => 'ASC',
		'no_found_rows'  => true,
	)
) : array();
$since    = $ids ? get_the_date( 'Y', $ids[0] ) : '';
$comments = 0;
$topics   = array();
foreach ( $ids as $id ) {
	$comments += (int) get_comments_number( $id );
	foreach ( get_the_category( $id ) as $category ) {
		if ( ! isset( $topics[ $category->term_id ] ) ) {
			$topics[ $category->term_id ] = array( 'term' => $category, 'count' => 0 );
		}
		$topics[ $category->term_id ]['count']++;
	}
}
uasort(
	$topics,
	function ( $a, $b ) {
		return $b['count'] - $a['count'];
	}
);

$popular = $count ? new WP_Query(
	array(
		'author'              => $author_id,
		'posts_per_page'      => 4,
		'orderby'             => 'comment_count',
		'order'               => 'DESC',
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
) : null;

$stats = array(
	array( $count, _n( 'artigo', 'artigos', $count, 'rootwp' ) ),
	array( count( $topics ), _n( 'tema', 'temas', count( $topics ), 'rootwp' ) ),
	array( $comments, _n( 'comentário recebido', 'comentários recebidos', $comments, 'rootwp' ) ),
);
if ( $since ) {
	$stats[] = array( $since, __( 'escrevendo desde', 'rootwp' ) );
}
?>

<main id="primary" class="site-main site-main--author">
	<div class="profile-cover" aria-hidden="true"></div>

	<div class="container">
		<header class="profile-card">
			<div class="profile-card__avatar"><?php echo get_avatar( $author_id, 160 ); ?></div>

			<div class="profile-card__main">
				<?php rootwp_breadcrumbs(); ?>
				<span class="profile-card__label"><?php esc_html_e( 'autor', 'rootwp' ); ?></span>
				<h1 class="profile-card__name"><?php echo esc_html( $author->display_name ); ?></h1>

				<?php if ( $bio ) : ?>
					<p class="profile-card__bio"><?php echo nl2br( esc_html( $bio ) ); ?></p>
				<?php elseif ( current_user_can( 'edit_user', $author_id ) ) : ?>
					<p class="author-box__bio author-box__bio--empty">
						<?php esc_html_e( 'Espaço para a sua bio.', 'rootwp' ); ?>
						<a href="<?php echo esc_url( get_edit_user_link( $author_id ) . '#description' ); ?>"><?php esc_html_e( 'Escrever em Usuários > Perfil', 'rootwp' ); ?></a>
					</p>
				<?php endif; ?>

				<div class="profile-card__links">
					<?php rootwp_author_social_links( $author_id, 'profile-card__social' ); ?>
					<?php if ( $website ) : ?>
						<a class="profile-card__site" href="<?php echo esc_url( $website ); ?>" target="_blank" rel="noopener me">
							<?php echo rootwp_icon( 'link' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<?php echo esc_html( preg_replace( '#^https?://(www\.)?#', '', untrailingslashit( $website ) ) ); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>

			<ul class="profile-stats">
				<?php foreach ( $stats as $stat ) : ?>
					<li><strong><?php echo esc_html( $stat[0] ); ?></strong><span><?php echo esc_html( $stat[1] ); ?></span></li>
				<?php endforeach; ?>
			</ul>
		</header>

		<div class="profile-layout">
			<section class="profile-posts" aria-labelledby="profile-posts-title">
				<div class="profile-posts__header">
					<h2 id="profile-posts-title" class="section__title">
						<?php
						if ( $current && isset( $topics[ $current ] ) ) {
							/* translators: %s: nome da categoria */
							echo esc_html( sprintf( __( 'Artigos sobre %s', 'rootwp' ), $topics[ $current ]['term']->name ) );
						} else {
							esc_html_e( 'Artigos', 'rootwp' );
						}
						?>
					</h2>

					<?php if ( count( $topics ) > 1 ) : ?>
						<ul class="search-filters profile-filters">
							<li><a class="<?php echo $current ? '' : 'is-active'; ?>" href="<?php echo esc_url( $base_url ); ?>"><?php esc_html_e( 'Todos', 'rootwp' ); ?> <span class="count"><?php echo (int) $count; ?></span></a></li>
							<?php foreach ( $topics as $id => $topic ) : ?>
								<li>
									<a class="<?php echo $current === $id ? 'is-active' : ''; ?>" href="<?php echo esc_url( add_query_arg( 'tema', $id, $base_url ) ); ?>"<?php echo rootwp_category_style_attr( $id ); // phpcs:ignore WordPress.Security.EscapeOutput ?>>
										<?php echo esc_html( $topic['term']->name ); ?> <span class="count"><?php echo (int) $topic['count']; ?></span>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>

				<?php if ( have_posts() ) : ?>
					<div class="post-list">
						<?php
						while ( have_posts() ) :
							the_post();
							get_template_part( 'template-parts/content', 'list' );
						endwhile;
						?>
					</div>
					<?php rootwp_pagination(); ?>
				<?php else : ?>
					<p class="archive-description"><?php esc_html_e( 'Nenhum artigo por aqui ainda.', 'rootwp' ); ?></p>
				<?php endif; ?>
			</section>

			<aside class="profile-aside" aria-label="<?php esc_attr_e( 'Sobre o autor', 'rootwp' ); ?>">
				<?php if ( $topics ) : ?>
					<section class="profile-box">
						<h2 class="profile-box__title"><?php esc_html_e( 'Temas', 'rootwp' ); ?></h2>
						<ul class="as-tags as-tags--pills as-tags--categories">
							<?php foreach ( $topics as $id => $topic ) : ?>
								<li><a class="as-tag" href="<?php echo esc_url( add_query_arg( 'tema', $id, $base_url ) ); ?>"<?php echo rootwp_category_style_attr( $id ); // phpcs:ignore WordPress.Security.EscapeOutput ?>><span class="as-tag__dot" aria-hidden="true"></span><?php echo esc_html( $topic['term']->name ); ?> <span class="count"><?php echo (int) $topic['count']; ?></span></a></li>
							<?php endforeach; ?>
						</ul>
					</section>
				<?php endif; ?>

				<?php if ( $popular && $popular->have_posts() && $comments ) : ?>
					<section class="profile-box">
						<h2 class="profile-box__title"><?php esc_html_e( 'Mais comentados', 'rootwp' ); ?></h2>
						<ol class="as-recent-posts profile-popular">
							<?php
							while ( $popular->have_posts() ) :
								$popular->the_post();
								?>
								<li>
									<div class="as-recent-posts__body">
										<a class="as-recent-posts__title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
										<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>">
											<?php
											$n = (int) get_comments_number();
											/* translators: %d: número de comentários */
											echo esc_html( sprintf( _n( '%d comentário', '%d comentários', $n, 'rootwp' ), $n ) );
											?>
										</time>
									</div>
								</li>
								<?php
							endwhile;
							wp_reset_postdata();
							?>
						</ol>
					</section>
				<?php endif; ?>

				<section class="profile-box profile-box--contact">
					<h2 class="profile-box__title"><?php esc_html_e( 'Acompanhe', 'rootwp' ); ?></h2>
					<p><?php esc_html_e( 'Receba os novos artigos pelo seu leitor de RSS.', 'rootwp' ); ?></p>
					<a class="btn btn--ghost" href="<?php echo esc_url( get_author_feed_link( $author_id ) ); ?>"><?php esc_html_e( 'Assinar o feed', 'rootwp' ); ?></a>
				</section>
			</aside>
		</div>
	</div>
</main>

<?php
get_footer();
