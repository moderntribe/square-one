import { registerRoute } from 'workbox-routing';
import { CacheFirst } from 'workbox-strategies';
import { ExpirationPlugin } from 'workbox-expiration';
import {
	SERVICE_WORKER_CACHE_NAME_IMAGES,
} from 'config/constants';

const init = () => {
	registerRoute(
		/\.(?:png|gif|jpg|jpeg|svg)$/,
		new CacheFirst( {
			cacheName: SERVICE_WORKER_CACHE_NAME_IMAGES,
			plugins: [
				new ExpirationPlugin( {
					maxEntries: 60,
					maxAgeSeconds: 30 * 24 * 60 * 60, // 30 Days
					purgeOnQuotaError: true,
				} ),
			],
		} ),
	);
};

export default init;
