/**
 * @module Slider
 * @description Javascript that drives the main slider component
 */

import _ from 'lodash';
import { Swiper, Navigation, Pagination, A11y, Autoplay } from 'swiper/js/swiper.esm.js';

import * as tools from 'utils/tools';
import * as tests from 'utils/tests';

Swiper.use( [ Navigation, Pagination, A11y, Autoplay ] );

const instances = {
	swipers: {},
};

const options = {
	swiperMain: () => ( {
		a11y: true,
	} ),
	swiperThumbs: () => ( {
		a11y: true,
		slidesPerView: 'auto',
		touchRatio: 0.2,
		watchSlidesVisibility: true,
		watchSlidesProgress: true,
	} ),
};

/**
 * @module
 * @description Update Pagination.
 */

const updatePagination = ( slider, swiperId ) => {
	const pagination = tools.getNodes( 'c-slider-pagination', false, slider )[ 0 ];
	if ( ! pagination ) {
		return;
	}
	pagination.setAttribute( 'data-id', swiperId );
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
	const opts = options.swiperMain();
	if ( slider.classList.contains( 'c-slider__main--has-arrows' ) ) {
		opts.navigation = {};
		opts.navigation.nextEl = '.c-slider__button--next';
		opts.navigation.prevEl = '.c-slider__button--prev';
	}
	if ( slider.classList.contains( 'c-slider__main--has-pagination' ) ) {
		opts.pagination = {};
		opts.pagination.el = `.c-slider__pagination[data-id="${ swiperId }"]`;
		opts.pagination.clickable = true;
		updatePagination( slider, swiperId );
	}
	if ( slider.classList.contains( 'c-slider__main--has-carousel' ) ) {
		const carousel = slider.nextElementSibling;
		if ( carousel ) {
			const swiperThumbId = _.uniqueId( 'swiper-carousel-' );
			instances.swipers[ swiperThumbId ] = new Swiper( carousel, getMainOptsForCarousel( carousel ) );
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
		instances.swipers[ swiperMainId ] = new Swiper( slider, getMainOptsForSlider( slider, swiperMainId ) );
		slider.setAttribute( 'data-id', swiperMainId );
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
};

const init = () => {
	initSliders();
	bindEvents();

	console.info( 'Square One FE: Initialized slider component scripts.' );
};

export default init;
