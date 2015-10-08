'use strict';

import Fastclick from 'fastclick';

export default function plugins() {

	// initialize global external plugins here

	Fastclick.attach( document.body );

}