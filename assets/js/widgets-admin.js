/* Widgets do tema: botão "Escolher" abre a biblioteca de mídia e preenche a URL. */
( function ( $ ) {
	// Delegado no documento: os formulários de widget são criados dinamicamente.
	$( document ).on( 'click', '.as-widget-media__button', function ( e ) {
		var $button = $( this );
		var $input = $( '#' + $button.data( 'target' ) );
		var frame = wp.media( {
			title: $button.closest( 'p' ).find( 'label' ).text(),
			library: { type: $button.data( 'type' ) },
			button: { text: 'Usar este arquivo' },
			multiple: false
		} );

		e.preventDefault();

		frame.on( 'select', function () {
			var file = frame.state().get( 'selection' ).first().toJSON();
			// "change" e "input" avisam o editor de widgets que há algo para salvar.
			$input.val( file.url ).trigger( 'input' ).trigger( 'change' );
		} );

		frame.open();
	} );
} )( jQuery );
