<?php
/**
 * The template part for displaying single posts
 *
 * 
 * @package Nuria
 * @since Nuria 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<div class="entry-meta">
			<?php nuria_entry_meta(); ?>
		</div><!-- .entry-footer -->

		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<?php //nuria_excerpt(); ?>

	<?php
	if ( ! in_array( get_post_format(), array( 'video', 'audio', 'gallery') ) ) { 
		nuria_post_thumbnail();
	}
	?>

	<div class="entry-content">
		<?php
			the_content();

			nuria_entry_taxonomies();

			if ( function_exists( 'sharing_display' ) ) {
				sharing_display( '', true );
			}

			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'nuria' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'nuria' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );

		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php

		if ( class_exists( 'Jetpack_RelatedPosts' ) ) {
			echo do_shortcode( '[jetpack-related-posts]' );
		}

		if ( '' !== get_the_author_meta( 'description' ) ) {
			get_template_part( 'template-parts/biography' );
		}
		?>
	</footer>

</article><!-- #post-## -->
