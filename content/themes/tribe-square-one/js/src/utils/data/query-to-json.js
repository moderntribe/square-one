'use strict';

export default function query_to_json() {

	var pairs = location.search.slice(1).split('&');

	var result = {};
	pairs.forEach(function(pair) {
		pair = pair.split('=');
		result[pair[0]] = decodeURIComponent(pair[1] || '');
	});

	return JSON.parse( JSON.stringify( result ) );
};

