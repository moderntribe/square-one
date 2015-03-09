/**
 * @function modern_tribe.core.execute_resize
 * @since 1.0
 * @desc modern_tribe.core.execute_resize brings together all functions that must execute on the end of browser resize.
 */

t.core.execute_resize = function() {

	// @ifdef DEBUG
	console.time( 't.fn.execute_resize timer' );
	// @endif

	if ( !t.br.legacy ) {

		t.core.update_viewport_dims();
		t.core.responsive_modules_init();

	}

	t.$el.doc.trigger( 'modern_tribe_resize_executed' );

	// @ifdef DEBUG
	console.timeEnd( 't.fn.execute_resize timer' );
	// @endif

};