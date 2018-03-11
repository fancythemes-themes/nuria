<?php
/**
 * Template for displaying search forms in Nuria
 *
 * 
 * @package Nuria
 * @since Nuria 1.0
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php echo esc_html_x( 'Search for:', 'label', 'nuria' ); ?></span>
		<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Type your keyword here and hit enter', 'placeholder', 'nuria' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
	</label>
	<button type="submit" class="search-submit"><?php echo nuria_svg_icon('search'); ?><span class="screen-reader-text"><?php echo esc_html_x( 'Search', 'submit button', 'nuria' ); ?></span></button>
</form>
