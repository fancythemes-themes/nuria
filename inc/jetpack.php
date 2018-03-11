<?php
/**
 * All functions that hooked to the Jetpack's filters and actions
 *
 * 
 * @package Nuria
 * @since Nuria 1.0
 */

/**
 * Remove the jetpack's share module from the_content() filter
 * It will called in the content's template tags, see template-parts/content.php, template-parts/content-single.php etc.
 *
 * @since Nuria 1.0
 */
function nuria_remove_jp_share( $domain ) {
	remove_filter( 'the_content', 'sharing_display',19 );
	remove_filter( 'the_excerpt', 'sharing_display',19 );
}
add_action( 'loop_start', 'nuria_remove_jp_share' );

/**
 * Remove the jetpack's Related Posts module from the_content() filter
 * It will called in the content's template tags, see template-parts/content.php, template-parts/content-single.php etc.
 *
 * @since Nuria 1.0
 */
function nuria_remove_jp_related( $domain ) {
	if ( class_exists( 'Jetpack_RelatedPosts' ) ) {
		$jprp = Jetpack_RelatedPosts::init();
		$callback = array( $jprp, 'filter_add_target_to_dom' );
		remove_filter( 'the_content', $callback, 40 );
	}
}
add_action( 'loop_start', 'nuria_remove_jp_related' );

/**
 * Add an SVG icon into the Sharedaddy display functon, based on the services.
 *
 * @since Nuria 1.0
 *
 * @param string $text Sharing service text.
 * @param object $service Sharing service properties.
 * @param int|false $id Sharing ID.
 * @param array $args Array of sharing service options.
 */
function nuria_jetpack_sharing_display_text( $text, $service, $id, $args ) {
	if ( class_exists( 'Jetpack_Options' ) && Jetpack_Options::get_option_and_ensure_autoload( 'sharedaddy_disable_resources', '0' ) ) {
		$text = nuria_svg_icon( 'social-' . $service->get_id() ) . '</span><span class="screen-reader-text">' . $text ;
	}

	return $text;
}
add_filter( 'jetpack_sharing_display_text', 'nuria_jetpack_sharing_display_text', 4, 10 );

/**
 * Add a class to the sharedaddy.
 *
 * @since Nuria 1.0
 *
 * @param string $text Sharing service text.
 * @param object $service Sharing service properties.
 * @param int|false $id Sharing ID.
 * @param array $args Array of sharing service options.
 */
function nuria_jetpack_sharing_display_classes( $klasses, $service, $id, $args ) {
	if ( class_exists( 'Jetpack_Options' ) && Jetpack_Options::get_option_and_ensure_autoload( 'sharedaddy_disable_resources', '0' ) ) {
		$klasses[] = 'no-resources' ;
	}

	return $klasses;
}
add_filter( 'jetpack_sharing_display_classes', 'nuria_jetpack_sharing_display_classes', 4, 10 );

/**
 * Add inline CSS for  .
 *
 * @since Nuria 1.0
 *
 */
