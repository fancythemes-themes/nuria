<?php
/**
 * Nuria functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 *
 * 
 * @package Nuria
 * @since Nuria 1.0
 */

if ( ! function_exists( 'nuria_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * Create your own nuria_setup() function to override in a child theme.
 *
 * @since Nuria 1.0
 */
function nuria_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed at WordPress.org. See: https://translate.wordpress.org/projects/wp-themes/nuria
	 * If you're building a theme based on Nuria, use a find and replace
	 * to change 'nuria' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'nuria' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for custom logo.
	 *
	 *  @since Nuria 1.0
	 */
	add_theme_support( 'custom-logo', array(
		'height'      => 300,
		'width'       => 300,
		'flex-height' => true,
	) );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 1200, 9999 );

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary'      => esc_html__( 'Primary Menu', 'nuria' ),
		'social'       => esc_html__( 'Social Links Menu', 'nuria' ),
		'footer-menu'  => esc_html__( 'Footer Menu', 'nuria' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'image',
		'video',
		'quote',
		'link',
		'gallery',
		'audio',
	) );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	add_editor_style( array( 'css/editor-style.css', nuria_fonts_url() ) );

	// Indicate widget sidebars can use selective refresh in the Customizer.
	add_theme_support( 'customize-selective-refresh-widgets' );
}
endif; // nuria_setup
add_action( 'after_setup_theme', 'nuria_setup' );

/**
 * Sets the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 *
 * @since Nuria 1.0
 */
function nuria_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'nuria_content_width', 840 );
}
add_action( 'after_setup_theme', 'nuria_content_width', 0 );

/**
 * Registers a widget area.
 *
 * @link https://developer.wordpress.org/reference/functions/register_sidebar/
 *
 * @since Nuria 1.0
 */
function nuria_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'nuria' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'nuria' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Top Featured Widget Area', 'nuria' ),
		'description'	=> esc_html__( 'Placed on the top before main content in the homepage. Best use for featured posts.', 'nuria'),
		'id'            => 'header-widget-full-width',
		'before_widget' => '<section id="%1$s" class="widget header-widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Bottom Featured Widget Area', 'nuria' ),
		'id'            => 'footer-widget-full-width',
		'before_widget' => '<section id="%1$s" class="widget footer-widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer Widget 1', 'nuria' ),
		'id'            => 'footer-widget-1',
		'before_widget' => '<section id="%1$s" class="widget footer-widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer Widget 2', 'nuria' ),
		'id'            => 'footer-widget-2',
		'before_widget' => '<section id="%1$s" class="widget footer-widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer Widget 3', 'nuria' ),
		'id'            => 'footer-widget-3',
		'before_widget' => '<section id="%1$s" class="widget footer-widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer Widget 4', 'nuria' ),
		'id'            => 'footer-widget-4',
		'before_widget' => '<section id="%1$s" class="widget footer-widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'nuria_widgets_init' );

if ( ! function_exists( 'nuria_fonts_url' ) ) :
/**
 * Register Google fonts for Nuria.
 *
 * Create your own nuria_fonts_url() function to override in a child theme.
 *
 * @since Nuria 1.0
 *
 * @return string Google fonts URL for the theme.
 */
function nuria_fonts_url() {
	$fonts_url = '';
	$fonts     = array();
	$subsets   = 'latin,latin-ext';

	/* translators: If there are characters in your language that are not supported by Josefin Sans, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Josefin Sans font: on or off', 'nuria' ) ) {
		$fonts[] = 'Josefin Sans:400,700';
	}

	/* translators: If there are characters in your language that are not supported by IM Fell French Canon, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'IM Fell French Canon font: on or off', 'nuria' ) ) {
		$fonts[] = 'IM Fell French Canon:400i';
	}

	/* translators: If there are characters in your language that are not supported by Source Sans Pro, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Source Sans Pro font: on or off', 'nuria' ) ) {
		$fonts[] = 'Source Sans Pro:400,400i,700,700i';
	}

	if ( $fonts ) {
		$fonts_url = add_query_arg( array(
			'family' => urlencode( implode( '|', $fonts ) ),
			'subset' => urlencode( $subsets ),
		), 'https://fonts.googleapis.com/css' );
	}

	return $fonts_url;
}
endif;

/**
 * Handles JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since Nuria 1.0
 */
function nuria_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'nuria_javascript_detection', 0 );

/**
 * Enqueues scripts and styles.
 *
 * @since Nuria 1.0
 */
