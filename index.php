<?php
/**
 * Listagem principal do blog (e modelo padrão de fallback).
 *
 * @package rootwp
 */

get_header();

$blog_page = (int) get_option( 'page_for_posts' );
?>

<main id="primary" class="site-main">
	<div class="container">
		<header class="page-header page-header--blog">
			<?php rootwp_breadcrumbs(); ?>
			<?php // Título oculto na tela, mas mantido para leitores de tela e buscadores (h1 da página). ?>
			<h1 class="page-title screen-reader-text">
				<?php echo $blog_page ? esc_html( get_the_title( $blog_page ) ) : esc_html__( 'Blog', 'rootwp' ); ?>
			</h1>
			<?php if ( $blog_page && has_excerpt( $blog_page ) ) : ?>
				<div class="archive-description"><?php echo wp_kses_post( get_the_excerpt( $blog_page ) ); ?></div>
			<?php endif; ?>
		</header>

		<div class="layout<?php echo rootwp_has_sidebar( 'archive' ) ? '' : ' layout--full'; ?>">
			<div>
				<?php if ( have_posts() ) : ?>
					<div class="post-grid">
						<?php
						$index = 0;
						while ( have_posts() ) :
							the_post();
							// O post mais recente da primeira página aparece em destaque.
							$featured = ( 0 === $index && ! is_paged() );
							get_template_part( 'template-parts/content', 'card', array( 'featured' => $featured ) );
							$index++;
						endwhile;
						?>
					</div>
					<?php rootwp_pagination(); ?>
				<?php else : ?>
					<?php get_template_part( 'template-parts/content', 'none' ); ?>
				<?php endif; ?>
			</div>

			<?php if ( rootwp_has_sidebar( 'archive' ) ) { get_sidebar(); } ?>
		</div>
	</div>
</main>

<?php
get_footer();
