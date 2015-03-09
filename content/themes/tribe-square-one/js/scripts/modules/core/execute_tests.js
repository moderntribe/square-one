/**
 * @function modern_tribe.core.execute_tests
 * @since 1.0
 * @desc modern_tribe.core.execute_tests brings together all tests that need to be run on init.
 */

t.core.execute_tests = function() {

	// Touch conditional
	if ( t.tests.has_touch() ) {
		t.$el.body.addClass( 'is-touch-device' );
	}

	// Legacy conditional
	if ( t.$el.html.is( '.lt-ie9' ) ) {
		t.br.legacy = true;
	}

};
