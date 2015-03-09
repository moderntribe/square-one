/**
 * @namespace modern_tribe.el
 * @desc modern_tribe.el is where we cache all of our needed DOM elements as jQuery objects.
 */

t.$el = {
	body     : $( 'body' ),
	doc      : $( document ),
	hb       : $( 'html, body' ),
	html     : $( 'html' ),
	site_wrap: $( '#site-wrap' ),
	window   : $( window )
};

