/**
 * @module
 * @description Javascript that drives the gallery slider block.
 */

import * as tools from 'utils/tools';
import { on } from 'utils/events';

const gallerySliderBlocks = tools.getNodes( '.b-gallery-slider', true, document, true );

/**
 * @function setVariableSlideWidth
 * @description Set slide width on variable image ratio sliders to contain caption.
 */

const setVariableSlideWidth = ( swiper ) => {
	const slider = swiper.el;
	if ( ! slider || ! slider.classList.contains( 'b-gallery-slider--variable' ) ) {
		return;
	}

	tools.getNodes( '.c-slider__slide', true, slider, true ).forEach( ( slide ) => {
		const slideImageWidth = tools.getNodes( '.c-image__image', false, slide, true )[ 0 ].offsetWidth;
		slide.style.width = `${ slideImageWidth }px`;
	} );

	// Recalculate slide offsets
	swiper.updateSlides();
};

/**
 * @function bindEvents
 * @description Bind the events for this module here.
 */

const bindEvents = () => {
	on( document, 'modern_tribe/swiper_images_ready', setVariableSlideWidth );
};

/**
 * @function init
 * @description Initialize if gallery slider blocks are on the page.
 */
const init = () => {
	if ( ! gallerySliderBlocks ) {
		return;
	}

	bindEvents();

	console.info( 'SquareOne Theme: Initialized Gallery Slider block scripts.' );
};

export default init;
