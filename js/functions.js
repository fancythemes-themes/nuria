/* global screenReaderText */
/**
 * Theme functions file.
 *
 * Contains handlers for navigation and widget area.
 */

( function( $ ) {
	"use strict";

	var body			 = $( document.body ); 
	var masthead         = $( '#masthead' );
	var menuToggle       = masthead.find( '#menu-toggle' );
	var siteHeaderMenu   = masthead.find( '#site-header-menu' );
	var siteNavigation   = masthead.find( '#site-navigation' );
	var socialNavigation = masthead.find( '#social-navigation' );

	function initMainNavigation( container ) {

		// Add dropdown toggle that displays child menu items.
		var dropdownToggle = $( '<button />', {
			'class': 'dropdown-toggle',
			'aria-expanded': false
		} ).append( $( '<span />', {
			'class': 'screen-reader-text',
			text: screenReaderText.expand
		} ) );

		container.find( '.menu-item-has-children > a' ).after( dropdownToggle );

		// Toggle buttons and submenu items with active children menu items.
		container.find( '.current-menu-ancestor > button' ).addClass( 'toggled-on' );
		container.find( '.current-menu-ancestor > .sub-menu' ).addClass( 'toggled-on' );

		// Add menu items with submenus to aria-haspopup="true".
		container.find( '.menu-item-has-children' ).attr( 'aria-haspopup', 'true' );

		container.find( '.dropdown-toggle' ).click( function( e ) {
			var _this            = $( this ),
				screenReaderSpan = _this.find( '.screen-reader-text' );

			e.preventDefault();
			_this.toggleClass( 'toggled-on' );
			_this.next( '.children, .sub-menu' ).toggleClass( 'toggled-on' );

			// jscs:disable
			_this.attr( 'aria-expanded', _this.attr( 'aria-expanded' ) === 'false' ? 'true' : 'false' );
			// jscs:enable
			screenReaderSpan.text( screenReaderSpan.text() === screenReaderText.expand ? screenReaderText.collapse : screenReaderText.expand );
		} );
	}
	initMainNavigation( $( '.main-navigation' ) );

	// Enable menuToggle.
	( function() {

		// Return early if menuToggle is missing.
		if ( ! menuToggle.length ) {
			return;
		}

		menuToggle.on( 'click.nuria', function() {
			$( this ).add( siteHeaderMenu ).toggleClass( 'toggled-on' );
			$('.search-toggle').removeClass('toggled');

		} );
	} )();

	// Fix sub-menus for touch devices and better focus for hidden submenu items for accessibility.
	( function() {
		if ( ! siteNavigation.length || ! siteNavigation.children().length ) {
			return;
		}

		// Toggle `focus` class to allow submenu access on tablets.
		function toggleFocusClassTouchScreen() {
			if ( window.innerWidth >= 910 ) {
				$( document.body ).on( 'touchstart.nuria', function( e ) {
					if ( ! $( e.target ).closest( '.main-navigation li' ).length ) {
						$( '.main-navigation li' ).removeClass( 'focus' );
					}
				} );
				siteNavigation.find( '.menu-item-has-children > a' ).on( 'touchstart.nuria', function( e ) {
					var el = $( this ).parent( 'li' );

					if ( ! el.hasClass( 'focus' ) ) {
						e.preventDefault();
						el.toggleClass( 'focus' );
						el.siblings( '.focus' ).removeClass( 'focus' );
					}
				} );
			} else {
				siteNavigation.find( '.menu-item-has-children > a' ).unbind( 'touchstart.nuria' );
			}
		}

		if ( 'ontouchstart' in window ) {
			$( window ).on( 'resize.nuria', toggleFocusClassTouchScreen );
			toggleFocusClassTouchScreen();
		}

		siteNavigation.find( 'a' ).on( 'focus.nuria blur.nuria', function() {
			$( this ).parents( '.menu-item' ).toggleClass( 'focus' );
		} );
	} )();

	function sliderWidthInit( slider ) {
		var width = 286; 
		slider.vars.minItems = Math.floor( slider.width() / width );
	}

	function resizeFormatPosts() {
		var embedMargin = body.outerWidth() >= 1200 ? 0 : 0;
		$('.single .site-main .format-video .entry-content p, .single .site-main .format-audio .entry-content p, .classic-view .site-main .format-video .entry-content p, .classic-view .site-main .format-audio .entry-content p').has('.fluid-width-video-wrapper').first().css({ marginRight: 0, marginLeft: 0 });
		$('.single .site-main .format-gallery .entry-content .tiled-gallery, .classic-view .site-main .format-gallery .entry-content .tiled-gallery').first().css({ marginRight: 0, marginLeft: 0 })
	}

	$( document ).ready( function() {

		$(".hentry").fitVids();
		resizeFormatPosts();


		$('.posts-slider').each( function(){
			var slider = $(this);
			var sliderOpts = slider.data('slider-options');
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
				init: function( slider ) {
					sliderWidthInit(slider);
					slider.doMath();
					$(window).on('resize.nuria', function() {
						sliderWidthInit(slider);
						slider.doMath();
					});
				}
			});
		} );

		$('.search-toggle').on( 'click.nuria', function() {
			$(this).toggleClass('toggled');
			if ( $(this).hasClass('toggled') ) {
				$('.site-search .search-field').focus();
				menuToggle.add(siteHeaderMenu).removeClass('toggled-on');
			}
		} );

		$('.site-search .search-field, .site-search .search-submit').on( 'focus', function() {
			$('.search-toggle').addClass('toggled');
		});

 		$('.load-more a').on('click.nuria', function (e) {
			e.preventDefault();

			//widgetId = $(this).parents('.widget').attr("id");
			$(this).addClass('loading').text( screenReaderText.loadingText );

			$.ajax({
				type: "GET",
				url: $(this).attr('href') + '#main',
				dataType: "html",
				success: function (out) {
					var result = $(out).find('#main .post');
					var nextlink = $(out).find('#main .load-more a').attr('href');
					$('#main .load-more').before( result.fadeIn(800) );
					$('#main .load-more a').removeClass('loading').text( screenReaderText.loadMoreText );
					if (nextlink != undefined) {
						$('#main .load-more a').attr('href', nextlink);
					} else {
						$('#main .load-more').remove();
					}
				}
			});
		});

	} );
} )( jQuery );
