'use strict';

import resize from './resize';
import plugins from './plugins';
import viewport_dims from './viewport-dims';
import Events from '../utils/events';

import example from './example';

let events = new Events();

function init() {

	// init external plugins

	plugins();

	// set initial states

	viewport_dims();

	// initialize global events

	bind_events();

	// initialize your modules here

	example();

	console.info( 'Initialized all javascript that targeted document ready.' );

}

function bind_events() {

	events.on( window, 'resize', _.debounce( resize, 200, false ) );

}

export default function ready() {

	events.ready( init() );

}

