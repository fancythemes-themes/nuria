<?php
/**
 * Customizer framework with live css applier
 *
 * Here is array example: 
	$settings[] = array(
		'id'				=> 'sidebar_widget_title_color', 
		'default'			=> $default_color_options['sidebar_widget_title_color'],
		'transport'			=> 'postMessage',
		'sanitize_callback'	=> 'sanitize_hex_color',
		'control'			=> 'color', 
		'label'				=> __( 'Widget Title Color', 'nuria' ),
		'section'			=> 'colors',
		'priority'			=> 3,
		'apply_css'			=> array (
									array(
										'selector' => '.widget-title',
										'property' => 'color',
									)
								),
	); 
 * Return this array in a function and add_filter to 'nuria_customizer_wrapper_settings' hook
 * Create customizer settings and controls based the arguments with filter 'nuria_customizer_wrapper_settings'
 */
function nuria_customizer_wrapper_settings( $wp_customize ) {

	do_action('nuria_before_customize_wrapper', $wp_customize );

	$settings = array();
	$customize_admin_js_var = array();

	$settings = apply_filters( 'nuria_customizer_wrapper_settings', $settings );
	$i = 1;
	foreach ( $settings as $setting ) {
		$wp_customize->add_setting( $setting[ 'id' ], array(
			'default' => empty( $setting[ 'default' ] ) ? null : $setting[ 'default' ],
			'transport' => empty( $setting[ 'transport' ] ) ? null : $setting[ 'transport' ],
			'capability' => empty( $setting[ 'capability' ] ) ? 'edit_theme_options' : $setting[ 'capability' ],
			'theme_supports' => empty( $setting[ 'theme_supports' ] ) ? null : $setting[ 'theme_supports' ],
			'sanitize_callback' => empty( $setting[ 'sanitize_callback' ] ) ? null : $setting[ 'sanitize_callback' ],
			'sanitize_js_callback' => empty( $setting[ 'sanitize_js_callback' ] ) ? null : $setting[ 'sanitize_js_callback' ],
			//'type' => empty( $setting[ 'type' ] ) ? null : $setting[ 'type' ],
		) );

		$setting['control_id'] = empty( $setting['control_id'] ) ? $setting['id'] : $setting['control_id'];

		if ( 'image' === $setting[ 'type' ] ) {
			$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $setting['control_id'],
				array(
					'label' => empty( $setting[ 'label' ] ) ? null : $setting[ 'label' ],
					'section' => empty( $setting[ 'section' ] ) ? null : $setting[ 'section' ],
					'settings' => $setting['id'],
					'priority' => empty( $setting[ 'priority' ] ) ? $i : $setting[ 'priority' ],
					'active_callback' => empty( $setting[ 'active_callback' ] ) ? null : $setting[ 'active_callback' ],
					'description' => empty( $setting[ 'description' ] ) ? null : $setting[ 'description' ],
				)
			) );
		} else if ( 'color' === $setting[ 'type' ] ) {
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting['control_id'],
				array(
					'label' => empty( $setting[ 'label' ] ) ? null : $setting[ 'label' ],
					'section' => empty( $setting[ 'section' ] ) ? null : $setting[ 'section' ],
					'settings' => $setting['id'],
					'priority' => empty( $setting[ 'priority' ] ) ? $i : $setting[ 'priority' ],
					'active_callback' => empty( $setting[ 'active_callback' ] ) ? null : $setting[ 'active_callback' ],
					'description' => empty( $setting[ 'description' ] ) ? null : $setting[ 'description' ],
				)
			) );
		} else {
			$wp_customize->add_control( $setting[ 'control_id' ], array(
				'settings' => $setting['id'],
				'label' => empty( $setting[ 'label' ] ) ? null : $setting[ 'label' ],
				'section' => empty( $setting[ 'section' ] ) ? null : $setting[ 'section' ],
				'type' => empty( $setting[ 'type' ] ) ? null : $setting[ 'type' ],
				'choices' => empty( $setting[ 'choices' ] ) ? null : $setting[ 'choices' ],
				'input_attrs' => empty( $setting[ 'input_attrs' ] ) ? null : $setting[ 'input_attrs' ],
				'priority' => empty( $setting[ 'priority' ] ) ? $i : $setting[ 'priority' ],
				'active_callback' => empty( $setting[ 'active_callback' ] ) ? null : $setting[ 'active_callback' ],
				'description' => empty( $setting[ 'description' ] ) ? null : $setting[ 'description' ],
			) );
		}

		if ( isset( $setting['apply_changes_to'] ) ) {
			$customize_admin_js_var['settings'][] = $setting['id'];
			$customize_admin_js_var['apply'][] = 
				array (
					'id' => $setting['id'],
					'apply_changes_to' => $setting['apply_changes_to'],
				);
		}

		$i++;
	}

	do_action('nuria_after_customize_wrapper', $wp_customize );
}
add_action( 'customize_register', 'nuria_customizer_wrapper_settings', 100, 1 );


/** 
 * Apply the CSS set by the customizer framework based on arguments 
 */
