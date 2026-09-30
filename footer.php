<?php
/**
 * Rodapé do site. Configurado em Tema > Rodapé.
 *
 * @package rootwp
 */

$about_title  = rootwp_panel_option( 'footer_about_title' );
$about_title  = $about_title ? $about_title : get_bloginfo( 'name' );
$about_text   = rootwp_panel_option( 'footer_about' );
$show_social  = rootwp_panel_option( 'footer_show_social' );
$show_widgets = rootwp_panel_option( 'footer_show_widgets' )
	&& ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) );
$has_about    = (bool) $about_text;
$footer_text  = rootwp_panel_option( 'footer_text' );
?>
<footer class="site-footer">
	<div class="container">
		<?php if ( $has_about || $show_widgets ) : ?>
			<div class="footer-widgets">
				<?php if ( $has_about ) : ?>
					<div class="footer-widgets__col footer-about">
						<p class="footer-about__title"><?php echo esc_html( $about_title ); ?></p>
						<p class="footer-about__text"><?php echo nl2br( esc_html( $about_text ) ); ?></p>
					</div>
				<?php endif; ?>

				<?php if ( $show_widgets ) : ?>
					<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
						<?php if ( is_active_sidebar( 'footer-' . $i ) ) : ?>
							<div class="footer-widgets__col"><?php dynamic_sidebar( 'footer-' . $i ); ?></div>
						<?php endif; ?>
					<?php endfor; ?>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="site-info">
			<div class="site-info__copy">
				<?php
				if ( $footer_text ) {
					echo wp_kses_post( str_replace( '{ano}', gmdate( 'Y' ), $footer_text ) );
				} else {
					echo '&copy; ' . esc_html( gmdate( 'Y' ) ) . ' ' . esc_html( get_bloginfo( 'name' ) );
				}
				?>
			</div>

			<div class="site-info__social">
				<?php
				if ( $show_social ) {
					rootwp_social_links();
				}
				?>
			</div>

			<div class="site-info__end">
			<?php if ( rootwp_panel_option( 'footer_show_menu' ) && has_nav_menu( 'footer' ) ) : ?>
				<nav class="footer-navigation" aria-label="<?php esc_attr_e( 'Menu do rodapé', 'rootwp' ); ?>">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer',
							'container'      => false,
							'depth'          => 1,
						)
					);
					?>
				</nav>
			<?php endif; ?>
			<?php if ( rootwp_panel_option( 'footer_back_to_top' ) ) : ?>
				<a class="back-to-top" href="#top">
					<?php esc_html_e( 'Voltar ao topo', 'rootwp' ); ?>
					<?php echo rootwp_icon( 'arrow-up' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</a>
			<?php endif; ?>
			</div>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
