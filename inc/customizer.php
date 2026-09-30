<?php
/**
 * Valores antigos da página inicial, salvos quando ela era configurada em
 * Aparência > Personalizar. Hoje tudo fica em Root WP > Página inicial; estas
 * funções só servem de ponto de partida (padrão) para quem já tinha configurado.
 *
 * @package rootwp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Valores padrão das opções.
 *
 * @return array
 */
function rootwp_defaults() {
	return array(
		'hero_eyebrow'       => __( 'Tecnologia & Educação', 'rootwp' ),
		'hero_title'         => __( 'Aprender, ensinar e compartilhar conhecimento.', 'rootwp' ),
		'hero_text'          => __( 'Artigos, tutoriais e reflexões sobre tecnologia aplicada à educação.', 'rootwp' ),
		'hero_button_text'   => __( 'Ler os artigos', 'rootwp' ),
		'hero_button_url'    => '',
		'hero_button2_text'  => __( 'Sobre mim', 'rootwp' ),
		'hero_button2_url'   => '',
		'hero_image'         => '',
		'home_posts_count'   => 6,
		'show_topics'        => true,
		'footer_text'        => '',
	);
}

/**
 * Lê uma opção do tema com valor padrão.
 *
 * @param string $key Nome da opção.
 * @return mixed
 */
function rootwp_option( $key ) {
	$defaults = rootwp_defaults();
	return get_theme_mod( $key, isset( $defaults[ $key ] ) ? $defaults[ $key ] : '' );
}
