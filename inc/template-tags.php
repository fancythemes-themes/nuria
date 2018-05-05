<?php
/**
 * Custom Nuria template tags
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * 
 * @package Nuria
 * @since Nuria 1.0
 */

if ( ! function_exists( 'nuria_entry_meta' ) ) :
/**
 * Prints HTML with meta information for the categories, tags.
 *
 * Create your own nuria_entry_meta() function to override in a child theme.
 *
 * @since Nuria 1.0
 */
function nuria_entry_meta() {

	$categories_list = get_the_category_list( _x( ' ', 'Used between list items, there is a space.', 'nuria' ) );
	if ( $categories_list && nuria_categorized_blog() ) {
		printf( '<span class="cat-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
			esc_html_x( 'Categories: ', 'Used before category names.', 'nuria' ),
			$categories_list
		);
	}

	if ( 'post' === get_post_type() ) {
		$author_avatar_size = apply_filters( 'nuria_author_avatar_size', 50 );
		printf( '<span class="byline"><span class="author vcard">%1$s<span class="screen-reader-text">%2$s </span> <a class="url fn n" href="%3$s">%4$s</a></span></span>',
			get_avatar( get_the_author_meta( 'user_email' ), $author_avatar_size ),
			esc_html_x( 'Author', 'Used before post author name.', 'nuria' ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			get_the_author()
		);
	}

	if ( in_array( get_post_type(), array( 'post', 'attachment' ) ) ) {
		nuria_entry_date();
	}

	if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( sprintf( 
			/* translators: %s is the post title */
			wp_kses( __( 'No comment<span class="screen-reader-text"> on %s</span>', 'nuria' ), nuria_only_allow_span() ),
			get_the_title() ) );
		echo '</span>';
	}

	edit_post_link(
		sprintf(
			/* translators: %s: Name of current post */
			wp_kses( __( 'Edit<span class="screen-reader-text"> "%s"</span>', 'nuria' ), nuria_only_allow_span() ),
			get_the_title()
		),
		'<span class="edit-link">',
		'</span>'
	);

}
endif;

if ( ! function_exists( 'nuria_entry_date' ) ) :
/**
 * Prints HTML with date information for current post.
 *
 * Create your own nuria_entry_date() function to override in a child theme.
 *
 * @since Nuria 1.0
 */
function nuria_entry_date() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		get_the_date(),
		esc_attr( get_the_modified_date( 'c' ) ),
		get_the_modified_date()
	);

	printf( '<span class="posted-on"><span class="screen-reader-text">%1$s </span><a href="%2$s" rel="bookmark">%3$s</a></span>',
		esc_html_x( 'Posted on', 'Used before publish date.', 'nuria' ),
		esc_url( get_permalink() ),
		$time_string
	);
}
endif;

if ( ! function_exists( 'nuria_entry_taxonomies' ) ) :
/**
 * Prints HTML with category and tags for current post.
 *
 * Create your own nuria_entry_taxonomies() function to override in a child theme.
 *
 * @since Nuria 1.0
 */
function nuria_entry_taxonomies() {

	$tags_list = get_the_tag_list( '', wp_kses( _x( ', ', 'Used between list items, there is a space after the comma.', 'nuria' ), nuria_only_allow_span() ) );
	if ( $tags_list ) {
		printf( '<p><span class="tags-links"><span>%1$s </span>%2$s</span></p>',
			esc_html_x( 'Tags: ', 'Used before tag names.', 'nuria' ),
			$tags_list
		);
	}
}
endif;

if ( ! function_exists( 'nuria_post_thumbnail' ) ) :
/**
 * Displays an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index views, or a div
 * element when on single views.
 *
 * Create your own nuria_post_thumbnail() function to override in a child theme.
 *
 * @since Nuria 1.0
 */
