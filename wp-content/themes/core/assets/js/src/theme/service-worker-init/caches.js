/* eslint-disable */
import {
	SERVICE_WORKER_CACHE_NAME_STYLES,
	SERVICE_WORKER_CACHE_NAME_SCRIPTS,
	SERVICE_WORKER_CACHE_NAME_PAGES,
} from 'config/constants';

const requiredPages = [
	'/offline/', // todo: sort out offline plan with kyle and fe
	'/',
];

const cachesToScrub = cacheName => (
		cacheName === SERVICE_WORKER_CACHE_NAME_SCRIPTS
		|| cacheName === SERVICE_WORKER_CACHE_NAME_STYLES
);

async function purgeInvalidAssets() {
	// Get a list of all of the caches for this origin
	const cacheNames = await caches.keys();
	const targets = cacheNames.filter(cachesToScrub);
	for (const name of targets) {
		// Open the cache
		const cache = await caches.open(name);

		// Get a list of entries. Each item is a Request object
		for (const request of await cache.keys()) {
			// todo: scrub old scripts and styles
			// if (request.url.indexOf(BUILD_ASSETS_VERSION) === -1) {
			// 	await cache.delete(request);
			// }
		}
	}
}

async function ensureRequiredPages() { // todo: sort out required pages with team
	const cache = await caches.open(SERVICE_WORKER_CACHE_NAME_PAGES);
	const requests = await cache.keys();
	const cachedPaths = requests.map(request => new URL(request.url).pathname);
	const missing = requiredPages.filter(page => !cachedPaths.includes(page));
	for (const path of missing) {
		await cache.add(path);
	}
}

const init = () => {
	purgeInvalidAssets();
	ensureRequiredPages();
};

export default init;
