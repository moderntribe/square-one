'use strict';

import events from '../utils/events';

export default function resize() {

	// code for resize events can go here

	events.trigger( {event:'modern_tribe/resize_executed', native:false} );

}