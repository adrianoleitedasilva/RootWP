<?php
/**
 * Cores do tema (Root WP > Cores): paletas prontas, cálculo das cores
 * derivadas e geração das variáveis de CSS para os modos claro e escuro.
 *
 * O visitante nunca recebe cores "soltas": bordas, texto secundário, tons
 * claros da cor principal e a cor do texto sobre botões são calculados a
 * partir das cores escolhidas, para manter harmonia e contraste.
 *
 * @package rootwp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Cores editáveis e seus valores padrão (paleta Azul).
 *
 * @return array
 */
function rootwp_color_defaults() {
	return array(
		'primary_color' => '#2f4bd8',
		'accent_color'  => '#f5a524',
		'light_bg'      => '#ffffff',
		'light_surface' => '#f6f7fb',
		'light_text'    => '#1c2033',
		'dark_bg'       => '#0e1120',
		'dark_surface'  => '#161a2e',
		'dark_text'     => '#e6e8f2',
	);
}

/**
 * Paletas prontas, testadas nos modos claro e escuro.
 *
 * @return array
 */
function rootwp_color_palettes() {
	return array(
		'blue'     => array(
			'label'  => __( 'Azul (padrão)', 'rootwp' ),
			'colors' => rootwp_color_defaults(),
		),
		'green'    => array(
			'label'  => __( 'Verde', 'rootwp' ),
			'colors' => array(
				'primary_color' => '#0f8a5f',
				'accent_color'  => '#f2b134',
				'light_bg'      => '#ffffff',
				'light_surface' => '#f3f8f5',
				'light_text'    => '#15241d',
				'dark_bg'       => '#0b1511',
				'dark_surface'  => '#13211b',
				'dark_text'     => '#e2efe8',
			),
		),
		'purple'   => array(
			'label'  => __( 'Roxo', 'rootwp' ),
			'colors' => array(
				'primary_color' => '#6d3fd9',
				'accent_color'  => '#ff8a3d',
				'light_bg'      => '#ffffff',
				'light_surface' => '#f7f5fc',
				'light_text'    => '#1e1733',
				'dark_bg'       => '#120e1f',
				'dark_surface'  => '#1b152e',
				'dark_text'     => '#ebe6f7',
			),
		),
		'orange'   => array(
			'label'  => __( 'Laranja', 'rootwp' ),
			'colors' => array(
				'primary_color' => '#d9531e',
				'accent_color'  => '#1f9d8f',
				'light_bg'      => '#fffdfb',
				'light_surface' => '#fbf5f0',
				'light_text'    => '#2a1d16',
				'dark_bg'       => '#17110d',
				'dark_surface'  => '#221a14',
				'dark_text'     => '#f3e9e2',
			),
		),
		'graphite' => array(
			'label'  => __( 'Grafite', 'rootwp' ),
			'colors' => array(
				'primary_color' => '#334155',
				'accent_color'  => '#0ea5e9',
				'light_bg'      => '#ffffff',
				'light_surface' => '#f5f6f8',
				'light_text'    => '#111827',
				'dark_bg'       => '#0b0d12',
				'dark_surface'  => '#151922',
				'dark_text'     => '#e5e7eb',
			),
		),
	);
}

/**
 * Cor salva no painel, validada, com o padrão como reserva.
 *
 * @param string $key Opção.
 * @return string
 */
function rootwp_color( $key ) {
	$defaults = rootwp_color_defaults();
	$color    = sanitize_hex_color( rootwp_panel_option( $key ) );
	return strtolower( $color ? $color : $defaults[ $key ] );
}

/**
 * Converte #rgb/#rrggbb em array( r, g, b ).
 *
 * @param string $hex Cor.
 * @return int[]
 */
function rootwp_hex_to_rgb( $hex ) {
	$hex = ltrim( $hex, '#' );
	if ( 3 === strlen( $hex ) ) {
		$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
	}
	return array_map( 'hexdec', str_split( $hex, 2 ) );
}

/**
 * Mistura duas cores (equivalente a color-mix in srgb).
 *
 * @param string $a      Primeira cor.
 * @param string $b      Segunda cor.
 * @param float  $weight Proporção da primeira cor (0 a 1).
 * @return string
 */
