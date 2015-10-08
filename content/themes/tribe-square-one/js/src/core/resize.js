'use strict';

import events from '../utils/events';
import viewport_dims from './viewport-dims';

export default function resize() {

	// code for resize events can go here

	viewport_dims();

	events.trigger( {event:'modern_tribe/resize_executed', native:false} );

}