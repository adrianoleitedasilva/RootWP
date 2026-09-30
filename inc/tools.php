<?php
/**
 * Root WP > Ferramentas: exportar e importar as configurações (JSON) e
 * restaurar os padrões.
 *
 * O arquivo leva as opções do painel e as configurações de cada categoria
 * (layout, template, cor e capa), identificadas pelo slug.
 *
 * @package rootwp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Volta para a aba Ferramentas com uma mensagem.
 *
 * @param string $message Código da mensagem.
 */
function rootwp_tools_redirect( $message ) {
	wp_safe_redirect( add_query_arg( 'rootwp_msg', $message, admin_url( 'admin.php?page=rootwp-tools' ) ) );
	exit;
}

/**
 * Mensagens após exportar, importar ou restaurar.
 */
function rootwp_tools_message() {
	$code     = isset( $_GET['rootwp_msg'] ) ? sanitize_key( wp_unslash( $_GET['rootwp_msg'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	$messages = array(
		'imported' => array( 'success', __( 'Configurações importadas com sucesso.', 'rootwp' ) ),
		'reset'    => array( 'success', __( 'Todas as opções do tema voltaram ao padrão.', 'rootwp' ) ),
		'invalid'  => array( 'error', __( 'Arquivo inválido: envie um .json exportado pelo Root WP.', 'rootwp' ) ),
		'nofile'   => array( 'error', __( 'Nenhum arquivo foi enviado.', 'rootwp' ) ),
		'confirm'  => array( 'error', __( 'Marque a confirmação antes de restaurar os padrões.', 'rootwp' ) ),
	);
	if ( isset( $messages[ $code ] ) ) {
		printf( '<div class="notice notice-%1$s is-dismissible"><p>%2$s</p></div>', esc_attr( $messages[ $code ][0] ), esc_html( $messages[ $code ][1] ) );
	}
}

/**
 * Dados exportados.
 *
 * @return array
 */
function rootwp_export_data() {
	$categories = array();
	foreach ( get_categories( array( 'hide_empty' => false ) ) as $category ) {
		$meta = array();
		foreach ( array( 'layout', 'post_template', 'color', 'cover' ) as $key ) {
			$value = rootwp_category_meta( $category->term_id, $key );
			if ( $value ) {
				$meta[ $key ] = $value;
			}
		}
		if ( $meta ) {
			$categories[ $category->slug ] = $meta;
		}
	}

	$options = get_option( 'rootwp_options', array() );
	return array(
		'theme'      => 'Root WP',
		'version'    => ROOTWP_VERSION,
		'exported'   => gmdate( 'c' ),
		'site'       => home_url( '/' ),
		'options'    => is_array( $options ) ? $options : array(),
		'categories' => $categories,
	);
}

/**
 * Exportar: baixa um .json com as configurações.
 */
function rootwp_handle_export() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( 'Sem permissão.', 'rootwp' ) );
	}
	check_admin_referer( 'rootwp_export' );

	$filename = 'rootwp-' . sanitize_title( wp_parse_url( home_url(), PHP_URL_HOST ) ) . '-' . gmdate( 'Y-m-d' ) . '.json';
	nocache_headers();
	header( 'Content-Type: application/json; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
	echo wp_json_encode( rootwp_export_data(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
	exit;
}
add_action( 'admin_post_rootwp_export', 'rootwp_handle_export' );

/**
 * Importar: lê o .json, valida tudo e aplica.
 */
function rootwp_handle_import() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( 'Sem permissão.', 'rootwp' ) );
	}
	check_admin_referer( 'rootwp_import' );

	if ( empty( $_FILES['rootwp_file']['tmp_name'] ) || ! is_uploaded_file( $_FILES['rootwp_file']['tmp_name'] ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		rootwp_tools_redirect( 'nofile' );
	}

	$data = json_decode( (string) file_get_contents( $_FILES['rootwp_file']['tmp_name'] ), true ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput, WordPress.WP.AlternativeFunctions
	if ( ! is_array( $data ) || ! isset( $data['theme'], $data['options'] ) || 'Root WP' !== $data['theme'] || ! is_array( $data['options'] ) ) {
		rootwp_tools_redirect( 'invalid' );
	}

	// Mesma validação do painel: só entram chaves conhecidas, com valores limpos.
	$options = array_intersect_key( $data['options'], rootwp_panel_defaults() );
	update_option( 'rootwp_options', rootwp_sanitize_options( array_merge( rootwp_panel_defaults(), $options ) ) );

	if ( ! empty( $data['categories'] ) && is_array( $data['categories'] ) ) {
		foreach ( $data['categories'] as $slug => $meta ) {
			$term = get_term_by( 'slug', sanitize_title( $slug ), 'category' );
			if ( ! $term || ! is_array( $meta ) ) {
				continue;
			}
			$clean = array(
				'layout'        => isset( $meta['layout'], rootwp_category_layouts()[ $meta['layout'] ] ) ? $meta['layout'] : '',
				'post_template' => isset( $meta['post_template'], rootwp_post_templates()[ $meta['post_template'] ] ) ? $meta['post_template'] : '',
				'color'         => isset( $meta['color'] ) ? (string) sanitize_hex_color( $meta['color'] ) : '',
				'cover'         => isset( $meta['cover'] ) && wp_attachment_is_image( (int) $meta['cover'] ) ? (int) $meta['cover'] : 0,
			);
			foreach ( $clean as $key => $value ) {
				if ( $value && 'default' !== $value ) {
					update_term_meta( $term->term_id, 'rootwp_' . $key, $value );
				}
			}
		}
	}

	rootwp_tools_redirect( 'imported' );
}
add_action( 'admin_post_rootwp_import', 'rootwp_handle_import' );

/**
 * Restaurar os padrões do painel (não mexe nas categorias, widgets nem menus).
 */
function rootwp_handle_reset() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( 'Sem permissão.', 'rootwp' ) );
	}
	check_admin_referer( 'rootwp_reset' );

	if ( empty( $_POST['rootwp_confirm'] ) ) {
		rootwp_tools_redirect( 'confirm' );
	}

	delete_option( 'rootwp_options' );
	rootwp_tools_redirect( 'reset' );
}
add_action( 'admin_post_rootwp_reset', 'rootwp_handle_reset' );

