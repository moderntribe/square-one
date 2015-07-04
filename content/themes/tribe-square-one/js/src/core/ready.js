'use strict';

import resize from './resize';
import Events from '../utils/events';

let events = new Events();

function init() {

	// initialize app here

	bind_events();

	console.info( 'Initialized all javascript on that targeted document ready.' );

}

function bind_events() {

	events.on( window, 'resize', _.debounce( resize, 200, false ) );

}

export default function ready() {

	events.ready( init() );

}

