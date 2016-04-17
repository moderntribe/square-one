/**
 * @function is_external_link
 * @desc test if a url points to the website domain.
 */

let is_external_link = ( url ) => {
	var match = url.match( /^([^:\/?#]+:)?(?:\/\/([^\/?#]*))?([^?#]+)?(\?[^#]*)?(#.*)?/ );
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
};

export default is_external_link;