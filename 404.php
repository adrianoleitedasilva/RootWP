<?php
/**
 * Página não encontrada: busca, atalhos e sugestões de leitura.
 *
 * @package rootwp
 */

get_header();

$blog_page = (int) get_option( 'page_for_posts' );
$blog_url  = $blog_page ? get_permalink( $blog_page ) : home_url( '/' );
?>

<main id="primary" class="site-main site-main--404">
	<div class="container">
		<section class="error-404">
			<?php // Robozinho confuso: desenhado em SVG, usa as cores do tema (claro/escuro e paletas). ?>
			<svg class="robot-404" viewBox="0 0 220 200" role="img" aria-label="<?php esc_attr_e( 'Robô confuso coçando a cabeça', 'rootwp' ); ?>">
				<g class="robot-404__marks">
					<text class="robot-404__q robot-404__q--1" x="18" y="62">?</text>
					<text class="robot-404__q robot-404__q--2" x="176" y="44">?</text>
					<text class="robot-404__q robot-404__q--3" x="190" y="104">?</text>
				</g>
				<ellipse class="robot-404__shadow" cx="110" cy="190" rx="52" ry="6"/>
				<g class="robot-404__body">
					<!-- braço esquerdo, parado -->
					<path class="robot-404__line" d="M72 138 Q56 150 58 168"/>
					<circle class="robot-404__joint" cx="58" cy="170" r="6"/>
					<!-- corpo -->
					<rect class="robot-404__shell" x="72" y="120" width="76" height="58" rx="14"/>
					<!-- pernas -->
					<path class="robot-404__line" d="M92 178 v8 M128 178 v8"/>
					<g class="robot-404__head">
						<!-- cabeça -->
						<rect class="robot-404__shell" x="62" y="44" width="96" height="70" rx="20"/>
						<rect class="robot-404__face" x="74" y="56" width="72" height="46" rx="14"/>
						<!-- olhos desencontrados e sobrancelhas tortas -->
						<circle class="robot-404__eye" cx="96" cy="78" r="7"/>
						<circle class="robot-404__eye" cx="126" cy="80" r="4.5"/>
						<path class="robot-404__brow" d="M88 66 l14 -4 M119 70 l13 3"/>
						<!-- boca em zigue-zague -->
						<path class="robot-404__mouth" d="M98 93 l5 -3 l5 3 l5 -3 l5 3"/>
						<!-- orelhas -->
						<rect class="robot-404__ear" x="54" y="68" width="8" height="22" rx="4"/>
						<rect class="robot-404__ear" x="158" y="68" width="8" height="22" rx="4"/>
					</g>
					<!-- braço direito, coçando a cabeça -->
					<g class="robot-404__arm">
						<path class="robot-404__line" d="M148 132 Q176 118 168 84"/>
						<circle class="robot-404__joint" cx="166" cy="80" r="6"/>
					</g>
				</g>
			</svg>

			<p class="error-404__code" aria-hidden="true"><span>4</span><span>0</span><span>4</span></p>
			<h1 class="error-404__title"><?php esc_html_e( 'Página não encontrada', 'rootwp' ); ?></h1>
			<p class="error-404__text"><?php esc_html_e( 'O endereço pode ter mudado, o conteúdo pode ter sido removido ou houve um erro de digitação. Tente uma busca ou siga por um dos caminhos abaixo.', 'rootwp' ); ?></p>

			<form role="search" method="get" class="search-form search-form--large" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<label class="screen-reader-text" for="search-404-field"><?php esc_html_e( 'Buscar por:', 'rootwp' ); ?></label>
				<?php echo rootwp_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<input type="search" id="search-404-field" class="search-field" name="s" placeholder="<?php esc_attr_e( 'Buscar artigos…', 'rootwp' ); ?>">
				<button type="submit" class="btn search-submit"><?php esc_html_e( 'Buscar', 'rootwp' ); ?></button>
			</form>

			<div class="error-404__actions">
				<a class="btn" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Ir para o início', 'rootwp' ); ?></a>
				<?php if ( $blog_url !== home_url( '/' ) ) : ?>
					<a class="btn btn--ghost" href="<?php echo esc_url( $blog_url ); ?>"><?php esc_html_e( 'Ver todos os artigos', 'rootwp' ); ?></a>
				<?php endif; ?>
			</div>
		</section>

		<?php rootwp_suggestions(); ?>
	</div>
</main>

<?php
get_footer();
