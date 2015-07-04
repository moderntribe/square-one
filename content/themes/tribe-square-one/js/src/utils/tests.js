'use strict';

import Utils from './base';

export default class Tests extends Utils {

	constructor( options ) {
		super( options );
	}

	static is_json( str ){

		try {
			JSON.parse( str );
		} catch ( e ) {
			return false;
		}
		return true;

	}

	static can_local_store(){

		let mod, result;
		try {
			mod = new Date;
			localStorage.setItem( mod, mod.toString() );
			result = localStorage.getItem( mod ) === mod.toString();
			localStorage.removeItem( mod );
			return result;
		} catch ( _error ) {
			console.error( 'This browser doesn\'t support local storage or is not allowing writing to it.' );
		}

	}

	static is_external_link( url ){

		let match = url.match( /^([^:\/?#]+:)?(?:\/\/([^\/?#]*))?([^?#]+)?(\?[^#]*)?(#.*)?/ );
		if ( typeof match[1] === "string" && match[1].length > 0 && match[1].toLowerCase() !== location.protocol ) {
			return true;
		}
		if ( typeof match[2] === "string" && match[2].length > 0 && match[2].replace( new RegExp( ":(" + {
					"http:" : 80,
					"https:": 443
				}[location.protocol] + ")?$" ), "" ) !== location.host ) {
			return true;
		}
		return false;

	}

}