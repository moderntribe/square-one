import { registerRoute } from 'workbox-routing';
import { CacheFirst, StaleWhileRevalidate } from 'workbox-strategies';
import { CacheableResponse } from 'workbox-cacheable-response';
import { ExpirationPlugin } from 'workbox-expiration';
import {
	SERVICE_WORKER_CACHE_NAME_GOOGLE_FONTS,
	SERVICE_WORKER_CACHE_NAME_GOOGLE_FONTS_CSS,
	SERVICE_WORKER_CACHE_NAME_TYPEKIT_CSS,
	SERVICE_WORKER_CACHE_NAME_TYPEKIT_FONTS,
	SERVICE_WORKER_CACHE_NAME_LOCAL_FONTS,
} from 'config/constants';

/**
 * @function google
 * @description
 */

const google = () => {
	registerRoute(
		/^https:\/\/fonts\.googleapis\.com/,
		new StaleWhileRevalidate( {
			cacheName: SERVICE_WORKER_CACHE_NAME_GOOGLE_FONTS_CSS,
		} ),
	);

	// Cache the underlying font files with a cache-first strategy for 1 year.
	registerRoute(
		/^https:\/\/fonts\.gstatic\.com/,
		new CacheFirst( {
			cacheName: SERVICE_WORKER_CACHE_NAME_GOOGLE_FONTS,
			plugins: [
				new CacheableResponse( {
					statuses: [ 0, 200 ],
				} ),
				new ExpirationPlugin( {
					maxAgeSeconds: 60 * 60 * 24 * 365,
					maxEntries: 30,
					purgeOnQuotaError: true,
				} ),
			],
		} ),
	);
};

/**
 * @function
 * @description
 */

const typekit = () => {
	registerRoute(
		/^https:\/\/p\.typekit\.net/,
		new StaleWhileRevalidate( {
			cacheName: SERVICE_WORKER_CACHE_NAME_TYPEKIT_CSS,
		} ),
	);

	// Cache the underlying font files with a cache-first strategy for 1 year.
	registerRoute(
		/^https:\/\/use\.typekit\.net/,
		new CacheFirst( {
			cacheName: SERVICE_WORKER_CACHE_NAME_TYPEKIT_FONTS,
			plugins: [
				new CacheableResponse( {
					statuses: [ 0, 200 ],
				} ),
				new ExpirationPlugin( {
					maxAgeSeconds: 60 * 60 * 24 * 365,
					maxEntries: 30,
					purgeOnQuotaError: true,
				} ),
			],
		} ),
	);
};

/**
 * @function local
 * @description
 */

const local = () => {
	registerRoute(
		new RegExp( '/wp-content/themes/keepwild/fonts/' ),
		new CacheFirst( {
			cacheName: SERVICE_WORKER_CACHE_NAME_LOCAL_FONTS,
			plugins: [
				new ExpirationPlugin( {
					maxAgeSeconds: 60 * 60 * 24 * 365,
					maxEntries: 30,
					purgeOnQuotaError: true,
				} ),
			],
		} ),
	);
};

const init = () => {
	google();
	typekit();
	local();
};

export default init;
