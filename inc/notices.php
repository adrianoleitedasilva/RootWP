<?php
/**
 * Avisos (Root WP > Avisos):
 * - barra de aviso no topo do site, com link e botão de fechar;
 * - chamada (CTA) no fim de todo artigo: newsletter, curso, redes etc.
 *
 * @package rootwp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Identificador do aviso atual: muda quando o texto ou o link mudam,
 * então quem fechou um aviso antigo volta a ver o novo.
 *
 * @return string
 */
function rootwp_notice_id() {
	return substr( md5( rootwp_panel_option( 'notice_text' ) . '|' . rootwp_panel_option( 'notice_link_url' ) ), 0, 10 );
}

/**
 * Barra de aviso no topo do site.
 */
function rootwp_notice_bar() {
	$text = rootwp_panel_option( 'notice_text' );
	if ( ! rootwp_panel_option( 'notice_enabled' ) || ! $text ) {
		return;
	}

	$id       = rootwp_notice_id();
	$url      = rootwp_panel_option( 'notice_link_url' );
	$link     = rootwp_panel_option( 'notice_link_text' );
	$closable = rootwp_panel_option( 'notice_dismissible' );
	$new_tab  = rootwp_panel_option( 'notice_new_tab' ) ? ' target="_blank" rel="noopener"' : '';
	?>
	<div class="notice-bar notice-bar--<?php echo esc_attr( rootwp_panel_option( 'notice_style' ) ); ?>" id="notice-bar" data-notice="<?php echo esc_attr( $id ); ?>" role="region" aria-label="<?php esc_attr_e( 'Aviso', 'rootwp' ); ?>">
		<?php if ( $closable ) : ?>
			<?php // Fechado antes? Remove já, antes de aparecer na tela. ?>
			<script>try{if(localStorage.getItem('rootwp-notice')===<?php echo wp_json_encode( $id ); ?>){document.getElementById('notice-bar').remove();}}catch(e){}</script>
		<?php endif; ?>
		<div class="container notice-bar__inner">
			<p class="notice-bar__text">
				<?php echo esc_html( $text ); ?>
				<?php if ( $url && $link ) : ?>
					<a class="notice-bar__link" href="<?php echo esc_url( $url ); ?>"<?php echo $new_tab; // phpcs:ignore WordPress.Security.EscapeOutput ?>><?php echo esc_html( $link ); ?> <span aria-hidden="true">&rarr;</span></a>
				<?php endif; ?>
			</p>
			<?php if ( $closable ) : ?>
				<button type="button" class="notice-bar__close" aria-label="<?php esc_attr_e( 'Fechar aviso', 'rootwp' ); ?>"><?php echo rootwp_icon( 'x' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></button>
			<?php endif; ?>
		</div>
	</div>
	<?php
}

/**
 * Chamada no fim do artigo.
 */
function rootwp_post_cta() {
	$title = rootwp_panel_option( 'cta_title' );
	$text  = rootwp_panel_option( 'cta_text' );
	if ( ! rootwp_panel_option( 'cta_enabled' ) || ( ! $title && ! $text ) ) {
		return;
	}

	$excluded = array_filter( array_map( 'absint', explode( ',', rootwp_panel_option( 'cta_exclude' ) ) ) );
	if ( $excluded && has_category( $excluded ) ) {
		return;
	}

	$button  = rootwp_panel_option( 'cta_button_text' );
	$url     = rootwp_panel_option( 'cta_button_url' );
	$image   = (int) rootwp_panel_option( 'cta_image' );
	$new_tab = rootwp_panel_option( 'cta_new_tab' ) ? ' target="_blank" rel="noopener"' : '';
	?>
	<aside class="post-cta<?php echo $image ? ' post-cta--image' : ''; ?>" aria-label="<?php echo esc_attr( $title ? $title : __( 'Chamada', 'rootwp' ) ); ?>">
		<?php if ( $image ) : ?>
			<div class="post-cta__image"><?php echo wp_get_attachment_image( $image, 'medium', false, array( 'alt' => '', 'loading' => 'lazy' ) ); ?></div>
		<?php endif; ?>
		<div class="post-cta__body">
			<?php if ( $title ) : ?>
				<p class="post-cta__title"><?php echo esc_html( $title ); ?></p>
			<?php endif; ?>
			<?php if ( $text ) : ?>
				<p class="post-cta__text"><?php echo nl2br( esc_html( $text ) ); ?></p>
			<?php endif; ?>
			<?php if ( $button && $url ) : ?>
				<a class="btn" href="<?php echo esc_url( $url ); ?>"<?php echo $new_tab; // phpcs:ignore WordPress.Security.EscapeOutput ?>><?php echo esc_html( $button ); ?> <span aria-hidden="true">&rarr;</span></a>
			<?php endif; ?>
		</div>
	</aside>
	<?php
}
