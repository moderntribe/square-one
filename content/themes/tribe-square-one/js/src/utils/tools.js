'use strict';

import Utils from './base';

export default class Tools extends Utils {
	constructor( options ) {
		super( options );
	}

	add_class( el, class_name ) {
		if ( el.classList ) {
			el.classList.add( class_name );
		}
		else {
			el.className += ' ' + class_name;
		}
		return el;
	}

	get_children( el ) {
		let children = [];
		for ( var i = el.children.length; i--; ) {
			if ( el.children[i].nodeType != 8 ) {
				children.unshift( el.children[i] );
			}
		}
		return children;
	}

}
