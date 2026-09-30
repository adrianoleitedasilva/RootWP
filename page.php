<?php
/**
 * Páginas estáticas (Sobre, Contato, Cursos etc.).
 *
 * @package rootwp
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="container">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="single-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header>

				<?php if ( has_post_thumbnail() ) : ?>
					<figure class="single-thumb"><?php the_post_thumbnail( 'rootwp-wide' ); ?></figure>
				<?php endif; ?>

				<div class="single-layout single-layout--no-toc">
					<div>
						<div class="entry-content">
							<?php
							the_content();
							wp_link_pages();
							?>
						</div>
						<?php
						if ( comments_open() || get_comments_number() ) {
							comments_template();
						}
						?>
					</div>
				</div>
			</article>
		<?php endwhile; ?>
	</div>
</main>

<?php
get_footer();
