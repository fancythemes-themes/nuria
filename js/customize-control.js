/* global colorScheme, Color */
/**
 * Add a listener to the Color Scheme control to update other color controls to new values/defaults.
 * Also trigger an update of the Color Scheme CSS when a color is changed.
 */

( function( api ) {

	api.controlConstructor.select = api.Control.extend( {
		ready: function() {
			if ( 'color_scheme' === this.id ) {
				this.setting.bind( 'change', function( value ) {
					_.each( JSON.parse( value ), function(color,key){
						api( key ).set( color );
						api.control( key ).container.find( '.color-picker-hex' )
							.data( 'data-default-color', color )
							.wpColorPicker( 'defaultColor', color );
					});
				} );
			}
		}
	} );

	api.bind( 'ready', function() {
		var colorScheme;
		_.each( ['color_scheme'], function( setting ) {
			colorScheme = api( setting ).get();
			if ( colorScheme == 'default' ) return;
			_.each( JSON.parse( colorScheme ), function(color,key){
				api.control( key ).container.find( '.color-picker-hex' )
					.data( 'default-color', color )
					.wpColorPicker( 'defaultColor', color );
			});
		} );	
	} );

} )( wp.customize );
