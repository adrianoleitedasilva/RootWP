<?php
/**
 * Barra lateral do blog.
 *
 * @package rootwp
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>
<aside class="widget-area" aria-label="<?php esc_attr_e( 'Barra lateral', 'rootwp' ); ?>">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside>
