import { registerRoute } from 'workbox-routing';
import { CacheFirst } from 'workbox-strategies';
import {
	SERVICE_WORKER_CACHE_NAME_SCRIPTS,
	SERVICE_WORKER_CACHE_NAME_STYLES,
} from 'config/constants';

/**
 * @function scripts
 * @description
 */

const scripts = () => {
	registerRoute( /\.(?:js)/, new CacheFirst( {
		cacheName: SERVICE_WORKER_CACHE_NAME_SCRIPTS,
	} ) );
};

/**
 * @function styles
 * @description
 */

const styles = () => {
	registerRoute( /\.(?:css)/, new CacheFirst( {
		cacheName: SERVICE_WORKER_CACHE_NAME_STYLES,
	} ) );
};

const init = () => {
	scripts();
	styles();
};

export default init;
