<?php
/**
 * Sanitize Callback Functions
 *
 * 
 * @package Nuria
 * @since Nuria 1.0
 */

/**
 * Only allow value for layout.
 *
 * @since Nuria 1.0
 *
 * @return HTML string sanitized with wpkses.
 */
function nuria_sanitize_layout($value) {
	if ( ! in_array( $value, array('full-width', 'two-column', 'three-column') ) ) {
		$value = 'two-column';
	}
	return $value;
}

/**
 * Only allow value with hex format.
 *
 * @since Nuria 1.0
 *
 * @return hex color code.
 */
function nuria_sanitize_color_field($value) {
	return $value;
}

/**
 * Only allow value as list or classic for List View.
 *
 * @since Nuria 1.0
 *
 * @return HTML string sanitized with wpkses.
 */
function nuria_sanitize_blog_list_view($value) {
	if ( ! in_array( $value, array('classic', 'list', 'grid') ) ) {
		$value = 'classic';
	}
	return $value;
}

/**
 * Allowed html tags for footer credit.
 *
 * @since Nuria 1.0
 *
 * @return HTML string sanitized with wpkses.
 */
function nuria_sanitize_footer_credit($value) {
	$allowed_tags = array(
						'a' => array(
							'href' => array(),
							'title' => array(),
							'rel' => array(),
						),
						'br' => array(),
						'em' => array(),
						'strong' => array(),
						);
	return wp_kses( $value, $allowed_tags );
}

/**
 * Checkbox sanitization callback.
 * 
 * Sanitization callback for 'checkbox' type controls. This callback sanitizes `$checked`
 * as a boolean value, either TRUE or FALSE.
 *
 * @since Nuria 1.0
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool Whether the checkbox is checked.
 */
function nuria_sanitize_checkbox( $checked ) {
	// Boolean check.
	return ( ( isset( $checked ) && true == $checked ) ? true : false );
}

/**
 * Number sanitization callback.
 *
 * - Sanitization: number_absint
 * - Control: number
 * 
 * Sanitization callback for 'number' type text inputs. This callback sanitizes `$number`
 * as an absolute integer (whole number, zero or greater).
 * 
 * NOTE: absint() can be passed directly as `$wp_customize->add_setting()` 'sanitize_callback'.
 * It is wrapped in a callback here merely for example purposes.
 * 
 * @see absint() https://developer.wordpress.org/reference/functions/absint/
 *
 * @param int                  $number  Number to sanitize.
 * @param WP_Customize_Setting $setting Setting instance.
 * @return int Sanitized number; otherwise, the setting default.
 */
function nuria_sanitize_number_absint( $number, $setting ) {
	// Ensure $number is an absolute integer (whole number, zero or greater).
	$number = absint( $number );
	
	// If the input is an absolute integer, return it; otherwise, return the default
	return ( $number ? $number : $setting->default );
}

/**
 * Image sanitization callback.
 *
 * Checks the image's file extension and mime type against a whitelist. If they're allowed,
 * send back the filename, otherwise, return the setting default.
 *
 * - Sanitization: image file extension
 * - Control: text, WP_Customize_Image_Control
 * 
 * @see wp_check_filetype() https://developer.wordpress.org/reference/functions/wp_check_filetype/
 *
 * @param string               $image   Image filename.
 * @param WP_Customize_Setting $setting Setting instance.
 * @return string The image filename if the extension is allowed; otherwise, the setting default.
 */
function nuria_sanitize_image( $image, $setting ) {
	/*
	 * Array of valid image file types.
	 *
	 * The array includes image mime types that are included in wp_get_mime_types()
	 */
	$mimes = array(
		'jpg|jpeg|jpe' => 'image/jpeg',
		'gif'          => 'image/gif',
		'png'          => 'image/png',
		'bmp'          => 'image/bmp',
		'tif|tiff'     => 'image/tiff',
		'ico'          => 'image/x-icon'
	);
	// Return an array with file extension and mime_type.
    $file = wp_check_filetype( $image, $mimes );
	// If $image has a valid mime_type, return it; otherwise, return the default.
    return ( $file['ext'] ? $image : $setting->default );
}
