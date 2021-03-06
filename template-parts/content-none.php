<?php
/**
 * The template part for displaying a message that posts cannot be found
 *
 * 
 * @package Nuria
 * @since Nuria 1.0
 */
?>

<article class="no-results not-found">
	<header class="page-header-no-result">
		<h1 class="page-title"><?php esc_html_e( 'Nothing Found', 'nuria' ); ?></h1>
	</header><!-- .page-header -->

	<div class="page-content">
		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

			<p>
				<?php printf(
						/* translators: 1 is link to post editor */ 
						wp_kses( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'nuria' ), array( 'a' => array ('href') => array() ) , esc_url( admin_url( 'post-new.php' ) ) )
					);
				?>
			</p>

		<?php elseif ( is_search() ) : ?>

			<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'nuria' ); ?></p>
			<?php get_search_form(); ?>

		<?php else : ?>

			<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'nuria' ); ?></p>
			<?php get_search_form(); ?>

		<?php endif; ?>
	</div><!-- .page-content -->
</article><!-- .no-results -->
