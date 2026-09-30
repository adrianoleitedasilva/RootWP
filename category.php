<?php
/**
 * Página de categoria. O layout, a cor e a imagem de capa são definidos em
 * Posts > Categorias > editar (ver inc/categories.php).
 *
 * @package rootwp
 */

get_header();

$term    = get_queried_object();
$layout  = rootwp_category_meta( $term->term_id, 'layout' );
$layout  = isset( rootwp_category_layouts()[ $layout ] ) ? $layout : 'grid';
$cover   = (int) rootwp_category_meta( $term->term_id, 'cover' );
$first   = ! is_paged();
$sidebar = rootwp_has_sidebar( 'archive' );
?>

<main id="primary" class="site-main category-page category-page--<?php echo esc_attr( $layout ); ?>"<?php echo rootwp_category_style_attr( $term->term_id ); // phpcs:ignore WordPress.Security.EscapeOutput ?>>

	<?php if ( $cover ) : ?>
		<header class="category-cover">
			<?php echo wp_get_attachment_image( $cover, 'full', false, array( 'class' => 'category-cover__img', 'alt' => '', 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?>
			<div class="container category-cover__inner">
				<?php rootwp_breadcrumbs(); ?>
				<h1 class="page-title"><?php single_cat_title(); ?></h1>
				<?php the_archive_description( '<div class="archive-description">', '</div>' ); ?>
				<?php rootwp_category_count( $term ); ?>
			</div>
		</header>
	<?php endif; ?>

	<div class="container">
		<?php if ( ! $cover ) : ?>
			<header class="page-header category-header">
				<?php rootwp_breadcrumbs(); ?>
				<h1 class="page-title"><?php single_cat_title(); ?></h1>
				<?php the_archive_description( '<div class="archive-description">', '</div>' ); ?>
				<?php rootwp_category_count( $term ); ?>
			</header>
		<?php endif; ?>

		<div class="layout<?php echo $sidebar ? '' : ' layout--full'; ?>">
			<div>
				<?php if ( have_posts() ) : ?>

					<?php if ( 'list' === $layout ) : ?>
						<div class="post-list">
							<?php
							while ( have_posts() ) :
								the_post();
								get_template_part( 'template-parts/content', 'list' );
							endwhile;
							?>
						</div>

					<?php elseif ( 'compact' === $layout ) : ?>
						<div class="post-compact">
							<?php
							$current_group = '';
							while ( have_posts() ) :
								the_post();
								$group = get_the_date( 'F Y' );
								if ( $group !== $current_group ) {
									echo $current_group ? '</ol>' : '';
									echo '<h2 class="post-compact__group">' . esc_html( $group ) . '</h2><ol class="post-compact__list">';
									$current_group = $group;
								}
								?>
								<li>
									<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date( 'd/m' ) ); ?></time>
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
									<?php if ( rootwp_panel_option( 'reading_time' ) ) : ?>
										<span class="post-compact__meta"><?php echo esc_html( rootwp_reading_time() ); ?></span>
									<?php endif; ?>
								</li>
								<?php
							endwhile;
							echo '</ol>';
							?>
						</div>

					<?php elseif ( 'magazine' === $layout && $first ) : ?>
						<?php
						// Revista: 1 destaque grande + 2 menores ao lado; o restante em grade.
						$index = 0;
						echo '<div class="magazine">';
						while ( have_posts() && $index < 3 ) :
							the_post();
							get_template_part( 'template-parts/content', 'card', array( 'featured' => 0 === $index, 'magazine' => true ) );
							$index++;
						endwhile;
						echo '</div>';
						if ( have_posts() ) :
							echo '<div class="post-grid">';
							while ( have_posts() ) :
								the_post();
								get_template_part( 'template-parts/content', 'card' );
							endwhile;
							echo '</div>';
						endif;
						?>

					<?php else : ?>
						<div class="post-grid">
							<?php
							$index = 0;
							while ( have_posts() ) :
								the_post();
								// Grade: o post mais recente da primeira página aparece em destaque.
								get_template_part( 'template-parts/content', 'card', array( 'featured' => 'grid' === $layout && 0 === $index && $first ) );
								$index++;
							endwhile;
							?>
						</div>
					<?php endif; ?>

					<?php rootwp_pagination(); ?>
				<?php else : ?>
					<?php get_template_part( 'template-parts/content', 'none' ); ?>
				<?php endif; ?>
			</div>

			<?php
			if ( $sidebar ) {
				get_sidebar();
			}
			?>
		</div>
	</div>
</main>

<?php
get_footer();
