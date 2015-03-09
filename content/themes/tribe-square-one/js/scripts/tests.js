/**
 * @namespace modern_tribe.tests
 * @since 1.0
 * @desc modern_tribe.tests is where we store all other tests.
 */

t.tests = {

	has_bar: function () {
		return t.$el.body.is('.admin-bar');
	},

	has_touch: function () {
		return ('ontouchstart' in document.documentElement || navigator.msMaxTouchPoints > 0);
	}

};
