'use strict';

import resize from './resize';
import addEventListener from '../utils/add-event-listener';

export default function ready() {

	addEventListener( window, 'resize', _.debounce( resize, 200, false ) );

}