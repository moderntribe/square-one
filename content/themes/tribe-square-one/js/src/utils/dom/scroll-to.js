'use strict';

/**
 * @function scroll_to
 * @since 1.0
 * @desc scroll_to allows equalized or duration based scrolling of the body to a supplied target with options.
 */

export default function scroll_to( opts ) {

	let options = $.extend( {
		auto           : false,
		auto_coefficent: 2.5,
		after_scroll   () {},
		duration       : 1000,
		easing         : 'linear',
		offset         : 0,
		target         : $()
	}, opts );

	if ( options.target.length ) {

		var position = options.target.offset().top + options.offset;

		if ( options.auto ) {

			var html_position =  $( 'html' ).scrollTop();

			if ( position > html_position ) {
				options.duration = (position - html_position) / options.auto_coefficent;
			}
			else {
				options.duration = (html_position - position) / options.auto_coefficent;
			}
		}

		$( 'html, body' ).animate( {scrollTop: position}, options.duration, options.easing, options.after_scroll );
	}

};