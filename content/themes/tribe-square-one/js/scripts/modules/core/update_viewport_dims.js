/**
 * @function modern_tribe.core.update_viewport_dims
 * @since 1.0
 * @desc modern_tribe.core.update_viewport_dims updates the state object viewport dimension variables for use throughout the app.
 */

t.core.update_viewport_dims = function() {

	t.state.v_height = $.viewportH();
	t.state.v_width = $.viewportW();

	if ( t.state.v_width >= t.options.mobile_breakpoint ) {
		t.state.is_desktop = true;
		t.state.is_mobile = false;
	}
	else {
		t.state.is_desktop = false;
		t.state.is_mobile = true;
	}

};
