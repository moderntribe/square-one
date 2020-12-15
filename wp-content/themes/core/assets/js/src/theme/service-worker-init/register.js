import { Workbox } from 'workbox-window';
import { SCRIPT_DEBUG } from 'config/wp-settings';

export const instances = {};

/**
 * @function instantiate
 * @description
 */

const instantiate = () => {
	const path = SCRIPT_DEBUG ? '/sw.js' : '/sw.min.js';
	instances.workbox = new Workbox( path );
};

/**
 * @function register
 * @description
 */

const register = () => {
	instances.workbox.register();
};

const init = () => {
	instantiate();
	register();
};

export default init;