function nuria_scripts() {
	// Add custom fonts, used in the main stylesheet.
	wp_enqueue_style( 'nuria-fonts', nuria_fonts_url(), array(), null );

	// Add Genericons, used in the main stylesheet.
	//wp_enqueue_style( 'genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.4.1' );

	// Theme stylesheet.
	wp_enqueue_style( 'nuria-style', get_stylesheet_uri() );

	// Load the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'nuria-ie', get_template_directory_uri() . '/css/ie.css', array( 'nuria-style' ), '20160816' );
	wp_style_add_data( 'nuria-ie', 'conditional', 'lt IE 10' );

	// Load the Internet Explorer 8 specific stylesheet.
	wp_enqueue_style( 'nuria-ie8', get_template_directory_uri() . '/css/ie8.css', array( 'nuria-style' ), '20160816' );
	wp_style_add_data( 'nuria-ie8', 'conditional', 'lt IE 9' );

	// Load the Internet Explorer 7 specific stylesheet.
	wp_enqueue_style( 'nuria-ie7', get_template_directory_uri() . '/css/ie7.css', array( 'nuria-style' ), '20160816' );
	wp_style_add_data( 'nuria-ie7', 'conditional', 'lt IE 8' );

	// Load the html5 shiv.
	wp_enqueue_script( 'nuria-html5', get_template_directory_uri() . '/js/html5.js', array(), '3.7.3' );
	wp_script_add_data( 'nuria-html5', 'conditional', 'lt IE 9' );

	wp_enqueue_script( 'nuria-fitvids', get_template_directory_uri() . '/js/jquery.fitvids.js', array( 'jquery' ), '20160816' );

	wp_enqueue_script( 'nuria-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20160816', true );

	wp_enqueue_script( 'svgxuse', get_template_directory_uri() . '/js/svgxuse.js', array(), '1.2.4' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'nuria-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20160816' );
	}

	wp_enqueue_script( 'nuria-script', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '20160816', true );

	wp_localize_script( 'nuria-script', 'screenReaderText', array(
		'expand'   => esc_html__( 'expand child menu', 'nuria' ),
		'collapse' => esc_html__( 'collapse child menu', 'nuria' ),
		'loadMoreText' => esc_html__( 'Load More', 'nuria' ),
		'loadingText'  => esc_html__( 'Loading...', 'nuria' ),
	) );

	wp_enqueue_script( 'flexslider', get_template_directory_uri() . '/js/jquery.flexslider.js', array( 'jquery' ), '20160816', true );

	$featured_posts_type =  get_theme_mod('featured_posts_type', 'carousel');
	wp_localize_script( 'nuria-script', 'sliderOptions', array(
		'slideType'     => $featured_posts_type,	
		'slideshowSpeed'	=> 5000,
		'prevText'			=> sprintf(
									'<span class="screen-reader-text">%1$s</span>%2$s',
									esc_html__('Previous', 'nuria'),
									nuria_svg_icon('arrow-left')
								 ),
		'nextText'			=> sprintf(
									'<span class="screen-reader-text">%1$s</span>%2$s',
									esc_html__('Next', 'nuria'),
									nuria_svg_icon('arrow-right')
								 ),
		'itemWidth'			=> ( $featured_posts_type == 'carousel' ) ? 300 : 0,
		'minItems'			=> ( $featured_posts_type == 'carousel' ) ? 3 : 1,
		'maxItems'			=> ( $featured_posts_type == 'carousel' ) ? 3 : 0
	) );
}
add_action( 'wp_enqueue_scripts', 'nuria_scripts' );


/**
 * Enqueues admin scripts and styles.
 *
 * @since Nuria 1.0
 */
