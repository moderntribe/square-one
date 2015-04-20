/**
 * @function modern_tribe.core.execute_ready
 * @since 1.0
 * @desc modern_tribe.core.execute_ready brings together all functions that must execute on doc ready.
 */

t.core.execute_ready = function() {

	// @ifdef DEBUG
	console.time( 't.fn.execute_ready timer' );
	// @endif

	// core inits

	t.core.execute_tests();
	t.core.update_viewport_dims();
	t.core.initialize_plugins();
	t.core.responsive_modules_init();

	// module inits



	// resize and load event dispatcher

	t.$el.window
		.on( 'resize', _.debounce( t.core.execute_resize, 200, false ) )
		.on( 'load', t.core.execute_load );


	// @ifdef DEBUG
	console.timeEnd( 't.fn.execute_ready timer' );
	// @endif

};