/**
 * Aba "Ferramentas".
 */
function rootwp_render_tools_tab() {
	$action = admin_url( 'admin-post.php' );
	?>
	<div class="rootwp-card">
		<h2><?php esc_html_e( 'Exportar configurações', 'rootwp' ); ?></h2>
		<p class="description"><?php esc_html_e( 'Baixa um arquivo .json com todas as opções do painel Root WP e as configurações das categorias (layout, template, cor e capa). Use como backup ou para levar o visual para outro site.', 'rootwp' ); ?></p>
		<form method="post" action="<?php echo esc_url( $action ); ?>">
			<input type="hidden" name="action" value="rootwp_export">
			<?php wp_nonce_field( 'rootwp_export' ); ?>
			<?php submit_button( __( 'Baixar arquivo de configurações', 'rootwp' ), 'secondary', 'submit', false ); ?>
		</form>
	</div>

	<div class="rootwp-card">
		<h2><?php esc_html_e( 'Importar configurações', 'rootwp' ); ?></h2>
		<p class="description"><?php esc_html_e( 'Envie um arquivo exportado pelo Root WP. As opções atuais do painel são substituídas; as categorias são ajustadas pelo nome (slug) quando existirem neste site.', 'rootwp' ); ?></p>
		<p class="description"><?php esc_html_e( 'Imagens (logo, favicon, capas) são guardadas pelo ID da biblioteca de mídia: em outro site, elas precisam ser escolhidas de novo.', 'rootwp' ); ?></p>
		<form method="post" action="<?php echo esc_url( $action ); ?>" enctype="multipart/form-data">
			<input type="hidden" name="action" value="rootwp_import">
			<?php wp_nonce_field( 'rootwp_import' ); ?>
			<p><input type="file" name="rootwp_file" accept=".json,application/json" required></p>
			<?php submit_button( __( 'Importar', 'rootwp' ), 'primary', 'submit', false ); ?>
		</form>
	</div>

	<div class="rootwp-card rootwp-card--danger">
		<h2><?php esc_html_e( 'Restaurar padrões', 'rootwp' ); ?></h2>
		<p class="description"><?php esc_html_e( 'Volta todas as abas do painel Root WP (logo, cores, cabeçalho, artigos, avisos, SEO, rodapé…) para o padrão do tema. Categorias, widgets, menus, posts e páginas não são alterados.', 'rootwp' ); ?></p>
		<p class="description"><strong><?php esc_html_e( 'Dica: exporte as configurações antes, para poder voltar atrás.', 'rootwp' ); ?></strong></p>
		<form method="post" action="<?php echo esc_url( $action ); ?>" onsubmit="return confirm('<?php echo esc_js( __( 'Restaurar todas as opções do tema para o padrão?', 'rootwp' ) ); ?>');">
			<input type="hidden" name="action" value="rootwp_reset">
			<?php wp_nonce_field( 'rootwp_reset' ); ?>
			<p><label><input type="checkbox" name="rootwp_confirm" value="1" required> <?php esc_html_e( 'Entendo que as opções atuais do painel serão apagadas.', 'rootwp' ); ?></label></p>
			<?php submit_button( __( 'Restaurar padrões', 'rootwp' ), 'delete', 'submit', false ); ?>
		</form>
	</div>
	<?php
}
