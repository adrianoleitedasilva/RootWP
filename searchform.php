<?php
/**
 * Formulário de busca.
 *
 * @package rootwp
 */

$rootwp_search_id = wp_unique_id( 'search-' );
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $rootwp_search_id ); ?>"><?php esc_html_e( 'Buscar por:', 'rootwp' ); ?></label>
	<input type="search" id="<?php echo esc_attr( $rootwp_search_id ); ?>" class="search-field" placeholder="<?php esc_attr_e( 'Buscar artigos…', 'rootwp' ); ?>" value="<?php echo get_search_query(); ?>" name="s">
	<button type="submit" class="btn search-submit"><?php esc_html_e( 'Buscar', 'rootwp' ); ?></button>
</form>
