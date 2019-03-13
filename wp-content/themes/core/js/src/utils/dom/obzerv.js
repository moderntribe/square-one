/**
 * A convenient wrapper around IntersectionObserver for tracking element position relative to the viewport.
 *
 * Docs: https://github.com/callmecavs/obzerv#obzerv
 *
 * @returns {{create: function(*=)}}
 */

const obzerv = () => {
	// feature detection
	if ( ! ( 'IntersectionObserver' in window ) ) {
		console.warn( 'IntersectionObserver is not supported, see: http://caniuse.com/#search=IntersectionObserver' );
	}

	const create = ( options ) => {
		// exit early if no callback
		if ( ! options.callback ) {
			return {};
		}

		// define change handler
		const change = ( entries, observer ) => {
			// for each change
			entries.forEach( ( entry ) => {
				// define untrack helper
				const untrack = () => observer.unobserve( entry.target );

				// pass params to provided callback function
				options.callback(
					entry.target, // current node
					entry.isIntersecting, // boolean indicating inview status
					untrack, // function to unobserve the current node
				);
			} );
		};

		// create observer instance
		const observer = new window.IntersectionObserver( change, {
			root: null, // relative to the viewport
			rootMargin: `-${ options.offset || 0 }%`, // apply offsets as percentage
			threshold: 0.01, // any amount visible
		} );

		// return a method that adds a node to the observer
		return {
			track: node => observer.observe( node ),
		};
	};

	return { create };
};

const singleton = obzerv();

export default singleton;
