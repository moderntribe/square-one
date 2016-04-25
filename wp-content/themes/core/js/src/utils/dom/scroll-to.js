
/**
 * @function scrollTo
 * @since 1.0
 * @desc scrollTo allows equalized or duration based scrolling of the body to a supplied $target with options.
 */

const scrollTo = (opts) => {

	let options = $.extend({
		auto: false,
		auto_coefficent: 2.5,
		afterScroll() {
		},

		duration: 1000,
		easing: 'linear',
		offset: 0,
		$target: $(),
	}, opts);

	if (options.$target.length) {

		var position = options.$target.offset().top + options.offset;

		if (options.auto) {

			var htmlPosition = $('html').scrollTop();

			if (position > htmlPosition) {
				options.duration = (position - htmlPosition) / options.auto_coefficent;
			} else {
				options.duration = (htmlPosition - position) / options.auto_coefficent;
			}
		}

		$('html, body').animate({ scrollTop: position }, options.duration, options.easing, options.after_scroll);
	}

};

export default scrollTo;
