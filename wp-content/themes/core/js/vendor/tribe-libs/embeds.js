/**
 * @module
 * @description JavaScript specific to the_content embeds,
 * specifically YouTube & Vimeo oembeds.
 */

import { on } from '../../src/utils/events';

const el = document.getElementsByClassName('wp-embed-lazy');
let $el;

/**
 * @function setOembedDisplayMode
 * @description Set display mode of embeds for small vs. regular.
 */

const setOembedDisplayMode = () => {
	$el.each(() => {
		const $this = $(this);

		if ($this.width() >= 500) {
			$this.removeClass('small-display');
		} else {
			$this.addClass('small-display');
		}
	});
};

/**
 * @function resetEmbed
 * @description Reset embed.
 */

const resetEmbed = () => {
	const $embed = $('.is-playing');

	// Remove embed
	$embed
		.removeClass('is-playing')
		.find('iframe')
		.remove();

	// Fade in image/caption
	$embed.find('.wp-embed-lazy-launch')
		.css('display', 'block')
		.animate({
			opacity: 1,
		}, 0);
};

/**
 * @function playEmbed
 * @description Play embed.
 */

const playEmbed = (e) => {
	e.preventDefault();

	// Reset embed if another is playing
	if ($('.is-playing').length) {
		resetEmbed();
	}

	const $target = $(e.currentTarget);
	const videoId = $target.attr('data-embed-id');
	const iframeUrl = ($target.closest('.wp-embed-lazy').is('.youtube')) ? `https://www.youtube.com/embed/${videoId}?autoplay=1&autohide=1&fs=1&modestbranding=1&showinfo=0&controls=2&autoplay=1&rel=0&theme=light&vq=hd720` : `//player.vimeo.com/video/${videoId}?autoplay=1`;
	const $iframe = $('<iframe/>', {
		id: videoId,
		frameborder: '0',
		src: iframeUrl,
		width: 1280,
		height: 720,
	});

	// Add & kickoff embed
	$target
		.closest('.wp-embed-lazy')
		.addClass('is-playing')
		.prepend($iframe);

	// Fade out image/caption, avoid fouc
	$target
		.animate({
			opacity: 0,
		}, 250, () => {
			$target.css('display', 'none');
		});
};

/**
 * @function executeResize
 * @description Bind the events for this module that react to resize events here.
 */

const executeResize = () => {
	setOembedDisplayMode();
};

/**
 * @function bindEvents
 * @description Bind the events for this module here.
 */

const bindEvents = () => {
	$('body')
		.on('click', '.wp-embed-lazy a', (e) => playEmbed(e));

	on(document, 'modern_tribe/resize_executed', (e) => executeResize(e));
};

/**
 * @function init
 * @description Kick off this modules functions
 */

const embeds = () => {
	if (el) {
		$el = $(el);

		bindEvents();

		setOembedDisplayMode();

		console.info('Initialized embeds scripts.');
	}
};

export default embeds;
