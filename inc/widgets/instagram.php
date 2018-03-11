<?php
/**
 * Widget API: Nuria_Widget_Instagram class
 *
 * @package Nuria
 * @since 1.0.0
 */

/**
 * Core class used to implement a Instagram widget.
 *
 * @since 1.0.0
 *
 * @see WP_Widget
 */
class Nuria_Widget_Instagram extends WP_Widget {
	/** constructor */
	public function __construct() {
		$widget_ops = array('classname' => 'widget-instagram', 'description' => esc_html__( 'Instagram Feed.', 'nuria') );
		parent::__construct('nuria-instagram', esc_html__('Nuria - Instagram', 'nuria'), $widget_ops);
		$this->alt_option_name = 'widget_instagram';
	}
	
	function widget($args, $instance) {		
	extract( $args );
		$default = array ( 
			'widget_title' => esc_html__( 'Instagram', 'nuria' ),
			'username' => '',
			'qty' => 9
		);

		$instance = wp_parse_args($instance, $default);			
		$widget_title = apply_filters('widget_title', $instance['widget_title']);
		$username = $instance['username'];
		$qty = $instance['qty'];
		// WIDGET OUTPUT
		echo $before_widget;
		if(!empty($widget_title)){ echo $before_title . wp_kses_post( $widget_title ) . $after_title; }

		if ( $username != '' ) {

			$media_array = $this->scrape_instagram( $username );

			if ( is_wp_error( $media_array ) ) {

				echo wp_kses_post( $media_array->get_error_message() );

			} else {

				// slice list down to required limit
				$media_array = array_slice( $media_array, 0, $qty );

				?><ul class="image-list clear"><?php
				foreach ( $media_array as $item ) {
					echo '<li><a href="'. esc_url( $item['link'] ) .'" target="blank"><img src="'. esc_url( $item['small'] ) .'"  alt="'. esc_attr( $item['description'] ) .'" title="'. esc_attr( $item['description'] ).'" /></a></li>';
				}
				?></ul>
				<div class="instagram-account">
					<a  rel="nofollow" href="<?php echo esc_url('http://www.instagram.com/'.  $username ) ?>/"><?php esc_html_e('Follow Me', 'nuria'); ?></a>
				</div>
				<?php
			}
		}

		echo $after_widget;		
	}

	function update($new_instance, $old_instance) {				
		$instance = $old_instance;
		$instance['widget_title'] = sanitize_text_field( $new_instance['widget_title'] );
		$instance['username'] = sanitize_text_field( $new_instance['username'] );
		$instance['qty'] = absint($new_instance['qty']);

		return $instance;
	}

	function form($instance) {	
		$default = array ( 
			'widget_title' => esc_html__( 'Instagram', 'nuria' ),
			'username'     => '',
			'qty'          => 9
		);
		$instance = wp_parse_args($instance, $default);			
		$widget_title = $instance['widget_title'];
		$username = $instance['username'];
		$qty = absint($instance['qty']);
	?>
		<p>
			<?php esc_html_e( 'Widget title:', 'nuria'); ?>
			<input class="widefat" type="text" name="<?php echo $this->get_field_name('widget_title'); ?>" value="<?php echo esc_attr($widget_title); ?>" />
		</p>
		<p>
			<?php esc_html_e( 'Username:', 'nuria'); ?>
			<input class="widefat" type="text" name="<?php echo $this->get_field_name('username'); ?>" value="<?php echo esc_attr($username); ?>" />
		</p>
		<p>
			<?php esc_html_e( 'Number of Photos:', 'nuria' ); ?>
			<input class="tiny-text" type="number" step="1" min="3" name="<?php echo $this->get_field_name('qty'); ?>" value="<?php echo esc_attr($qty); ?>" />
		</p>

	<?php
	}

