'use strict';

const query_to_json = ( params = '' ) => {

	var pairs = params.length ? params.split( '&' ) : location.search.slice( 1 ).split( '&' );

	var result = {};
	pairs.forEach( function( pair ) {
		pair = pair.split( '=' );
		result[ pair[ 0 ] ] = decodeURIComponent( pair[ 1 ] || '' );
	} );

	return JSON.parse( JSON.stringify( result ) );
};

export default query_to_json;