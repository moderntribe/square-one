import {
	SERVICE_WORKER_CACHE_NAME_PAGES,
} from 'config/constants';
import images from './images';
import fonts from './fonts';
import scriptsAndStyles from './scripts-styles';
import routes from './routes';

/**
 * @function bindEvents
 * @description
 */

const bindEvents = () => {
	self.addEventListener( 'install', ( event ) => {
		// todo: sync this with required pages in init
		const urls = [ '/', '/offline/' ];
		event.waitUntil( caches.open( SERVICE_WORKER_CACHE_NAME_PAGES ).then( cache => cache.addAll( urls ) ) );
	} );
};

const init = () => {
	images();
	fonts();
	scriptsAndStyles();
	routes();
	bindEvents();
};

init();
