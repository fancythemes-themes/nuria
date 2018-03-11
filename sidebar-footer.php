<?php
/**
 * The template for the content bottom widget areas on posts and pages
 *
 * 
 * @package Nuria
 * @since Nuria 1.0
 */

if ( ! is_active_sidebar( 'footer-widget-1' ) && ! is_active_sidebar( 'footer-widget-2' ) && ! is_active_sidebar( 'footer-widget-3' ) && ! is_active_sidebar( 'footer-widget-4' ) && ! is_active_sidebar( 'footer-widget-full-width' ) ) {
	return;
}

// If we get this far, we have widgets. Let's do this.
?>
<div id="footer-widgets-container" class="footer-widgets-container" role="complementary">
	<?php if ( is_active_sidebar( 'footer-widget-full-width' ) ) : ?>
		<div class="widget-area-full">
			<?php dynamic_sidebar( 'footer-widget-full-width' ); ?>
		</div><!-- .widget-area -->
	<?php endif; ?>
	<?php if ( is_active_sidebar( 'footer-widget-1' ) ) : ?>
		<div class="widget-area footer-widget-area-1">
			<?php dynamic_sidebar( 'footer-widget-1' ); ?>
		</div><!-- .widget-area -->
	<?php endif; ?>
	<?php if ( is_active_sidebar( 'footer-widget-2' ) ) : ?>
		<div class="widget-area footer-widget-area-2">
			<?php dynamic_sidebar( 'footer-widget-2' ); ?>
		</div><!-- .widget-area -->
	<?php endif; ?>
	<?php if ( is_active_sidebar( 'footer-widget-3' ) ) : ?>
		<div class="widget-area footer-widget-area-3">
			<?php dynamic_sidebar( 'footer-widget-3' ); ?>
		</div><!-- .widget-area -->
	<?php endif; ?>
	<?php if ( is_active_sidebar( 'footer-widget-4' ) ) : ?>
		<div class="widget-area footer-widget-area-4">
			<?php dynamic_sidebar( 'footer-widget-4' ); ?>
		</div><!-- .widget-area -->
	<?php endif; ?>	
</div><!-- .content-bottom-widgets -->
