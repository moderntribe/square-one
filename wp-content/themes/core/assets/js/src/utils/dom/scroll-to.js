/**
 * @function scrollTo
 * @since 1.0
 * @desc scrollTo allows equalized or duration based scrolling of the body to a supplied $target with options.
 */

const scrollTo = ( opts ) => {
	const options = Object.assign( {
		auto: false,
		autoCoefficient: 2.5,
		afterScroll() {
		},

		duration: 1000,
		easing: 'linear',
		offset: 0,
		$target: $(),
		$scrollContainer: $( 'html' ),
	}, opts );
	let position;
	let htmlPosition;

	if ( options.$target.length ) {
		position = options.$target.offset().top + options.offset;

		if ( options.auto ) {
			htmlPosition = $( 'html' ).scrollTop();

			if ( position > htmlPosition ) {
				options.duration = ( position - htmlPosition ) / options.autoCoefficient;
			} else {
				options.duration = ( htmlPosition - position ) / options.autoCoefficient;
			}
		}

		options.$scrollContainer.animate( { scrollTop: position }, options.duration, options.easing, options.afterScroll );
	}
};

export default scrollTo;
