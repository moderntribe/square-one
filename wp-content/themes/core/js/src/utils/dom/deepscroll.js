
import _ from 'lodash';

/**
 * @function deepscroll
 * @desc A plugin that updates the url as targets are scrolled by using the data attribute
 * data-url-key. It depends on lodash and waypoints. This code is ie9 and up.
 *
 * @param opts Object The options object. Check below for available and defaults.
 */

const deepScroll = function (opts) {

	let options = _.assign({
			attr: 'data-url-key',
			targets: null,
			offset: 0,
		}, opts);
	let url = `${document.location.protocol}//${document.location.hostname}${document.location.pathname}`;
	let items = [];
	let nodes;

	const _updateHash = (el) => {

		if (history.pushState) {

			if (el) {
				let hash = el.getAttribute('data-url-key') ? `#${el.getAttribute('data-url-key')}` : window.location.pathname;
				history.replaceState('', '', hash);
			} else {
				history.replaceState('', '', url);
			}

		}

	};

	const _triggerScrollby = (el) => {

		$(document).trigger('modern_tribe/scrolledto', { el: el });

	};

	const _handleWaypointDown = (dir, el) => {

		if (dir === 'down') {
			_updateHash(el);
			_triggerScrollby(el);
		}

		if (dir === 'up' && $(el).is('.panel-count-0')) {
			_updateHash(null);
			_triggerScrollby(null);
		}

	};

	const _handleWaypointUp = (dir, el) => {

		if (dir === 'up') {
			_updateHash(el);
			_triggerScrollby(el);
		}

	};

	const _applyWaypoint = (el) => {

		let data = {};
		let urlKey = el.getAttribute(options.attr);
		let title = el.getAttribute('data-nav-title');

		data[(urlKey ? urlKey : _.uniqueId('way-')) + '-down'] = new Waypoint({
			element: el,
			handler: function (dir) {
				_handleWaypointDown(dir, el);
			},

			offset: options.offset + 'px',
		});

		data[(urlKey ? urlKey : _.uniqueId('way-')) + '-up'] = new Waypoint({
			element: el,
			handler: function (dir) {
				_handleWaypointUp(dir, el);
			},

			offset: function () {
				return -(this.element.clientHeight - options.offset);
			},

		});

		items.push({
			has_data: el.innerHTML.trim() !== '',
			url_key: urlKey,
			title: title,
			waypoint: data,
		});

	};

	const _executeResize = () => {

		Waypoint.refreshAll();

	};

	const _refresh = () => {

		_.delay(() => Waypoint.refreshAll(), 1000);

	};

	const _bindEvents = () => {

		document.addEventListener('modern_tribe/refresh_waypoints', _executeResize);
		document.addEventListener('modern_tribe/resize_executed', _executeResize);
		document.addEventListener('modern_tribe/accordion_animated', _executeResize);
		window.addEventListener('load', _refresh);

	};

	if (options.targets) {

		nodes = [].slice.call(options.targets);
		nodes.forEach((el) => _applyWaypoint(el));

		_bindEvents();

	}

};

export default deepScroll;
