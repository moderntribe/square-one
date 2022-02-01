/**
 * Enable this module in your ready function to fix browser/a11y
 * issues with properly shifting focus to anchor hashed item (skip links)!
 */

import * as tools from '../tools';
import * as test from '../tests';

const elementIsFocusable = ( target ) => {
	return tools.matches( target, 'button:enabled, input:enabled, textarea:enabled, select:enabled, a[href], area[href], object, [tabindex]' ) && ! test.isElementHidden( target );
};

const handleBlurFocusOut = ( target ) => {
	target.removeAttribute( 'tabindex' );
	target.removeEventListener( 'blur', handleBlurFocusOut );
	target.removeEventListener( 'focusout', handleBlurFocusOut );
};

const handleElementFocus = ( hash ) => {
	const target = document.getElementById( hash );

	if ( ! target ) {
		return;
	}

	if ( ! elementIsFocusable( target ) ) {
		target.setAttribute( 'tabindex', '-1' );
		target.addEventListener( 'blur', () => handleBlurFocusOut( target ) );
		target.addEventListener( 'focusout', () => handleBlurFocusOut( target ) );
	}

	target.focus();
};

const handleHashChange = () => {
	handleElementFocus( window.location.hash.substring( 1 ) );
};

const bindEvents = () => {
	window.addEventListener( 'hashchange', handleHashChange, false );
	document.addEventListener( 'modern_tribe/trigger_smooth_anchor', handleHashChange );
};

const init = () => {
	if ( window.location.hash ) {
		handleElementFocus( window.location.hash.substring( 1 ) );
	}

	bindEvents();
};

export default init;