	// based on https://gist.github.com/cosmocatalano/4544576
	function scrape_instagram( $username ) {

		$username = strtolower( $username );
		$username = str_replace( '@', '', $username );

		if ( false === ( $instagram = get_transient( 'nuria-instagram-'.sanitize_title_with_dashes( $username ) ) ) ) {

			$remote = wp_remote_get( 'http://instagram.com/'.trim( $username ) );

			if ( is_wp_error( $remote ) )
				return new WP_Error( 'site_down', esc_html__( 'Unable to communicate with Instagram.', 'nuria' ) );

			if ( 200 != wp_remote_retrieve_response_code( $remote ) )
				return new WP_Error( 'invalid_response', esc_html__( 'Instagram did not return a 200.', 'nuria' ) );

			$shards = explode( 'window._sharedData = ', $remote['body'] );
			$insta_json = explode( ';</script>', $shards[1] );
			$insta_array = json_decode( $insta_json[0], TRUE );

			if ( ! $insta_array )
				return new WP_Error( 'bad_json', esc_html__( 'Instagram has returned invalid data.', 'nuria' ) );

			if ( isset( $insta_array['entry_data']['ProfilePage'][0]['user']['media']['nodes'] ) ) {
				$images = $insta_array['entry_data']['ProfilePage'][0]['user']['media']['nodes'];
			} else {
				return new WP_Error( 'bad_json_2', esc_html__( 'Instagram has returned invalid data.', 'nuria' ) );
			}

			if ( ! is_array( $images ) )
				return new WP_Error( 'bad_array', esc_html__( 'Instagram has returned invalid data.', 'nuria' ) );

			$instagram = array();

			foreach ( $images as $image ) {

				$image['thumbnail_src'] = preg_replace( '/^https?\:/i', '', $image['thumbnail_src'] );
				$image['display_src'] = preg_replace( '/^https?\:/i', '', $image['display_src'] );

				// handle both types of CDN url
				if ( ( strpos( $image['thumbnail_src'], 's640x640' ) !== false ) ) {
					$image['thumbnail'] = str_replace( 's640x640', 's160x160', $image['thumbnail_src'] );
					$image['small'] = str_replace( 's640x640', 's320x320', $image['thumbnail_src'] );
				} else {
					$urlparts = wp_parse_url( $image['thumbnail_src'] );
					$pathparts = explode( '/', $urlparts['path'] );
					array_splice( $pathparts, 3, 0, array( 's160x160' ) );
					$image['thumbnail'] = '//' . $urlparts['host'] . implode( '/', $pathparts );
					$pathparts[3] = 's320x320';
					$image['small'] = '//' . $urlparts['host'] . implode( '/', $pathparts );
				}

				$image['large'] = $image['thumbnail_src'];

				if ( $image['is_video'] == true ) {
					$type = 'video';
				} else {
					$type = 'image';
				}

				$caption = esc_html__( 'Instagram Image', 'nuria' );
				if ( ! empty( $image['caption'] ) ) {
					$caption = $image['caption'];
				}

				$instagram[] = array(
					'description'   => $caption,
					'link'		  	=> trailingslashit( '//instagram.com/p/' . $image['code'] ),
					'time'		  	=> $image['date'],
					'comments'	  	=> $image['comments']['count'],
					'likes'		 	=> $image['likes']['count'],
					'thumbnail'	 	=> $image['thumbnail'],
					'small'			=> $image['small'],
					'large'			=> $image['large'],
					'original'		=> $image['display_src'],
					'type'		  	=> $type
				);
			}

			// do not set an empty transient - should help catch private or empty accounts
			if ( ! empty( $instagram ) ) {
				$instagram = serialize( $instagram ) ;
				set_transient( 'nuria-instagram-'.sanitize_title_with_dashes( $username ), $instagram, 120 );
			}
		}

		if ( ! empty( $instagram ) ) {

			return unserialize( $instagram ) ;

		} else {

			return new WP_Error( 'no_images', esc_html__( 'Instagram did not return any images.', 'nuria' ) );

		}
	}

	function images_only( $media_item ) {

		if ( $media_item['type'] == 'image' )
			return true;

		return false;
	}

	
}

function nuria_register_widget_instagram() {
	register_widget("Nuria_Widget_Instagram");
}
add_action('widgets_init', 'nuria_register_widget_instagram');