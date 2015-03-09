/**
 * @function modern_tribe.core.execute_load
 * @since 1.0
 * @desc modern_tribe.core.execute_load brings together all functions that must execute on window load.
 */

t.core.execute_load = function() {

	// @ifdef DEBUG
	console.time( 't.fn.execute_load timer' );
	// @endif

	t.core.update_viewport_dims();

	// @ifdef DEBUG
	console.timeEnd( 't.fn.execute_load timer' );
	// @endif

};