function nuria_jetpack_inline_css() {
	if ( ! class_exists( 'Jetpack' ) ) {
		return false;
	}

	$css = '
		.comment-form > .comment-subscription-form {
			margin-top: 28px;
			margin-bottom: 0;
		}
		.comment-form > .comment-subscription-form:last-child {
			margin-bottom: 0;
			margin-top: 0;
		}

		#subscribe-text p {
			margin-bottom: 31px;
		}
		#subscribe-email {
			margin-bottom: 42px;
		}
	';

	if ( Jetpack::is_module_active( 'sharedaddy' ) ) {
		$css .= '
			/* Sharedaddy */ 
			.site-main div.sharedaddy h3.sd-title {
				font-size: 16px;
				font-size: 1rem;
				line-height: 1.625;
				font-weight: 400;
			}
			.site-main div.sharedaddy h3.sd-title:before {
				display: none;
			}
			';

		if ( get_option( 'sharedaddy_disable_resources' ) == 1 ) {
			$css .= '
				.sharedaddy ul {
					margin-left: 0;
					margin-top: 21px;
				}
				.sharedaddy ul li {
					list-style: none;
					display: inline-block;
					border-bottom: 2px solid #000000;
				}
				.sharedaddy ul li a {
					color: #000000;
					padding: 8px 15px;
					border-left: 1px solid #dddddd;
					display: block;
				}
				.sharedaddy ul li:first-child a {
					border-left: none;
				}
				.sharedaddy ul li a:hover,
				.sharedaddy ul li a:focus {
					color: #ff2222;
				}
				.sharedaddy ul .share-end {
					display: none;
				}
			';
		}
	}

	if ( Jetpack::is_module_active( 'related-posts' ) && is_single() )  {
		$css .= '
			/* Related Posts */

			.site-main #jp-relatedposts {
				margin: 40px -40px 0;
				padding: 40px 40px 0;
				border-top: 1px solid #dddddd;
			}

			#jp-relatedposts .jp-relatedposts-items-visual .jp-relatedposts-post {
				opacity: 1;
			}
			.site-main #jp-relatedposts .jp-relatedposts-items {
				margin: 0 -40px;
				text-align: center;
				display:flex;
				flex-wrap: wrap;
			}
			.site-main #jp-relatedposts .jp-relatedposts-items-visual .jp-relatedposts-post {
				flex: 0 0 100%;
				padding: 0 40px;
				margin-bottom: 0;
				display: flex;
				flex-direction: column;
				margin-bottom: 0;
			}
			.site-main #jp-relatedposts .jp-relatedposts-items-visual > .jp-relatedposts-post-a {
				order: 1;
			}
			.site-main #jp-relatedposts .jp-relatedposts-items-visual .jp-relatedposts-post-date {
				order: 2;
				margin-bottom: 5px;
			}
			.site-main #jp-relatedposts .jp-relatedposts-items-visual .jp-relatedposts-post-context {
				order: 3;
				margin-bottom: 5px;
			}
			.site-main #jp-relatedposts .jp-relatedposts-items-visual h4.jp-relatedposts-post-title {
				order: 4;
			}
			.site-main #jp-relatedposts .jp-relatedposts-items-visual h4.jp-relatedposts-post-title a {
				font-family: "Josefin Sans", "Helvetica Neue", Helvetica, Arial, sans-serif;
				font-size: 22px;
				font-size: 1.375rem;
				line-height: 1.454545;
				font-weight: bold;
				margin-bottom: 5px;
				letter-spacing: 0;
			}

			.site-main #jp-relatedposts .jp-relatedposts-items-visual h4.jp-relatedposts-post-excerpt {
				margin-bottom: 5px;
			}
			.site-main #jp-relatedposts img {
				margin-bottom: 30px;
			}
			.site-main #jp-relatedposts h3.jp-relatedposts-headline {
				width: 100%;
			}
			.site-main #jp-relatedposts h3.jp-relatedposts-headline em {
				font-weight: bold;
				font-style: normal;
			}
			.site-main #jp-relatedposts h3.jp-relatedposts-headline em::before {
				display: none;
			}
			#jp-relatedposts .jp-relatedposts-items .jp-relatedposts-post .jp-relatedposts-post-title a {
				color: #000000;
			}
			#jp-relatedposts .jp-relatedposts-items .jp-relatedposts-post .jp-relatedposts-post-title a:hover,
			#jp-relatedposts .jp-relatedposts-items .jp-relatedposts-post .jp-relatedposts-post-title a:focus {
				color: #ff0000;
			}
			#jp-relatedposts .jp-relatedposts-items .jp-relatedposts-post .jp-relatedposts-post-date,
			#jp-relatedposts .jp-relatedposts-items .jp-relatedposts-post .jp-relatedposts-post-context {
				font-family: Rosario, "Helvetica Neue", sans-serif;
				font-size: 16px;
				font-size: 1rem;
				line-height: 1.625;
			}
			@media only screen and (min-width: 640px) {
				.site-main #jp-relatedposts .jp-relatedposts-items-visual .jp-relatedposts-post {
					flex: 0 0 33.333333%;
					border-right: 1px dotted #dddddd;
				}
				.site-main #jp-relatedposts .jp-relatedposts-items-visual .jp-relatedposts-post:nth-child(3n) {
					border-right: none;
				}
			}
		';
	}

	wp_add_inline_style( 'nuria-style', $css  );
}
add_action( 'wp_enqueue_scripts', 'nuria_jetpack_inline_css', 10 );
