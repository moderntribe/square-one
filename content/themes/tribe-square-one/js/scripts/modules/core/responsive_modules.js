/**
 * @function modern_tribe.core.responsive_modules_init
 * @since 1.0
 * @desc modern_tribe.core.responsive_modules_init fires out inits that shouldn't execute until a mobile or desktop state has been detected.
 */

t.core.responsive_modules_init = function() {

	if ( !t.state.desktop_initialized && t.state.v_width >= t.options.mobile_breakpoint ) {

		t.state.desktop_initialized = true;

		t.$el.doc.trigger( 'modern_tribe_desktop_init' );

		// @ifdef DEBUG
		console.info( 'Completed initializing desktop plugins.' );
		// @endif

	}
	else if ( !t.state.mobile_initialized && t.state.v_width < t.options.mobile_breakpoint ) {


		t.state.mobile_initialized = true;

		t.$el.doc.trigger( 'modern_tribe_mobile_init' );

		// @ifdef DEBUG
		console.info( 'Completed initializing mobile plugins.' );
		// @endif

	}

};
