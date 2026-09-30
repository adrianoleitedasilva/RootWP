/* Painel do tema: seletor de mídia do logo e troca texto/imagem. */
( function ( $ ) {
	var frame;

	function togglePanels() {
		var type = $( 'input[name="rootwp_options[logo_type]"]:checked' ).val();
		$( '[data-logo-panel]' ).each( function () {
			$( this ).toggle( $( this ).data( 'logo-panel' ) === type );
		} );
	}

	function toggleTopics() {
		var mode = $( 'input[name="rootwp_options[home_topics_mode]"]:checked' ).val();
		$( '[data-topics-panel]' ).each( function () {
			$( this ).toggle( $( this ).data( 'topics-panel' ) === mode );
		} );
	}

	// Listas arrastáveis: grava a ordem no campo oculto indicado em data-target.
	// Nas listas com checkbox, só entram os itens marcados.
	function saveSortable( $list ) {
		var checks = $list.hasClass( 'rootwp-sortable--checks' );
		var values = $list.children( 'li' ).filter( function () {
			return ! checks || $( this ).find( 'input[type="checkbox"]' ).prop( 'checked' );
		} ).map( function () {
			return $( this ).data( 'value' );
		} ).get();
		$( '#' + $list.data( 'target' ) ).val( values.join( ',' ) );
	}

	/* ---------------------------------------------------------------------
	   Aba Cores: mesmas contas de inc/colors.php, para a prévia bater com o site.
	   --------------------------------------------------------------------- */
	function hexToRgb( hex ) {
		hex = ( hex || '' ).replace( '#', '' );
		if ( hex.length === 3 ) hex = hex.replace( /(.)/g, '$1$1' );
		return [ 0, 2, 4 ].map( function ( i ) { return parseInt( hex.substr( i, 2 ), 16 ) || 0; } );
	}
	function mix( a, b, weight ) {
		var x = hexToRgb( a ), y = hexToRgb( b );
		return '#' + x.map( function ( v, i ) {
			return ( '0' + Math.round( v * weight + y[ i ] * ( 1 - weight ) ).toString( 16 ) ).slice( -2 );
		} ).join( '' );
	}
	function luminance( hex ) {
		var c = hexToRgb( hex ).map( function ( v ) {
			v /= 255;
			return v <= 0.03928 ? v / 12.92 : Math.pow( ( v + 0.055 ) / 1.055, 2.4 );
		} );
		return 0.2126 * c[ 0 ] + 0.7152 * c[ 1 ] + 0.0722 * c[ 2 ];
	}
	function ratio( a, b ) {
		var la = luminance( a ), lb = luminance( b );
		return ( Math.max( la, lb ) + 0.05 ) / ( Math.min( la, lb ) + 0.05 );
	}
	function contrastText( hex ) {
		var l = luminance( hex );
		return 1.05 / ( l + 0.05 ) >= ( l + 0.05 ) / 0.0636 ? '#ffffff' : '#1c2033';
	}

	function colorValues() {
		var values = {};
		$( '.rootwp-color-live' ).each( function () {
			values[ $( this ).data( 'key' ) ] = $( this ).val() || $( this ).data( 'default-color' );
		} );
		return values;
	}

	function scheme( v, mode ) {
		if ( mode === 'dark' ) {
			var main = mix( v.primary_color, '#ffffff', 0.62 );
			return { bg: v.dark_bg, surface: v.dark_surface, text: v.dark_text, border: mix( v.dark_text, v.dark_bg, 0.14 ),
				muted: mix( v.dark_text, v.dark_bg, 0.62 ), primary: main, soft: mix( main, v.dark_bg, 0.2 ),
				fg: contrastText( main ), accent: v.accent_color };
		}
		return { bg: v.light_bg, surface: v.light_surface, text: v.light_text, border: mix( v.light_text, v.light_bg, 0.12 ),
			muted: mix( v.light_text, v.light_bg, 0.66 ), primary: v.primary_color, soft: mix( v.primary_color, v.light_bg, 0.11 ),
			fg: contrastText( v.primary_color ), accent: v.accent_color };
	}

	function showCheck( name, value ) {
		var level = value >= 4.5 ? 'good' : ( value >= 3 ? 'ok' : 'bad' );
		var icon = level === 'good' ? '✓ ' : '⚠ ';
		$( '[data-check="' + name + '"]' ).attr( 'data-level', level )
			.find( 'strong' ).text( icon + rootwpAdmin.contrast[ level ] + ' (' + value.toFixed( 1 ) + ':1)' );
	}

	function updateColors() {
		var v = colorValues();
		[ 'light', 'dark' ].forEach( function ( mode ) {
			var s = scheme( v, mode );
			$( '.rootwp-preview[data-mode="' + mode + '"]' ).css( {
				'--p-bg': s.bg, '--p-surface': s.surface, '--p-text': s.text, '--p-border': s.border, '--p-muted': s.muted,
				'--p-primary': s.primary, '--p-soft': s.soft, '--p-fg': s.fg, '--p-accent': s.accent
			} );
			showCheck( mode + '-text', ratio( s.text, s.bg ) );
			showCheck( mode + '-link', ratio( s.primary, s.bg ) );
			showCheck( mode + '-button', ratio( s.fg, s.primary ) );
		} );
		// Marca a paleta que corresponde às cores atuais, se houver.
		$( '.rootwp-palette' ).each( function () {
			var p = rootwpAdmin.palettes[ $( this ).data( 'palette' ) ];
			var same = Object.keys( p ).every( function ( k ) { return ( v[ k ] || '' ).toLowerCase() === p[ k ]; } );
			$( this ).toggleClass( 'is-active', same );
		} );
	}

	function applyColors( colors ) {
		$( '.rootwp-color-live' ).each( function () {
			var key = $( this ).data( 'key' );
			if ( colors[ key ] ) $( this ).wpColorPicker( 'color', colors[ key ] );
		} );
		updateColors();
	}

	$( function () {
		if ( $( '.rootwp-color-live' ).length ) {
			$( '.rootwp-color-live' ).wpColorPicker( {
				change: function () { setTimeout( updateColors, 0 ); },
				clear: function () { setTimeout( updateColors, 0 ); }
			} );
			$( '.rootwp-palette' ).on( 'click', function () {
				applyColors( rootwpAdmin.palettes[ $( this ).data( 'palette' ) ] );
			} );
			$( '.rootwp-colors-reset' ).on( 'click', function () { applyColors( rootwpAdmin.defaults ); } );
			updateColors();
		}

		toggleTopics();
		$( 'input[name="rootwp_options[home_topics_mode]"]' ).on( 'change', toggleTopics );

		$( '.rootwp-sortable' ).each( function () {
			var $list = $( this );
			$list.sortable( {
				handle: '.dashicons-menu',
				axis: 'y',
				update: function () { saveSortable( $list ); }
			} );
			$list.on( 'change', 'input[type="checkbox"]', function () { saveSortable( $list ); } );
		} );

		togglePanels();
		$( '.rootwp-color' ).wpColorPicker();
		$( 'input[name="rootwp_options[logo_type]"]' ).on( 'change', togglePanels );

		$( '.rootwp-media__select' ).on( 'click', function ( e ) {
			var $wrap = $( this ).closest( '.rootwp-media' );
			e.preventDefault();

			frame = wp.media( {
				title: $wrap.data( 'title' ) || rootwpAdmin.title,
				button: { text: rootwpAdmin.button },
				library: { type: 'image' },
				multiple: false
			} );

			frame.on( 'select', function () {
				var file = frame.state().get( 'selection' ).first().toJSON();
				var url = file.sizes && file.sizes.medium ? file.sizes.medium.url : file.url;
				$wrap.find( '.rootwp-media__id' ).val( file.id );
				$wrap.find( '.rootwp-media__preview' ).html( $( '<img alt="">' ).attr( 'src', url ) );
				$wrap.find( '.rootwp-media__remove' ).prop( 'hidden', false );
			} );

			frame.open();
		} );

		$( '.rootwp-media__remove' ).on( 'click', function ( e ) {
			var $wrap = $( this ).closest( '.rootwp-media' );
			e.preventDefault();
			$wrap.find( '.rootwp-media__id' ).val( '' );
			$wrap.find( '.rootwp-media__preview' ).empty();
			$( this ).prop( 'hidden', true );
		} );
	} );
} )( jQuery );
