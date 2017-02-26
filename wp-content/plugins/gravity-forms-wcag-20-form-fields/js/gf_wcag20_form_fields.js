( function ( $ ) {
	'use strict';

	$( document ).bind( 'gform_post_render', function() {
		if ( gf_wcag20_form_fields_settings.failed_validation ) {
			window.location.hash = '#error';
			$( this ).find( '.validation_error' ).focus();
			$( this ).scrollTop( $( '.validation_error' ).offset().top );
		}
	});

	var new_window_text = gf_wcag20_form_fields_settings.new_window_text;
	$( function () {
		$( '.gform_body a' ).not( '.target-self, .gform_save_link' ).each( function() {
			//get the current title
			var title = $( this ).attr( 'title' );
			//if title doesnt exist or is empty, add line otherwise append it
			if ( title == undefined || title == '' ) {
				$( this ).attr( 'target', '_blank' ).attr( 'title', new_window_text );
			} else {
				$( this ).attr( 'target', '_blank' ).attr( 'title', title +  ' - ' + new_window_text );
			}
		});
	});
}( jQuery ) );