function nuria_admin_enqueue_scripts( $hook ) {
	if ( $hook == 'widgets.php' ) {
		wp_enqueue_style( 'nuria-admin', get_template_directory_uri() . '/css/admin.css' );
	}
}
add_action( 'admin_enqueue_scripts', 'nuria_admin_enqueue_scripts' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @since Nuria 1.0
 *
 * @param array $classes Classes for the body element.
 * @return array (Maybe) filtered body classes.
 */
function nuria_body_classes( $classes ) {
	// Adds a class of custom-background-image to sites with a custom background image.
	if ( get_background_image() ) {
		$classes[] = 'custom-background-image';
	}

	// Adds a class of group-blog to sites with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of no-sidebar to sites without active sidebar.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	return $classes;
}
add_filter( 'body_class', 'nuria_body_classes' );

/**
 * Converts a HEX value to RGB.
 *
 * @since Nuria 1.0
 *
 * @param string $color The original color, in 3- or 6-digit hexadecimal form.
 * @return array Array containing RGB (red, green, and blue) values for the given
 *               HEX code, empty array otherwise.
 */
function nuria_hex2rgb( $color ) {
	$color = trim( $color, '#' );

	if ( strlen( $color ) === 3 ) {
		$r = hexdec( substr( $color, 0, 1 ).substr( $color, 0, 1 ) );
		$g = hexdec( substr( $color, 1, 1 ).substr( $color, 1, 1 ) );
		$b = hexdec( substr( $color, 2, 1 ).substr( $color, 2, 1 ) );
	} else if ( strlen( $color ) === 6 ) {
		$r = hexdec( substr( $color, 0, 2 ) );
		$g = hexdec( substr( $color, 2, 2 ) );
		$b = hexdec( substr( $color, 4, 2 ) );
	} else {
		return array();
	}

	return array( 'red' => $r, 'green' => $g, 'blue' => $b );
}

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

if ( class_exists( 'Jetpack') ) :
	/**
	 * Jetpack. Only include if Jetpack plugin installed.
	 *
	 */
	require get_template_directory() . '/inc/jetpack.php';
endif;

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Customizer framework additions.
 */
require get_template_directory() . '/inc/customizer-simple.php';

/**
 * Customizer sanitazion callback functions.
 */
require get_template_directory() . '/inc/sanitize-callbacks.php';

/**
 * Posts widget.
 */
require get_template_directory() . '/inc/widgets/recent-posts.php';


/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for content images
 *
 * @since Nuria 1.0
 *
 * @param string $sizes A source size value for use in a 'sizes' attribute.
 * @param array  $size  Image size. Accepts an array of width and height
 *                      values in pixels (in that order).
 * @return string A source size value for use in a content image 'sizes' attribute.
 */
function nuria_content_image_sizes_attr( $sizes, $size ) {
	$width = $size[0];

	840 <= $width && $sizes = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 62vw, 840px';

	if ( 'page' === get_post_type() ) {
		840 > $width && $sizes = '(max-width: ' . $width . 'px) 85vw, ' . $width . 'px';
	} else {
		840 > $width && 600 <= $width && $sizes = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 61vw, (max-width: 1362px) 45vw, 600px';
		600 > $width && $sizes = '(max-width: ' . $width . 'px) 85vw, ' . $width . 'px';
	}

	return $sizes;
}
add_filter( 'wp_calculate_image_sizes', 'nuria_content_image_sizes_attr', 10 , 2 );

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for post thumbnails
 *
 * @since Nuria 1.0
 *
 * @param array $attr Attributes for the image markup.
 * @param int   $attachment Image attachment ID.
 * @param array $size Registered image size or flat array of height and width dimensions.
 * @return string A source size value for use in a post thumbnail 'sizes' attribute.
 */
function nuria_post_thumbnail_sizes_attr( $attr, $attachment, $size ) {
	if ( 'post-thumbnail' === $size ) {
		is_active_sidebar( 'sidebar-1' ) && $attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 60vw, (max-width: 1362px) 62vw, 840px';
		! is_active_sidebar( 'sidebar-1' ) && $attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 88vw, 1200px';
	}
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'nuria_post_thumbnail_sizes_attr', 10 , 3 );

/**
 * Modifies tag cloud widget arguments to have all tags in the widget same font size.
 *
 * @since Nuria 1.0
 *
 * @param array $args Arguments for tag cloud widget.
 * @return array A new modified arguments.
 */
function nuria_widget_tag_cloud_args( $args ) {
	$args['largest'] = 1;
	$args['smallest'] = 1;
	$args['unit'] = 'em';
	return $args;
}
add_filter( 'widget_tag_cloud_args', 'nuria_widget_tag_cloud_args' );


/**
 * Replace the string 'icon_replace' on SVG use xlink:href attribute from wp_nav_menu's link_before argument by the escaped domain name from link url
 * the dot(.) on domain replaced by dash(-), eg. plus.google.com -> plus-google-com
 * so for the menu with URL linked to google plus domain will have SVG icon with use tag like
 * <use xlink:href="http://your-domain/wp-content/themes/fusion/icons/symbol-defs.svg#icon-social-plus-google-com"></use>
 *
 * see also function fusion_svg_icon() in the template-tags.php
 * see also the declaration of wp_nav_menu for theme location "social" in the header.php and footer.php
 *
 * @since Nuria 1.0
 *
 * @param string $item_output The menu item's starting HTML output.
 * @param object $item		Menu item data object.
 * @param int	$depth	   Depth of menu item. Used for padding.
 * @param array  $args		An array of arguments. @see wp_nav_menu()
 */
function nuria_social_menu_item_output ( $item_output, $item, $depth, $args ) {
	$parsed_url = parse_url( $item->url);
	$class = ! empty( $parsed_url['host'] ) ? nuria_map_domain_class( $parsed_url['host'] ) : '';
	$output = str_replace('icon_replace', 'social-' . $class, $item_output);
	return $output;
}

/**
 * Extract domain name without tld, used as class name or icon identifier
 * Used in function nuria_social_menu_item_output()
 *
 * @since Nuria 1.0
 *
 * @param string $domain a domain name
 */
function nuria_map_domain_class( $domain ) {
	$class = '';
	if ( strpos( 'plus.google.com', $domain ) !== false ) {
		$class = 'google-plus-1';
	} else {
		$texts = explode('.', $domain );
		$class = $texts[count( $texts ) - 2];
	}
	return $class;
}

/**
 * Helper for kses that only span tag allowed
 *
 * @since Nuria 1.0
 */
function nuria_only_allow_span() {
	$allowed_tags = 
		array(
			'span' => array(
				'class' => array(),
			),
			'br' => array(),
			'em' => array(),
			'strong' => array(),
		);

	return $allowed_tags;

}


/**
 * Setup a font controls & settings for Easy Google Fonts plugin (if installed)
 *
 * @since Nuria 1.0
 *
 * @param array $options Default control list by the plugin.
 * @return array Modified $options parameter to applied in filter 'tt_font_get_option_parameters'.
 */
function nuria_easy_google_fonts($options) {

	// Just replace all the plugin default font control

	unset(  $options['tt_default_body'],
			$options['tt_default_heading_2'],
			$options['tt_default_heading_3'],
			$options['tt_default_heading_4'],
			$options['tt_default_heading_5'],
			$options['tt_default_heading_6'],
			$options['tt_default_heading_1']
		);

	$new_options = array(
		
		'nuria_default_body' => array(
			'name'        => 'nuria_default_body',
			'title'       => esc_html__( 'Body & Paragraphs', 'nuria' ),
			'description' => esc_html__( "Please select a font for the theme's body and paragraph text", 'nuria' ),
			'properties'  => array( 'selector' => apply_filters( 'nuria_default_body_font', 'body, input, select, textarea, blockquote cite, .entry-footer, .site-main div.sharedaddy h3.sd-title' ) ),
		),

		'nuria_default_menu' => array(
			'name'        => 'nuria_default_menu',
			'title'       => esc_html__( 'Menu', 'nuria' ),
			'description' => esc_html__( "Please select a font for the theme's menu styles", 'nuria' ),
			'properties'  => array( 'selector' => apply_filters( 'nuria_default_heading', '.main-navigation' ) ),
		),

		'nuria_default_entry_title' => array(
			'name'        => 'nuria_default_entry_title',
			'title'       => esc_html__( 'Entry Title', 'nuria' ),
			'description' => esc_html__( "Please select a font for the theme's Entry title styles", 'nuria' ),
			'properties'  => array( 'selector' => apply_filters( 'nuria_default_menu_font', '.site-title, .entry-title, .post-navigation .post-title, .comment-meta .fn, .page-title, .site-main #jp-relatedposts .jp-relatedposts-items-visual h4.jp-relatedposts-post-title a, .site-main #jp-relatedposts h3.jp-relatedposts-headline, button, input[type="button"], input[type="reset"], input[type="submit"], .load-more a ' ) ),
		),

		'nuria_default_entry_meta' => array(
			'name'        => 'nuria_default_entry_meta',
			'title'       => esc_html__( 'Entry Meta', 'nuria' ),
			'description' => esc_html__( "Please select a font for the theme's Entry meta styles", 'nuria' ),
			'properties'  => array( 'selector' => apply_filters( 'nuria_default_meta_font', '.entry-meta, .site-info, .site-breadcrumbs, .posted-on, .post-navigation .meta-nav, .comment-metadata, .pingback .edit-link, .comment-reply-link, .site-content #jp-relatedposts .jp-relatedposts-items .jp-relatedposts-post .jp-relatedposts-post-date, .site-content #jp-relatedposts .jp-relatedposts-items .jp-relatedposts-post .jp-relatedposts-post-context, .site-featured-posts .more-featured-title, .page-header .archive-title-pre' ) ),
		),

		'nuria_default_widget_title' => array(
			'name'        => 'nuria_default_widget_title',
			'title'       => esc_html__( 'Widget Title', 'nuria' ),
			'description' => esc_html__( "Please select a font for the theme's Widget title styles", 'nuria' ),
			'properties'  => array( 'selector' => apply_filters( 'nuria_default_widget_title_font', '.widget .widget-title, .widget-recent-posts .tab-control a span, .load-more a, .comments-title, .comment-reply-title, #page .site-main #jp-relatedposts h3.jp-relatedposts-headline, .site-main #jp-relatedposts h3.jp-relatedposts-headline em, .widget-recent-posts .image-medium.sort-comment_count li .post-thumbnail:before  ' ) ),
		),


	);

	return array_merge( $options, $new_options);
}
add_filter( 'tt_font_get_option_parameters', 'nuria_easy_google_fonts', 10 , 1 );