function rootwp_mix( $a, $b, $weight ) {
	$a = rootwp_hex_to_rgb( $a );
	$b = rootwp_hex_to_rgb( $b );
	$c = array();
	for ( $i = 0; $i < 3; $i++ ) {
		$c[] = (int) round( $a[ $i ] * $weight + $b[ $i ] * ( 1 - $weight ) );
	}
	return vsprintf( '#%02x%02x%02x', $c );
}

/**
 * Cores finais (escolhidas + derivadas) de um modo.
 *
 * @param string $mode light|dark.
 * @return array
 */
function rootwp_color_scheme( $mode ) {
	$primary = rootwp_color( 'primary_color' );

	if ( 'dark' === $mode ) {
		$bg    = rootwp_color( 'dark_bg' );
		$text  = rootwp_color( 'dark_text' );
		$main  = rootwp_mix( $primary, '#ffffff', .62 );
		$hover = rootwp_mix( $primary, '#ffffff', .42 );
		return array(
			'bg'              => $bg,
			'surface'         => rootwp_color( 'dark_surface' ),
			'text'            => $text,
			'border'          => rootwp_mix( $text, $bg, .14 ),
			'muted'           => rootwp_mix( $text, $bg, .62 ),
			'primary'         => $main,
			'primary-dark'    => $hover,
			'primary-soft'    => rootwp_mix( $main, $bg, .2 ),
			'primary-fg'      => rootwp_contrast_text( $main ),
			'primary-dark-fg' => rootwp_contrast_text( $hover ),
			'accent'          => rootwp_color( 'accent_color' ),
		);
	}

	$bg    = rootwp_color( 'light_bg' );
	$text  = rootwp_color( 'light_text' );
	$hover = rootwp_mix( $primary, '#000000', .72 );
	return array(
		'bg'              => $bg,
		'surface'         => rootwp_color( 'light_surface' ),
		'text'            => $text,
		'border'          => rootwp_mix( $text, $bg, .12 ),
		'muted'           => rootwp_mix( $text, $bg, .66 ),
		'primary'         => $primary,
		'primary-dark'    => $hover,
		'primary-soft'    => rootwp_mix( $primary, $bg, .11 ),
		'primary-fg'      => rootwp_contrast_text( $primary ),
		'primary-dark-fg' => rootwp_contrast_text( $hover ),
		'accent'          => rootwp_color( 'accent_color' ),
	);
}

/**
 * CSS com as variáveis de cor. Com a paleta padrão intacta, só vão as cores
 * de texto sobre botões; o restante continua vindo do style.css, exatamente
 * como o tema foi desenhado.
 *
 * @return string
 */
function rootwp_color_css() {
	$defaults = rootwp_color_defaults();
	$changed  = array();
	foreach ( $defaults as $key => $value ) {
		$changed[ $key ] = rootwp_color( $key ) !== $value;
	}

	// Quais variáveis dependem de quais cores escolhidas.
	$depends = array(
		'bg'              => array( 'bg' ),
		'surface'         => array( 'surface' ),
		'text'            => array( 'text' ),
		'border'          => array( 'bg', 'text' ),
		'muted'           => array( 'bg', 'text' ),
		'primary'         => array( 'primary' ),
		'primary-dark'    => array( 'primary' ),
		'primary-soft'    => array( 'primary', 'bg' ),
		'primary-fg'      => array( '*' ),
		'primary-dark-fg' => array( '*' ),
		'accent'          => array( 'accent' ),
	);

	$css = '';
	foreach ( array( 'light' => ':root', 'dark' => 'html[data-theme="dark"]' ) as $mode => $selector ) {
		$keys = array(
			'bg'      => $mode . '_bg',
			'surface' => $mode . '_surface',
			'text'    => $mode . '_text',
			'primary' => 'primary_color',
			'accent'  => 'accent_color',
		);
		$vars = '';
		foreach ( rootwp_color_scheme( $mode ) as $name => $value ) {
			$emit = false;
			foreach ( $depends[ $name ] as $source ) {
				if ( '*' === $source || $changed[ $keys[ $source ] ] ) {
					$emit = true;
				}
			}
			// O destaque (accent) não muda no modo escuro.
			if ( $emit && ! ( 'dark' === $mode && 'accent' === $name ) ) {
				$vars .= '--c-' . $name . ':' . $value . ';';
			}
		}
		$css .= $selector . '{' . $vars . '}';
	}

	return $css;
}
