/**
 * @function modern_tribe.bind_events
 * @desc modern_tribe.bind_events is where we bind event handlers that are global to the functioning of the site.
 * Handlers specific to modules should be contained within their respective event handler functions.
 */


t.bind_events = function() {

	/***
	 * @handler Window Events
	 * @desc These handlers listen on the window object for resize/load etc events.
	 */

	t.$el.window
		.on( 'resize', _.debounce( t.core.execute_resize, 200, false ) )
		.on( 'load', t.core.execute_load );

};