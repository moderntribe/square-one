/**
 * @function modern_tribe.util.scroll_to
 * @since 1.0
 * @desc modern_tribe.util.scroll_to allows equalized or duration based scrolling of the body to a supplied target with options.
 */

t.util.scroll_to = function( opts ) {

	var options = $.extend( {
		auto           : false,
		auto_coefficent: 2.5,
		after_scroll   : function() {},
		duration       : 1000,
		easing         : 'linear',
		offset         : 0,
		target         : $()
	}, opts );

	if ( options.target.length ) {

		var position = options.target.offset().top + options.offset;

		if ( options.auto ) {

			var html_position = t.$el.html.scrollTop();

			if ( position > html_position ) {
				options.duration = (position - html_position) / options.auto_coefficent;
			}
			else {
				options.duration = (html_position - position) / options.auto_coefficent;
			}
		}

		t.$el.hb.animate( {scrollTop: position}, options.duration, options.easing, options.after_scroll );
	}

};