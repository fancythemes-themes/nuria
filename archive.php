<?php
/**
 * The template for displaying archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each one. For example, tag.php (Tag archives),
 * category.php (Category archives), author.php (Author archives), etc.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * 
 * @package Nuria
 * @since Nuria 1.0
 */

get_header(); ?>

	<?php nuria_breadcrumbs(); ?>

	<?php $list_class = get_theme_mod( 'archive_blog_list_view', 'list') . '-view'; ?>
	<div id="primary" class="content-area <?php echo esc_attr($list_class); ?>">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<?php
				nuria_archive_title();
				?>
			</header><!-- .page-header -->

			<?php
			// Start the Loop.
			while ( have_posts() ) : the_post();

				if ( 'classic' == get_theme_mod( 'archive_blog_list_view', 'list') )
					get_template_part( 'template-parts/content', get_post_format() );
				else
					get_template_part( 'template-parts/content-list', get_post_format() );

			// End the loop.
			endwhile;

			nuria_posts_pagination();

		// If no content, include the "No posts found" template.
		else :
			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
