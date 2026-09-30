<?php
/**
 * Card de post usado nas listagens.
 * Aceita $args['featured'] = true para o card grande de destaque.
 *
 * @package rootwp
 */

$is_featured = ! empty( $args['featured'] );
$is_magazine = ! empty( $args['magazine'] );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'card' . ( $is_featured ? ' card--featured' : '' ) . ( $is_magazine ? ' card--magazine' : '' ) ); ?>>
	<a class="card__thumb<?php echo has_post_thumbnail() ? '' : ' card__thumb--placeholder'; ?>" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
		<?php
		if ( has_post_thumbnail() ) {
			the_post_thumbnail( $is_featured ? 'rootwp-wide' : 'rootwp-card', array( 'loading' => $is_featured ? 'eager' : 'lazy' ) );
		} else {
			echo esc_html( mb_strtoupper( mb_substr( get_the_title(), 0, 1 ) ) );
		}
		?>
	</a>

	<div class="card__body">
		<div class="card__badges"><?php rootwp_category_badge(); ?><?php echo rootwp_series_badge(); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>

		<h2 class="card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

		<div class="card__excerpt"><?php the_excerpt(); ?></div>

		<?php rootwp_entry_meta( $is_featured ); ?>
	</div>
</article>
