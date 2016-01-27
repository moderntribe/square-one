/**
 * @module
 * @exports resize
 * @description Kicks in any third party plugins that operate on a sitewide basis.
 */

'use strict';

import { trigger } from '../utils/events';
import viewport_dims from './viewport-dims';

let resize = () => {

	// code for resize events can go here

	viewport_dims();

	trigger( {event:'modern_tribe/resize_executed', native:false} );

};

export default resize;