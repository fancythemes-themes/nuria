<?php
/**
 * The template part for displaying content
 *
 * 
 * @package Nuria
 * @since Nuria 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( ); ?>>

	<header class="entry-header">
		<?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
			<span class="sticky-post"><?php esc_html_e( 'Featured', 'nuria' ); ?></span>
		<?php endif; ?>

		<div class="entry-meta">
			<?php nuria_entry_meta(); ?>
		</div><!-- .entry-meta -->

		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
	</header><!-- .entry-header -->

	<?php //nuria_excerpt(); ?>

	<?php nuria_post_thumbnail(); ?>

	<div class="entry-content">
		<?php
			/* translators: %s: Name of current post */
			the_content( sprintf(
				wp_kses(__( '%1$s<span class="screen-reader-text"> "%2$s"</span>', 'nuria' ), nuria_only_allow_span() ),
				esc_html__('Read More', 'nuria') . nuria_svg_icon('arrow-right'),
				get_the_title()
			) );

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

</article><!-- #post-## -->