function nuria_post_thumbnail() {
	if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
		return;
	}

	if ( is_singular() ) :
	?>

	<div class="post-thumbnail">
		<?php the_post_thumbnail(); ?>
	</div><!-- .post-thumbnail -->

	<?php else : ?>

	<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
		<?php the_post_thumbnail( 'post-thumbnail', array( 'alt' => the_title_attribute( 'echo=0' ) ) ); ?>
	</a>

	<?php endif; // End is_singular()
}
endif;

if ( ! function_exists( 'nuria_posts_pagination' ) ) :
/**
 * Displays the pagination or load more button.
 *
 * Create your own nuria_posts_pagination() function to override in a child theme.
 *
 * @since Nuria 1.0
 */
function nuria_posts_pagination() {
	if ( get_theme_mod( 'pagination_load_more', false ) ) {
		echo '<div class="load-more">';
		next_posts_link( esc_html__( 'Load More', 'nuria' ) );
		echo '</div>';
	} else {
		// Previous/next page navigation.
		the_posts_pagination( array(
			'prev_text'          => esc_html__( 'Previous', 'nuria' ),
			'next_text'          => esc_html__( 'Next', 'nuria' ),
			'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'nuria' ) . ' </span>',
		) );
	}

}
endif;

if ( ! function_exists( 'nuria_excerpt' ) ) :
	/**
	 * Displays the optional excerpt.
	 *
	 * Wraps the excerpt in a div element.
	 *
	 * Create your own nuria_excerpt() function to override in a child theme.
	 *
	 * @since Nuria 1.0
	 *
	 * @param string $class Optional. Class string of the div element. Defaults to 'entry-summary'.
	 */
	function nuria_excerpt( $class = 'entry-summary' ) {
		$class = esc_attr( $class );

		if ( has_excerpt() || is_search() ) : ?>
			<div class="<?php echo esc_attr( $class ); ?>">
				<?php the_excerpt(); ?>
			</div>
		<?php endif;
	}
endif;

if ( ! function_exists( 'nuria_excerpt_more' ) && ! is_admin() ) :
/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ... and
 * a 'Continue reading' link.
 *
 * Create your own nuria_excerpt_more() function to override in a child theme.
 *
 * @since Nuria 1.0
 *
 * @return string 'Continue reading' link prepended with an ellipsis.
 */
function nuria_excerpt_more() {
	if ( is_admin() ) return;

	$link = sprintf( '<a href="%1$s" class="more-link">%2$s</a>',
		esc_url( get_permalink( get_the_ID() ) ),
		/* translators: %s: Name of current post */
		sprintf( wp_kses( __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'nuria' ), nuria_only_allow_span() ), get_the_title( get_the_ID() ) )
	);
	return ' &hellip; ' . $link;
}
add_filter( 'excerpt_more', 'nuria_excerpt_more' );
endif;

if ( ! function_exists( 'nuria_excerpt_length' ) && ! is_admin() ) :
/**
 * Change the excerpt number
 *
 * @since Nuria 1.0
 *
 * @return number Number for excerpt.
 */
function nuria_excerpt_length( $length ) {
	return 30;
}
add_filter( 'excerpt_length', 'nuria_excerpt_length', 999 );
endif;

if ( ! function_exists( 'nuria_categorized_blog' ) ) :
/**
 * Determines whether blog/site has more than one category.
 *
 * Create your own nuria_categorized_blog() function to override in a child theme.
 *
 * @since Nuria 1.0
 *
 * @return bool True if there is more than one category, false otherwise.
 */
function nuria_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'nuria_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'nuria_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so nuria_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so nuria_categorized_blog should return false.
		return false;
	}
}
endif;

/**
 * Flushes out the transients used in nuria_categorized_blog().
 *
 * @since Nuria 1.0
 */
function nuria_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'nuria_categories' );
}
add_action( 'edit_category', 'nuria_category_transient_flusher' );
add_action( 'save_post',     'nuria_category_transient_flusher' );

