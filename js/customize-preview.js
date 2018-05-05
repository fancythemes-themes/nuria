/**
 * Live-update changed settings in real time in the Customizer preview.
 */

( function( $ ) {
	"use strict";

	var style = $( '#nuria-color-scheme-css' );
	var	api = wp.customize;

	if ( ! style.length ) {
		style = $( 'head' ).append( '<style type="text/css" id="nuria-color-scheme-css" />' )
		                    .find( '#nuria-color-scheme-css' );
	}

	// Site title.
	api( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );

	// Site tagline.
	api( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	// Add custom-background-image body class when background image is added.
	api( 'background_image', function( value ) {
		value.bind( function( to ) {
			$( 'body' ).toggleClass( 'custom-background-image', '' !== to );
		} );
	} );

	// Color Scheme CSS.
	api.bind( 'preview-ready', function() {
		api.preview.bind( 'update-color-scheme-css', function( css ) {
			style.html( css );
		} );
	} );

	function writeCSS(){
		var cssOutput = '';
		var before = '';
		var after = '';

		for ( i = 0; i < _customizerCSS.length ; i++ ){
			if ( api.instance( _customizerCSS[i].id ).get() && ( api.instance( _customizerCSS[i].id ).get() !== _customizerCSS[i].default ) ) {
				if ( _customizerCSS[i].mq !== 'global' ) {
					before = _customizerCSS[i].mq + ' { ';
					after = '}';
				}else{
					before = '';
					after = '';
				}
				cssOutput += before;
				if ( _customizerCSS[i].value_in_text == '' ){
					cssOutput += _customizerCSS[i].selector + '{' + _customizerCSS[i].property + ' : ' + api.instance( _customizerCSS[i].id ).get() + _customizerCSS[i].unit + '; }';
				/*}else if ( _customizerCSS[i].color_alpha !== '') {
					cssOutput += _customizerCSS[i].selector + '{' + _customizerCSS[i].property + ' : ' + toCSS( api.instance( _customizerCSS[i].id ).get(), _customizerCSS[i].color_alpha )  + '; }';*/
				}else{
					str = _customizerCSS[i].value_in_text;
					val = str.replace('%value%', api.instance( _customizerCSS[i].id ).get() );
					cssOutput += _customizerCSS[i].selector + '{' + _customizerCSS[i].property + ' : ' + val + '; }';
				}
				cssOutput += after;
			}
		}

		$('#nuria-preview-style-inline-css').text(cssOutput);
	}

	for ( var i = 0; i < _customizerCSS.length ; i++ ){
		wp.customize( _customizerCSS[i].id, function( value ) {
			value.bind( function( to ){
				writeCSS();
			} );
		});
	}

	$( document ).ready( function() {
		wp.customize.selectiveRefresh.bind( 'partial-content-rendered', function( placement ) {
			var slider, sliderOpts;
			if ( placement.container ) {
				slider = $( '.posts-slider', placement.container );
				//console.log( slider );
				if ( slider.length > 0 ) {
					sliderOpts = slider.data('slider-options');
					slider.flexslider( {
						selector: '.slides > article',
						animation: 'slide',
						controlNav: false,
						prevText: sliderOptions.prevText,
						nextText: sliderOptions.nextText,
						minItems: 1,
						maxItems: sliderOpts.maxItems,
						itemMargin: sliderOpts.itemMargin,
						itemWidth: 286,
						slideshow: sliderOpts.slideshow,
						slideshowSpeed: sliderOpts.slideshow_time,
					});
				}

			}
		} );
	});
} )( jQuery );
