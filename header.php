<?php
/**
 * Cabeçalho do site.
 *
 * @package rootwp
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body id="top" <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Pular para o conteúdo', 'rootwp' ); ?></a>

<?php if ( is_singular( 'post' ) && rootwp_panel_option( 'progress_bar' ) ) : ?>
	<div class="reading-progress" aria-hidden="true"></div>
<?php endif; ?>

<?php rootwp_notice_bar(); ?>

<header class="site-header">
	<div class="container site-header__inner">
		<div class="site-branding">
			<?php rootwp_site_logo(); ?>
		</div>

		<nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e( 'Menu principal', 'rootwp' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'fallback_cb'    => 'wp_page_menu',
					'depth'          => 2,
				)
			);
			?>
		</nav>

		<div class="header-actions">
			<?php
			$cta_text = rootwp_panel_option( 'header_cta_text' );
			$cta_url  = rootwp_panel_option( 'header_cta_url' );
			if ( $cta_text && $cta_url ) :
				?>
				<a class="btn header-cta<?php echo 'outline' === rootwp_panel_option( 'header_cta_style' ) ? ' btn--ghost' : ''; ?>" href="<?php echo esc_url( $cta_url ); ?>"<?php echo rootwp_panel_option( 'header_cta_new_tab' ) ? ' target="_blank" rel="noopener"' : ''; ?>><?php echo esc_html( $cta_text ); ?></a>
			<?php endif; ?>
			<?php if ( rootwp_panel_option( 'header_show_search' ) ) : ?>
				<button type="button" class="icon-btn search-toggle" aria-controls="search-panel" aria-expanded="false" aria-label="<?php esc_attr_e( 'Buscar', 'rootwp' ); ?>">
					<?php echo rootwp_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</button>
			<?php endif; ?>
			<?php if ( rootwp_panel_option( 'dark_mode_toggle' ) ) : ?>
				<button type="button" class="icon-btn theme-toggle" aria-label="<?php esc_attr_e( 'Alternar modo escuro', 'rootwp' ); ?>">
					<span class="icon-moon"><?php echo rootwp_icon( 'moon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<span class="icon-sun"><?php echo rootwp_icon( 'sun' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				</button>
			<?php endif; ?>
			<button type="button" class="icon-btn menu-toggle" aria-controls="site-navigation" aria-expanded="false" aria-label="<?php esc_attr_e( 'Abrir menu', 'rootwp' ); ?>">
				<?php echo rootwp_icon( 'menu' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</button>
		</div>
	</div>

	<?php if ( rootwp_panel_option( 'header_show_search' ) ) : ?>
		<div id="search-panel" class="search-panel">
			<div class="container"><?php get_search_form(); ?></div>
		</div>
	<?php endif; ?>
</header>