function nuria_apply_customizer_css(){

	$settings = array();
	$settings = apply_filters( 'nuria_apply_customizer_css', $settings );

	$css = ' ';
	$customize_js_var = array();
	$customize_admin_js_var = array();

	$selectors = array();
	$media_queries = array();

	foreach ( $settings as $setting ) {

		if ( isset( $setting['type'] ) && $setting['type'] == 'option' ){
			$value = get_option( $setting['id'] );
		}else{
			$value = get_theme_mod($setting['id']);
		}

		if ( !empty( $setting['apply_css'] ) && is_array($setting['apply_css']) ){

			foreach ( $setting['apply_css'] as $apply_css ){
				$mq = empty( $apply_css['media_query'] ) ? 'global' : $apply_css['media_query'];
				$selector = empty( $apply_css['selector'] ) ? '' : $apply_css['selector'];
				$property = empty( $apply_css['property'] ) ? '' : $apply_css['property'];
				$unit = empty( $apply_css['unit'] ) ? '' : $apply_css['unit'];
				$value_in_text = empty( $apply_css['value_in_text'] ) ? '' : $apply_css['value_in_text'];
				$color_alpha = empty( $apply_css['color_alpha'] ) ? '' : $apply_css['color_alpha'];


				if ( $value && ( $value !== $setting['default'] ) ){


					if ( !isset($media_queries[$mq][$selector]) ) {
						$media_queries[$mq][$selector] = '';
					}

					if ( !empty( $value_in_text ) ) {
						$media_queries[$mq][$selector] .= esc_attr( $property . ': ' . str_replace('%value%', esc_attr( $value ), $value_in_text) . ' ;' );
					} elseif ( !empty( $color_alpha ) ) {
						$media_queries[$mq][$selector] .= esc_attr( $property . ': ' . nuria_hex2rgba($value, $color_alpha, true ) . ' ;' );
					} else{
						$media_queries[$mq][$selector] .= esc_attr( $property . ': ' . $value . $unit . ' ;' );

					}

				}

				if ( isset($setting['transport']) && $setting['transport'] == 'postMessage'){
					$customize_js_var[] = 
						array ( 
							'id' => $setting['id'],
							'default' => isset($setting['default']) ? $setting['default'] : null,
							'selector' => $selector,
							'property' =>$property,
							'unit' => $unit,
							'value_in_text' => $value_in_text,
							'color_alpha' => $color_alpha,
							'mq' => $mq,
						);
				}

			}

		}
	}

	foreach ( $media_queries as $mq => $selectors ) {
		if ( $mq !== 'global' ) $css .= $mq . " {\n";
		foreach ( $selectors as $selector => $value ) {
			$css .= $selector . " { " . esc_attr( $value ) . "}\n";			
		}
		if ( $mq !== 'global' ) $css .= "}\n";
	}

	if ( is_customize_preview() ) {
		wp_enqueue_style( 'nuria-preview-style', get_template_directory_uri() . '/css/customize-preview.css', array( 'nuria-style' ), '20160816' );
		wp_add_inline_style( 'nuria-preview-style', $css );
	} else {
		wp_add_inline_style( 'nuria-style', $css );
	}
	wp_localize_script('nuria-customize-preview', '_customizerCSS', $customize_js_var );

}
add_action('wp_enqueue_scripts', 'nuria_apply_customizer_css', 11 );

/**
 * Converts a HEX value to RGB.
 *
 * @since Nuria 1.0
 *
 * @param string $color The original color, in 3- or 6-digit hexadecimal form.
 * @param number $alpha The opacity
 * @param boolean $as_css_value return as CSS value 
 * @return array Array containing RGB (red, green, and blue) values for the given
 *               HEX code, empty array otherwise.
 */
function nuria_hex2rgba( $color, $alpha = 1, $as_css_value = false ) {
	$color = trim( $color, '#' );

	if ( strlen( $color ) === 3 ) {
		$r = hexdec( substr( $color, 0, 1 ).substr( $color, 0, 1 ) );
		$g = hexdec( substr( $color, 1, 1 ).substr( $color, 1, 1 ) );
		$b = hexdec( substr( $color, 2, 1 ).substr( $color, 2, 1 ) );
	} else if ( strlen( $color ) === 6 ) {
		$r = hexdec( substr( $color, 0, 2 ) );
		$g = hexdec( substr( $color, 2, 2 ) );
		$b = hexdec( substr( $color, 4, 2 ) );
	} else {
		return array();
	}

	if ( $as_css_value )
		return "rgba( $r, $g, $b, $alpha )";  
	else
		return array( 'red' => $r, 'green' => $g, 'blue' => $b, 'alpha' => $alpha ); 
}


/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function nuria_customize_preview_js() {
	wp_enqueue_script( 'nuria-customize-preview', get_template_directory_uri() . '/js/customize-preview.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'nuria_customize_preview_js' );

/**
 * Binds JS handlers for the helper in the customizer admin.
 */
function nuria_customize_admin_js() {
	wp_enqueue_script( 'nuria-customizer-admin', get_template_directory_uri() . '/js/customize-control.js', array( ), '20141114', true );
}
add_action( 'customize_controls_enqueue_scripts', 'nuria_customize_admin_js' );