if ( ! function_exists( 'nuria_the_custom_logo' ) ) :
/**
 * Displays the optional custom logo.
 *
 * Does nothing if the custom logo is not available.
 *
 * @since Nuria 1.0
 */
function nuria_the_custom_logo() {
	if ( $secondary_logo = get_theme_mod( 'secondary_logo', false ) ) {
		?>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="secondary-logo"><img src="<?php echo esc_url( $secondary_logo); ?>" /></a>
		<?php
	}

	if ( function_exists( 'the_custom_logo' ) ) {
		the_custom_logo();
	}
}
endif;

/**
 * Print markup for SVG icon.
 *
 * @since Nuria 1.0
 * @param string $icon keyword for icon name
 */
function nuria_svg_icon ( $icon ) {
	$icon = esc_attr( $icon );
	$symbol = '<svg class="icon icon-' . $icon . '"><use xlink:href="' . get_template_directory_uri() . '/svg/symbol-defs.svg#icon-' . $icon . '"></use></svg>';

	return $symbol;
}

/**
 * Custom function to retrieve the archive title based on the queried object.
 *
 * @since Nuria 1.0
 *
 * @return string Archive title.
 */
function nuria_archive_title() {
	if ( is_category() ) {
		$title = sprintf(
			'<div class="archive-title-pre">%1$s</div><h1 class="page-title">%2$s</h1><div class="taxonomy-description">%3$s</div>',
			esc_html__( 'Category:', 'nuria' ),
			single_cat_title( '', false ),
			term_description()
		);
	} elseif ( is_tag() ) {
		$title = sprintf(
			'<div class="archive-title-pre">%1$s</div><h1 class="page-title">%2$s</h1><div class="taxonomy-description">%3$s</div>',
			esc_html__( 'Tagged As:', 'nuria' ),
			single_tag_title( '', false ),
			term_description()
		);
	} elseif ( is_author() ) {
		$title = sprintf(
			'<div class="author-avatar">%1$s</div><div class="archive-title-pre">%2$s</div><h1 class="page-title">%3$s</h1><div class="taxonomy-description">%4$s</div>',
			get_avatar( get_the_author_meta( 'user_email' ), 80 ),
			esc_html__( 'Author by:', 'nuria' ),
			get_the_author(),
			get_the_author_meta( 'description' )
		);
	} elseif ( is_year() ) {
		$title = sprintf(
			'<div class="archive-title-pre">%1$s</div><h1 class="page-title">%2$s</h1>',
			esc_html__( 'Posted in year:', 'nuria' ),
			get_the_date( _x( 'Y', 'yearly archives date format', 'nuria' ) )
		);
	} elseif ( is_month() ) {
		$title = sprintf(
			'<div class="archive-title-pre">%1$s</div><h1 class="page-title">%2$s</h1>',
			esc_html__( 'Posted in month:', 'nuria' ),
			get_the_date( _x( 'F Y', 'monthly archives date format', 'nuria' ) )
		);
	} elseif ( is_day() ) {
		$title = sprintf(
			'<div class="archive-title-pre">%1$s</div><h1 class="page-title">%2$s</h1>',
			esc_html__( 'Posted in:', 'nuria' ),
			get_the_date( _x( 'F j, Y', 'daily archives date format', 'nuria' ) )
		);
	} elseif ( is_tax( 'post_format' ) ) {
		if ( is_tax( 'post_format', 'post-format-aside' ) ) {
			$title = esc_html_x( 'Asides', 'post format archive title', 'nuria' );
		} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
			$title = esc_html_x( 'Galleries', 'post format archive title', 'nuria' );
		} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
			$title = esc_html_x( 'Images', 'post format archive title', 'nuria' );
		} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
			$title = esc_html_x( 'Videos', 'post format archive title', 'nuria' );
		} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
			$title = esc_html_x( 'Quotes', 'post format archive title', 'nuria' );
		} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
			$title = esc_html_x( 'Links', 'post format archive title', 'nuria' );
		} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
			$title = esc_html_x( 'Statuses', 'post format archive title', 'nuria' );
		} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
			$title = esc_html_x( 'Audio', 'post format archive title', 'nuria' );
		} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
			$title = esc_html_x( 'Chats', 'post format archive title', 'nuria' );
		}
	} elseif ( is_post_type_archive() ) {
		/* translators: %s taxonomy name */
		$title = sprintf( esc_html__( 'Archives: %s' , 'nuria'), post_type_archive_title( '', false ) );
	} elseif ( is_tax() ) {
		$tax = get_taxonomy( get_queried_object()->taxonomy );
		/* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
		$title = sprintf( esc_html__( '%1$s: %2$s', 'nuria' ), $tax->labels->singular_name, single_term_title( '', false ) );
	} else {
		$title = esc_html__( 'Archives', 'nuria' );
	}

	echo apply_filters( 'nuria_archive_title', $title );
}

/**
 * Breadcrumbs
 *
 * @since Nuria 1.0
 */
function nuria_breadcrumbs() { 
	if ( !is_front_page() ) {

		printf( 
			'<div class="site-breadcrumbs" ><span>%1$s</span><a href="%2$s">%3$s</a>',
			esc_html__('You are here:', 'nuria'),
			home_url(),
			esc_html__( 'Home', 'nuria' )
		);

		echo nuria_svg_icon('pointer');
	}

	if ( (is_category() || is_single()) && !is_attachment() ) {
		$category = get_the_category();
		if (count($category) > 0){
			$ID = $category[0]->cat_ID;
			if ( $ID ) {
				echo get_category_parents($ID, TRUE, ' ', FALSE );
				echo nuria_svg_icon('pointer');
			}
		}
	}

	if(is_single() || is_page()) {
		if ( !is_front_page() ){
			echo '<span>';
			the_title();
			echo '</span>';
		}
	}

	if (is_tag()){ echo '<span>' . esc_html__('Tag: ', 'nuria') . single_tag_title('',FALSE). '</span>'; }
	if (is_404()){ echo '<span>' . esc_html__('404 - Page not Found', 'nuria') . '</span>'; }
	if (is_search()){ echo '<span>' . esc_html__('Search', 'nuria'). '</span>'; }
	if (is_year()){ echo '<span>' . get_the_time('Y'). '</span>'; }
	if (is_month()){ echo '<span>' . get_the_time('F Y'). '</span>'; }
	if (is_author()){ echo '<span>' . esc_html__('Posts by ', 'nuria') . get_the_author(). '</span>'; }


	if ( !is_front_page() ) {
		echo "</div>";	
	}
}

/**
 * Render the footer credit, print from the footer_credit options, or default.
 *
 * @since Nuria 1.0
 *
 * @return string
 */
function nuria_footer_credit( $echo = false ) {

	if ( $footer_credit = get_theme_mod( 'footer_credit', false ) ) {
		$footer_credit = nuria_sanitize_footer_credit( $footer_credit );
	}else{ 

		$footer_credit = nuria_sanitize_footer_credit(
			sprintf(
				/* translators: 1. Site title, 2. WordPress dot org link, 3. Designer link */
				_x('%1$s Powered by %2$s Designed by %3$s.', '%1$s for homepage link, %2$s for wordpress.org link, %3$s for theme designer link', 'nuria'),
				'<a href="' . esc_url( home_url('/') ) .'" rel="home">' . get_bloginfo('name') . '</a>',
				'<a href="' . esc_url( __('https://wordpress.org/', 'nuria') ) .'">' . esc_html__('WordPress', 'nuria') . '</a>',				
				'<a href="' . esc_url( __('https://fancythemes.com/', 'nuria') ) .'">' . esc_html__('FancyThemes', 'nuria') . '</a>'
			)
		);
	}

	if ( $echo ) echo $footer_credit;
	return $footer_credit;
}
