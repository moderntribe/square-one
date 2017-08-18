/**
 * @module Slider
 * @description Javascript that drives the main slider component
 */

import _ from 'lodash';
import Swiper from 'swiper';

import * as tools from '../utils/tools';

const instances = {
	swipers: {},
};

const options = {
	swiperMain: () => ({

	}),
	swiperThumbs: () => ({
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
 * @module
 * @description Swiper init.
 */

const initSlider = () => {
	tools.getNodes('[data-js="c-slider"]:not(.initialized)', true, document, true).forEach((slider) => {
		const swiperMainId = _.uniqueId('swiper-');
		instances.swipers[swiperMainId] = new Swiper(slider, getMainOptsForSlider(slider, swiperMainId));
		slider.setAttribute('data-id', swiperMainId);
		slider.classList.add('initialized');
		if (!slider.classList.contains('c-slider__main--has-carousel')) {
			return;
		}
		const carousel = slider.nextElementSibling;
		const swiperThumbId = _.uniqueId('swiper-carousel-');
		instances.swipers[swiperThumbId] = new Swiper(carousel, options.swiperThumbs());
		carousel.setAttribute('data-id', swiperThumbId);
		carousel.classList.add('initialized');
		instances.swipers[swiperMainId].params.control = instances.swipers[swiperThumbId];
		instances.swipers[swiperThumbId].params.control = instances.swipers[swiperMainId];
	});
};

/**
 * @module
 * @description Bind Events.
 */

const bindEvents = () => {
	document.addEventListener('modular_content/panel_preview_updated', initSlider);
};

const init = () => {
	if (!tools.getNodes('c-slider')[0]) {
		return;
	}
	initSlider();
	bindEvents();

	console.info('Modern Tribe FE: Initialized slider components.');
};

export default init;
