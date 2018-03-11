<?php
/**
 * The template part for displaying content
 *
 * 
 * @package Nuria
 * @since Nuria 1.0
 */
?>
<?php
$post_attr_id = is_main_query() ? 'post-' . get_the_ID() : '';
?>
<article id="<?php echo $post_attr_id ?>" <?php post_class( ); ?>>

	<?php if ( has_post_thumbnail() ) : ?>
		<a href="<?php the_permalink(); ?>" class="post-thumbnail"><?php the_post_thumbnail('large'); ?></a>
	<?php endif; ?>

	<header class="entry-header">
		<?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
			<span class="sticky-post"><?php _e( 'Featured', 'nuria' ); ?></span>
		<?php endif; ?>

		<div class="entry-meta">
			<?php nuria_entry_meta(); ?>
		</div><!-- .entry-meta -->

		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
	</header><!-- .entry-header -->

	<?php //nuria_excerpt(); ?>

	<div class="entry-content">
		<?php
			/* translators: %s: Name of current post */
			the_excerpt();
		?>
	</div><!-- .entry-content -->

</article><!-- #post-## -->
