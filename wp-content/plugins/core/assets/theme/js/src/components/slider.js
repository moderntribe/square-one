/**
 * @module Slider
 * @description Javascript that drives the main slider component
 */

import _ from 'lodash';
import delegate from 'delegate';
import Swiper from 'swiper';

import * as tools from '../utils/tools';

const instances = {
	swipers: {},
};

const options = {
	swiperMain: () => ({
		a11y: true,
	}),
	swiperThumbs: () => ({
		a11y: true,
		slidesPerView: 'auto',
		touchRatio: 0.2,
		slideToClickedSlide: true,
	}),
};

/**
 * @module
 * @description Update Pagination.
 */

const updatePagination = (slider, swiperId) => {
	const pagination = tools.getNodes('c-slider-pagination', false, slider)[0];
	if (!pagination) {
		return;
	}
	pagination.setAttribute('data-id', swiperId);
};

/**
 * @function getMainOptsForSlider
 * @description Get the main variable options for the slider
 */

const getMainOptsForSlider = (slider, swiperId) => {
	const opts = options.swiperMain();
	if (slider.classList.contains('c-slider__main--has-arrows')) {
		opts.nextButton = '.c-slider__button--next';
		opts.prevButton = '.c-slider__button--prev';
	}
	if (slider.classList.contains('c-slider__main--has-pagination')) {
		opts.pagination = `.c-slider__pagination[data-id="${swiperId}"]`;
		opts.paginationClickable = true;
		updatePagination(slider, swiperId);
	}
	return opts;
};

/**
 * @function syncMainSlider
 * @description Sync the main slider to the carousel.
 * Too bad swiper has a bug with this and we have to resort this this stuff
 * https://github.com/nolimits4web/Swiper/issues/1658
 */

const syncMainSlider = (e) => {
	const carousel = tools.closest(e.delegateTarget, '.swiper-container');
	instances.swipers[carousel.dataset.controls].slideTo(e.delegateTarget.dataset.index);
};

/**
 * @module bindCarouselEvents
 * @description Bind Carousel Events.
 */

const bindCarouselEvents = (swiperThumbId, swiperMainId) => {
	instances.swipers[swiperMainId].on('slideChangeStart', (instance) => {
		instances.swipers[swiperThumbId].slideTo(instance.activeIndex);
	});
	delegate(instances.swipers[swiperThumbId].wrapper[0], '[data-js="c-slider-thumb-trigger"]', 'click', syncMainSlider);
};

/**
 * @function initCarousel
 * @description Init the carousel
 */

const initCarousel = (slider, swiperMainId) => {
	const carousel = slider.nextElementSibling;
	const swiperThumbId = _.uniqueId('swiper-carousel-');
	carousel.classList.add('initialized');
	instances.swipers[swiperThumbId] = new Swiper(carousel, options.swiperThumbs());
	slider.setAttribute('data-controls', swiperThumbId);
	carousel.setAttribute('data-id', swiperThumbId);
	carousel.setAttribute('data-controls', swiperMainId);
	bindCarouselEvents(swiperThumbId, swiperMainId);
};

/**
 * @module
 * @description Swiper init.
 */

const initSliders = () => {
	tools.getNodes('[data-js="c-slider"]:not(.initialized)', true, document, true).forEach((slider) => {
		const swiperMainId = _.uniqueId('swiper-');
		slider.classList.add('initialized');
		instances.swipers[swiperMainId] = new Swiper(slider, getMainOptsForSlider(slider, swiperMainId));
		slider.setAttribute('data-id', swiperMainId);
		if (!slider.classList.contains('c-slider__main--has-carousel')) {
			return;
		}
		initCarousel(slider, swiperMainId);
	});
};

/**
 * @module
 * @description Bind Events.
 */

const bindEvents = () => {
	document.addEventListener('modular_content/panel_preview_updated', initSliders);
};

const init = () => {
	if (!tools.getNodes('c-slider')[0]) {
		return;
	}
	initSliders();
	bindEvents();

	console.info('Modern Tribe FE: Initialized slider components.');
};

export default init;
