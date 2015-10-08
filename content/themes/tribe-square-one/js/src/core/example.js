'use strict';

// here is a bare bones example of a module hooking into state

import Events from '../utils/events';
import state from '../config/state';

// setup some vars, spin up events class

let events = new Events(),
	el = document.getElementsByTagName( 'body' ),
	$el = $( el );

// when not doing class based modules, make your init named and export that as default like yo

function example() {

	// bind events and kick other functions, store an el etc.

	if( $el.length ){

		bind_events();

		console.info( 'Initialized example module. Please remove.' );
	}

}

// here is how to use fat arrows if you need

let bind_events = () => {

	events.on( document, 'modern_tribe/resize_executed', resized );

};

// shorthand fatties

let resized = () => console.log( state.v_width );

// export the function that kicks off the module

export default example;
