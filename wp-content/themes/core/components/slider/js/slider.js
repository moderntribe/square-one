/**
 * @module Slider
 * @description Javascript that drives the main slider component
 */

import _ from 'lodash';
import SwiperCore, { Navigation, Pagination, A11y, Autoplay, Thumbs } from 'swiper/core';

SwiperCore.use( [ Navigation, Pagination, A11y, Autoplay, Thumbs ] );

import * as tools from 'utils/tools';
import * as tests from 'utils/tests';

const instances = {
	swipers: {},
};

const options = {
	swiperMain: () => ( {
		a11y: {
			enabled: true,
		},
	} ),
	swiperThumbs: () => ( {
		a11y: {
			enabled: true,
		},
		slidesPerView: 'auto',
		touchRatio: 0.2,
		watchSlidesVisibility: true,
		watchSlidesProgress: true,
	} ),
};

/**
 * @function updateCarousel
 * @description Update carousel
 */

const updateCarousel = ( carousel, swiperId, swiperThumbId ) => {
	carousel.classList.add( 'initialized' );
	carousel.setAttribute( 'data-id', swiperThumbId );
	carousel.setAttribute( 'data-controls', swiperId );
};

/**
 * @function getMainOptsForCarousel
 * @description Get the main variable options for the carousel
 */

const getMainOptsForCarousel = ( carousel ) => {
	const opts = options.swiperThumbs();
	if ( carousel.dataset.swiperOptions && tests.isJson( carousel.dataset.swiperOptions ) ) {
		Object.assign( opts, JSON.parse( carousel.dataset.swiperOptions ) );
	}
	return opts;
};

/**
 * @function getMainOptsForSlider
 * @description Get the main variable options for the slider
 */

const getMainOptsForSlider = ( slider, swiperId ) => {
	const sliderWrapper = tools.closest( slider, '.c-slider' );
	const opts = options.swiperMain();
	if ( slider.classList.contains( 'c-slider__main--has-arrows' ) ) {
		opts.navigation = {};
		opts.navigation.nextEl = sliderWrapper.querySelector( '.c-slider__button--next' );
		opts.navigation.prevEl = sliderWrapper.querySelector( '.c-slider__button--prev' );
	}
	if ( slider.classList.contains( 'c-slider__main--has-pagination' ) ) {
		opts.pagination = {};
		opts.pagination.el = sliderWrapper.querySelector( '.c-slider__pagination' );
		opts.pagination.clickable = true;
	}
	if ( slider.classList.contains( 'c-slider__main--has-carousel' ) ) {
		const carousel = sliderWrapper.querySelector( '.c-slider__carousel' );
		if ( carousel ) {
			const swiperThumbId = _.uniqueId( 'swiper-carousel-' );
			instances.swipers[ swiperThumbId ] = new SwiperCore( carousel, getMainOptsForCarousel( carousel ) );
			opts.thumbs = {};
			opts.thumbs.swiper = instances.swipers[ swiperThumbId ];
			updateCarousel( carousel, swiperId, swiperThumbId );
		}
	}
	if ( slider.dataset.swiperOptions && tests.isJson( slider.dataset.swiperOptions ) ) {
		Object.assign( opts, JSON.parse( slider.dataset.swiperOptions ) );
	}
	return opts;
};

/**
 * @module
 * @description Focus row from index and row index
 */

const focusRow = ( index, rowIndex, jumpTo ) => {
	const sliderSelector = `[data-js="panel"][data-index="${ index }"] [data-js="c-slider"]`;
	const slider = tools.getNodes( sliderSelector, false, document, true )[ 0 ];
	if ( slider && slider.swiper ) {
		let newSpeed = slider.swiper.params.speed;
		if ( jumpTo ) {
			newSpeed = 0;
		}
		slider.swiper.slideTo( rowIndex, newSpeed );
	}
};

/**
 * @module
 * @description Swiper init.
 */

const initSliders = () => {
	tools.getNodes( '[data-js="c-slider"]:not(.initialized)', true, document, true ).forEach( ( slider ) => {
		const swiperMainId = _.uniqueId( 'swiper-' );
		slider.classList.add( 'initialized' );
		instances.swipers[ swiperMainId ] = new SwiperCore( slider, getMainOptsForSlider( slider, swiperMainId ) );
		slider.setAttribute( 'data-id', swiperMainId );
		slider.setAttribute( 'id', swiperMainId );
	} );
};

/**
 * @module
 * @description Responds to panel live updating.
 */

const previewChangeHandler = ( e ) => {
	if ( e.type === 'modular_content/panel_preview_updated' ) {
		initSliders();
	}
	_.delay( () => {
		// for cases when all we have is the parent (example: when updating CTAs)
		const data = _.get( e, 'detail.parent.data', {} );
		if ( typeof data.index !== 'undefined' && typeof data.childIndex !== 'undefined' ) {
			focusRow( data.index, data.childIndex, true );
		} else if ( typeof e.detail.index !== 'undefined' && typeof e.detail.rowIndex !== 'undefined' ) {
			if ( e.type === 'modular_content/repeater_row_deactivated' ) {
				focusRow( e.detail.index, 0 );
			} else {
				focusRow( e.detail.index, e.detail.rowIndex );
			}
		}
	}, 50 );
};

/**
 * @module
 * @description Bind Events.
 */

const bindEvents = () => {
	document.addEventListener( 'modular_content/panel_preview_updated', previewChangeHandler );
	document.addEventListener( 'modular_content/repeater_row_activated', previewChangeHandler );
	document.addEventListener( 'modular_content/repeater_row_deactivated', previewChangeHandler );
	document.addEventListener( 'modular_content/repeater_row_added', previewChangeHandler );
	document.addEventListener( 'modern_tribe/component_dialog_rendered', initSliders );
};

const init = () => {
	if ( ! SwiperCore ) {
		return;
	}
	initSliders();
	bindEvents();

	console.info( 'SquareOne Theme: Initialized slider component scripts.' );
};

export default init;
