/**
 * Scripts to control the search results
 */

import delegate from 'delegate';
import * as tools from 'utils/tools';

const el = {
	searchInputs: tools.getNodes( 'search-form-input', true ),
};

/**
* Update each search input on page load.
*
* @param e
*/
const setInputState = ( e ) => updateInput( e.target );


/**
* Reset all search forms on click.
*
* @param e
*/
const resetSearchForms = ( e ) => {
	const currentInput = tools.closest( e.target, '[data-js="search-form"]' ).querySelector( '[data-js="search-form-clear"]' );

	el.searchInputs.forEach( ( input ) => {
		input.setAttribute( 'value', '' );
	} );

	currentInput.focus();
};

/**
* Prevent an empty form from submitting.
*
* @param e
*/
const handleSearchSubmit = ( e ) => {
	const currentInput = e.target.querySelector( '[data-js="search-form-input"]' );
	if ( ! currentInput.value.length ) {
		e.preventDefault();
		currentInput.focus();
	}
};

/**
*Bind events for this module.
*/
const bindEvents = () => {
	delegate( document, '[data-js="search-form-clear"]', 'click', resetSearchForms );
	delegate( document, '[data-js="search-form"]', 'submit', handleSearchSubmit );
};

/**
* Init this module.
*/
const init = () => {
	if ( ! el.searchInputs.length ) {
		return;
	}

	bindEvents();

	console.info( 'SquareOne Theme: Initialized search scripts.' );
};

export default init;
