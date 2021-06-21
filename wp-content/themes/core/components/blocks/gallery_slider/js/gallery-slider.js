/**
 * @module
 * @description Javascript that drives the gallery slider block.
 */

import * as tools from 'utils/tools';

const gallerySliderBlocks = tools.getNodes( '.b-gallery-slider', true, document, true );

/**
 * @module
 * @description Set variable slide width for caption to match image width.
 */

const setVariableSlideWidth = ( block ) => {
	const slider = tools.getNodes( 'c-slider', false, block )[ 0 ];
	tools.getNodes( '.c-slider__slide', true, slider, true ).forEach( ( slide ) => {
		const slideImageWidth = tools.getNodes( '.c-image__image', false, slide, true )[ 0 ].offsetWidth;
		slide.style.width = `${ slideImageWidth }px`;
	} );
};

/**
 * @function init
 * @description Initialize if the gallery slider block instances are on the page.
 */
const init = () => {
	if ( ! gallerySliderBlocks ) {
		return;
	}

	gallerySliderBlocks.forEach( block => {
		// Handle variable layout sliders.
		if ( block.classList.contains( 'b-gallery-slider--variable' ) ) {
			setTimeout( () => setVariableSlideWidth( block ), 500 );
		}
	} );

	console.info( 'SquareOne Theme: Initialized Gallery Slider block scripts.' );
};

export default init;
