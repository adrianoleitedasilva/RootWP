<?php
/**
 * Página inicial (quando uma página estática é definida em Configurações > Leitura).
 * Configurada em Root WP > Página inicial. Se a página inicial exibir os posts
 * mais recentes, usamos a listagem do blog (index.php).
 *
 * @package rootwp
 */

if ( 'posts' === get_option( 'show_on_front' ) ) {
	get_template_part( 'index' );
	return;
}

get_header();

$blog_page  = (int) get_option( 'page_for_posts' );
$blog_url   = $blog_page ? get_permalink( $blog_page ) : home_url( '/' );
$btn1_url   = rootwp_panel_option( 'hero_button_url' ) ? rootwp_panel_option( 'hero_button_url' ) : $blog_url;
$btn2_url   = rootwp_panel_option( 'hero_button2_url' );
$hero_image = (int) rootwp_panel_option( 'hero_image' );
$new_tab    = ' target="_blank" rel="noopener"';
?>

<main id="primary" class="site-main site-main--home">
	<section class="hero">
		<div class="container hero__inner<?php echo $hero_image ? '' : ' hero__inner--text'; ?>">
			<div>
				<?php if ( rootwp_panel_option( 'hero_eyebrow' ) ) : ?>
					<span class="hero__eyebrow"><?php echo esc_html( rootwp_panel_option( 'hero_eyebrow' ) ); ?></span>
				<?php endif; ?>
				<h2 class="hero__title"><?php echo esc_html( rootwp_panel_option( 'hero_title' ) ); ?></h2>
				<?php if ( rootwp_panel_option( 'hero_text' ) ) : ?>
					<p class="hero__text"><?php echo esc_html( rootwp_panel_option( 'hero_text' ) ); ?></p>
				<?php endif; ?>
				<div class="hero__actions">
					<?php if ( rootwp_panel_option( 'hero_button_text' ) ) : ?>
						<a class="btn" href="<?php echo esc_url( $btn1_url ); ?>"<?php echo rootwp_panel_option( 'hero_button_new_tab' ) ? $new_tab : ''; // phpcs:ignore WordPress.Security.EscapeOutput ?>><?php echo esc_html( rootwp_panel_option( 'hero_button_text' ) ); ?> &rarr;</a>
					<?php endif; ?>
					<?php if ( $btn2_url && rootwp_panel_option( 'hero_button2_text' ) ) : ?>
						<a class="btn btn--ghost" href="<?php echo esc_url( $btn2_url ); ?>"<?php echo rootwp_panel_option( 'hero_button2_new_tab' ) ? $new_tab : ''; // phpcs:ignore WordPress.Security.EscapeOutput ?>><?php echo esc_html( rootwp_panel_option( 'hero_button2_text' ) ); ?></a>
					<?php endif; ?>
				</div>
			</div>
			<?php if ( $hero_image ) : ?>
				<div class="hero__image"><?php echo wp_get_attachment_image( $hero_image, 'large', false, array( 'loading' => 'eager' ) ); ?></div>
			<?php endif; ?>
		</div>
	</section>

	<?php
	// Seções na ordem definida no painel.
	foreach ( explode( ',', rootwp_panel_option( 'home_sections_order' ) ) as $section ) {
		switch ( $section ) {
			case 'content':
				rootwp_home_content();
				break;
			case 'recent':
				rootwp_home_recent( $blog_url );
				break;
			case 'topics':
				rootwp_home_topics();
				break;
		}
	}
	?>
</main>

<?php
get_footer();
