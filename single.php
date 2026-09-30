<?php
/**
 * Artigo individual.
 *
 * Monta as variantes de template (ver inc/categories.php):
 * - default:  texto + coluna com widgets/sumário à direita;
 * - focus:    sem coluna lateral, texto centralizado;
 * - cover:    imagem destacada em tela cheia com o título por cima;
 * - tutorial: sumário fixo à esquerda, passos numerados e quadro de nível/tempo;
 * - video:    o primeiro vídeo do post vira destaque no topo.
 *
 * @package rootwp
 */

get_header();
?>

<main id="primary" class="site-main">
	<?php
	while ( have_posts() ) :
		the_post();
		$variant = rootwp_post_variant();

		// Sumário antes de tudo: ele lê o conteúdo e fica em cache para o resto da página.
		$has_toc     = rootwp_has_toc();
		$has_widgets = 'default' === $variant || 'cover' === $variant || 'video' === $variant ? rootwp_has_sidebar( 'single' ) : false;

		if ( 'focus' === $variant ) {
			$has_toc = false;
		}

		$video = 'video' === $variant ? rootwp_featured_video() : '';
		$cover = 'cover' === $variant && has_post_thumbnail();

		if ( 'tutorial' === $variant ) {
			$layout = $has_toc ? 'single-layout single-layout--toc-left' : 'single-layout single-layout--no-toc';
		} elseif ( 'focus' === $variant ) {
			$layout = 'single-layout single-layout--focus';
		} else {
			$layout = 'single-layout' . ( $has_toc || $has_widgets ? '' : ' single-layout--no-toc' );
		}

		$tags  = get_the_tag_list( '', '' );
		$share = (bool) rootwp_panel_option( 'share_enabled' );
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-variant--' . $variant ); ?>>

			<?php if ( $cover ) : ?>
				<header class="single-cover">
					<?php the_post_thumbnail( 'full', array( 'class' => 'single-cover__img', 'loading' => 'eager', 'fetchpriority' => 'high', 'alt' => '' ) ); ?>
					<div class="container single-cover__inner">
						<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
						<?php rootwp_breadcrumbs(); ?>
						<?php if ( has_excerpt() ) : ?>
							<p class="entry-subtitle"><?php echo esc_html( get_the_excerpt() ); ?></p>
						<?php endif; ?>
						<div class="single-meta-row">
							<?php rootwp_entry_meta( true, true ); ?>
							<?php rootwp_reader_tools(); ?>
						</div>
					</div>
				</header>
			<?php endif; ?>

			<div class="container">
				<?php if ( ! $cover ) : ?>
					<header class="single-header">
						<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
						<?php rootwp_breadcrumbs(); ?>
						<?php if ( has_excerpt() ) : ?>
							<p class="entry-subtitle"><?php echo esc_html( get_the_excerpt() ); ?></p>
						<?php endif; ?>
						<div class="single-meta-row">
							<?php rootwp_entry_meta( true, true ); ?>
							<?php rootwp_reader_tools(); ?>
						</div>
					</header>
				<?php endif; ?>

				<?php if ( $video ) : ?>
					<div class="single-video"><?php echo $video; // phpcs:ignore WordPress.Security.EscapeOutput -- HTML do bloco do próprio post. ?></div>
				<?php elseif ( ! $cover && has_post_thumbnail() ) : ?>
					<figure class="single-thumb">
						<?php the_post_thumbnail( 'rootwp-wide', array( 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?>
						<?php if ( get_the_post_thumbnail_caption() ) : ?>
							<figcaption><?php the_post_thumbnail_caption(); ?></figcaption>
						<?php endif; ?>
					</figure>
				<?php endif; ?>

				<div class="<?php echo esc_attr( $layout ); ?>">
					<?php if ( 'tutorial' === $variant && $has_toc ) : ?>
						<div class="toc-aside toc-aside--left"><?php rootwp_the_toc(); ?></div>
					<?php endif; ?>

					<div class="single-main">
						<?php rootwp_series_box(); ?>
						<?php rootwp_old_post_notice(); ?>

						<?php
						if ( 'tutorial' === $variant ) {
							rootwp_tutorial_info();
						}
						?>

						<div class="entry-content">
							<?php
							the_content();
							wp_link_pages(
								array(
									'before' => '<div class="page-links">' . esc_html__( 'Páginas:', 'rootwp' ),
									'after'  => '</div>',
								)
							);
							?>
						</div>

						<?php rootwp_post_cta(); ?>

						<?php if ( $tags || $share ) : ?>
							<footer class="entry-footer">
								<?php
								if ( $tags ) {
									echo '<div class="tag-list">' . wp_kses_post( $tags ) . '</div>';
								}
								if ( $share ) {
									rootwp_share_links();
								}
								?>
							</footer>
						<?php endif; ?>

						<?php rootwp_series_nav(); ?>

						<?php
						if ( rootwp_panel_option( 'author_box' ) ) {
							rootwp_author_box();
						}

						the_post_navigation(
							array(
								'prev_text' => '<span class="meta-nav">' . esc_html__( '← Anterior', 'rootwp' ) . '</span><span class="post-title">%title</span>',
								'next_text' => '<span class="meta-nav">' . esc_html__( 'Próximo →', 'rootwp' ) . '</span><span class="post-title">%title</span>',
							)
						);

						if ( comments_open() || get_comments_number() ) {
							comments_template();
						}
						?>
					</div>

					<?php if ( 'tutorial' !== $variant && 'focus' !== $variant && ( $has_toc || $has_widgets ) ) : ?>
						<div class="toc-aside">
							<?php
							// Widgets primeiro: o sumário fixo fica abaixo e acompanha a rolagem.
							if ( $has_widgets ) {
								get_sidebar();
							}
							if ( $has_toc ) {
								rootwp_the_toc();
							}
							?>
						</div>
					<?php endif; ?>
				</div>

				<?php
				$related = rootwp_panel_option( 'related_enabled' ) ? rootwp_related_posts_query( (int) rootwp_panel_option( 'related_count' ) ) : null;
				if ( $related && $related->have_posts() ) :
					?>
					<section class="related-posts">
						<h2 class="section__title"><?php echo esc_html( rootwp_panel_option( 'related_title' ) ); ?></h2>
						<div class="post-grid">
							<?php
							while ( $related->have_posts() ) :
								$related->the_post();
								get_template_part( 'template-parts/content', 'card' );
							endwhile;
							wp_reset_postdata();
							?>
						</div>
					</section>
				<?php endif; ?>
			</div>
		</article>
	<?php endwhile; ?>
</main>

<?php
get_footer();
