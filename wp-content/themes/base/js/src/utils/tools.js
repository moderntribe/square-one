/**
 * @module
 * @description Some vanilla js cross browser utils
 */

'use strict';

let add_class = ( el, class_name ) => {
	if( ! el ){
		console.log( `Cant apply class ${class_name} on null element.` );
		return null;
	}
	if ( el.classList ) {
		el.classList.add( class_name );
	}
	else {
		el.className += ' ' + class_name;
	}
	return el;
};

let get_children = ( el ) => {
	let children = [];
	for ( var i = el.children.length; i--; ) {
		if ( el.children[i].nodeType != 8 ) {
			children.unshift( el.children[i] );
		}
	}
	return children;
};

let has_class = ( el, class_name ) => {

	if( ! el ){
		console.log( `Cant test class ${class_name} on null element.` );
		return null;
	}

	if ( el.classList ) {
		return el.classList.contains( class_name );
	}
	else {
		return new RegExp( '(^| )' + class_name + '( |$)', 'gi' ).test( el.className );
	}

};

let remove_class = ( el, class_name ) => {

	if( ! el ){
		console.log( `Cant remove class ${class_name} on null element.` );
		return null;
	}

	if ( el.classList ) {
		el.classList.remove( class_name );
	}
	else {
		el.className = el.className.replace( new RegExp( '(^|\\b)' + class_name.split( ' ' ).join( '|' ) + '(\\b|$)', 'gi' ), ' ' );
	}

	return el;
};

let convert_elements = ( elements ) => {

	let converted = [];
	for ( var i = elements.length; i--; converted.unshift( elements[i] ) ) {}
	return converted;

};

export { add_class, get_children, has_class, remove_class, convert_elements }