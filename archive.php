<?php
/**
 * Arquivos: categorias, tags, autor e datas.
 *
 * @package rootwp
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="container">
		<header class="page-header">
			<?php rootwp_breadcrumbs(); ?>
			<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
			<?php the_archive_description( '<div class="archive-description">', '</div>' ); ?>
		</header>

		<div class="layout<?php echo rootwp_has_sidebar( 'archive' ) ? '' : ' layout--full'; ?>">
			<div>
				<?php if ( have_posts() ) : ?>
					<div class="post-grid">
						<?php
						while ( have_posts() ) :
							the_post();
							get_template_part( 'template-parts/content', 'card' );
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
