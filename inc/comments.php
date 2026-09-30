<?php
/**
 * Comentários: cada comentário com a foto numa coluna estreita e, ao lado,
 * nome, selo "autor", data relativa, o texto num balão e as ações.
 *
 * @package rootwp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Data do comentário em formato relativo ("há 2 horas"); depois de 30 dias,
 * a data completa. O title mostra sempre data e hora exatas.
 *
 * @param WP_Comment $comment Comentário.
 * @return string
 */
function rootwp_comment_date( $comment ) {
	$time = (int) get_comment_time( 'U', true, true, $comment );
	/* translators: 1: data, 2: hora */
	$full = sprintf( __( '%1$s às %2$s', 'rootwp' ), get_comment_date( '', $comment ), get_comment_time( '', false, true, $comment ) );

	if ( time() - $time < 30 * DAY_IN_SECONDS ) {
		/* translators: %s: tempo decorrido, ex. "2 horas" */
		$label = sprintf( __( 'há %s', 'rootwp' ), human_time_diff( $time ) );
	} else {
		$label = get_comment_date( '', $comment );
	}

	return sprintf(
		'<a class="rc__date" href="%1$s"><time datetime="%2$s" title="%3$s">%4$s</time></a>',
		esc_url( get_comment_link( $comment ) ),
		esc_attr( get_comment_time( DATE_W3C, true, true, $comment ) ),
		esc_attr( $full ),
		esc_html( $label )
	);
}

/**
 * Monta um comentário (callback de wp_list_comments).
 * O WordPress fecha o <li> e cuida das respostas aninhadas.
 *
 * @param WP_Comment $comment Comentário.
 * @param array      $args    Argumentos.
 * @param int        $depth   Nível de aninhamento.
 */
function rootwp_comment( $comment, $args, $depth ) {
	// Pingbacks e trackbacks: uma linha simples.
	if ( in_array( $comment->comment_type, array( 'pingback', 'trackback' ), true ) ) {
		?>
		<li id="comment-<?php comment_ID(); ?>" <?php comment_class( 'rc-ping' ); ?>>
			<p><?php esc_html_e( 'Menção:', 'rootwp' ); ?> <?php comment_author_link( $comment ); ?></p>
		<?php
		return;
	}

	$is_author = $comment->user_id && (int) $comment->user_id === (int) get_post_field( 'post_author', $comment->comment_post_ID );
	?>
	<li id="comment-<?php comment_ID(); ?>" <?php comment_class( $args['has_children'] ? 'parent' : '', $comment ); ?>>
		<article id="div-comment-<?php comment_ID(); ?>" class="rc">
			<div class="rc__avatar"><?php echo get_avatar( $comment, 44 ); ?></div>

			<div class="rc__main">
				<header class="rc__header">
					<span class="rc__author"><?php comment_author_link( $comment ); ?></span>
					<?php if ( $is_author ) : ?>
						<span class="rc__badge"><?php esc_html_e( 'autor', 'rootwp' ); ?></span>
					<?php endif; ?>
					<span class="rc__sep" aria-hidden="true">·</span>
					<?php echo rootwp_comment_date( $comment ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</header>

				<?php if ( '0' === $comment->comment_approved ) : ?>
					<p class="rc__pending"><?php esc_html_e( 'Seu comentário está aguardando moderação.', 'rootwp' ); ?></p>
				<?php endif; ?>

				<div class="rc__bubble"><?php comment_text( $comment ); ?></div>

				<div class="rc__actions">
					<?php
					comment_reply_link(
						array_merge(
							$args,
							array(
								'depth'      => $depth,
								'max_depth'  => $args['max_depth'],
								'reply_text' => rootwp_icon( 'reply' ) . esc_html__( 'Responder', 'rootwp' ),
								'before'     => '',
								'after'      => '',
							)
						),
						$comment
					);
					edit_comment_link( rootwp_icon( 'edit' ) . esc_html__( 'Editar', 'rootwp' ), '', '' );
					?>
				</div>
			</div>
		</article>
	<?php
}

/**
 * Formulário de comentário: rótulos, campos lado a lado e botão no estilo do tema.
 *
 * @param array $defaults Argumentos padrão.
 * @return array
 */
function rootwp_comment_form_defaults( $defaults ) {
	$defaults['title_reply']          = __( 'Deixe seu comentário', 'rootwp' );
	/* translators: %s: nome de quem está sendo respondido */
	$defaults['title_reply_to']       = __( 'Responder a %s', 'rootwp' );
	$defaults['cancel_reply_link']    = __( 'Cancelar', 'rootwp' );
	$defaults['label_submit']         = __( 'Publicar comentário', 'rootwp' );
	$defaults['class_submit']         = 'submit btn';
	$defaults['class_form']           = 'comment-form rc-form';
	$defaults['comment_notes_before'] = '<p class="comment-notes">' . esc_html__( 'Seu e-mail não será publicado. Os campos marcados com * são obrigatórios.', 'rootwp' ) . '</p>';
	$defaults['comment_field']        = '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Comentário', 'rootwp' ) . ' <span class="required">*</span></label><textarea id="comment" name="comment" rows="5" maxlength="65525" required placeholder="' . esc_attr__( 'Escreva aqui o que achou, uma dúvida ou uma dica…', 'rootwp' ) . '"></textarea></p>';
	return $defaults;
}
add_filter( 'comment_form_defaults', 'rootwp_comment_form_defaults' );
