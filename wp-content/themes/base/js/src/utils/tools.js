/**
 * @module
 * @description Some vanilla js cross browser utils
 */

'use strict';

const add_class = ( el, class_name ) => {
	if ( ! el ) {
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

const get_children = ( el ) => {
	let children = [];
	for ( var i = el.children.length; i --; ) {
		if ( el.children[ i ].nodeType !== 8 ) {
			children.unshift( el.children[ i ] );
		}
	}
	return children;
};

const has_class = ( el, class_name ) => {

	if ( ! el ) {
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

const remove_class = ( el, class_name ) => {

	if ( ! el ) {
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

const convert_elements = ( elements ) => {

	let converted = [];
	for ( var i = elements.length; i --; converted.unshift( elements[ i ] ) ) {}
	return converted;

};

const is_nodeList = ( elements ) => {

	let string_repr = Object.prototype.toString.call( elements );

	return typeof elements === 'object' &&
		/^\[object (HTMLCollection|NodeList|Object)\]$/.test( string_repr ) &&
		elements.hasOwnProperty( 'length' ) &&
		( elements.length === 0 || ( typeof elements[ 0 ] === "object" && elements[ 0 ].nodeType > 0 ) );

};

export { add_class, get_children, has_class, remove_class, convert_elements, is_nodeList }