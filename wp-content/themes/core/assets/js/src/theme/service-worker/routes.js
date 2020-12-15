import { registerRoute } from 'workbox-routing';
import { NetworkFirst } from 'workbox-strategies';
import {
	SERVICE_WORKER_CACHE_NAME_PAGES,
} from 'config/constants';

const instances = {};

const pagesHandler = async( args ) => {
	try {
		// todo: set offline page in constants or most likely pipe from php
		const response = await instances.pageRoute.handle( args );
		return response || await caches.open( SERVICE_WORKER_CACHE_NAME_PAGES ).then( cache => cache.match( '/offline/' ) );
	} catch ( error ) {
		return await caches.open( SERVICE_WORKER_CACHE_NAME_PAGES ).then( cache => cache.match( '/offline/' ) ); // eslint-disable-line
	}
};

const init = () => {
	instances.pageRoute = new NetworkFirst( {
		cacheName: SERVICE_WORKER_CACHE_NAME_PAGES,
	} );
	registerRoute( new RegExp( /\// ), pagesHandler );
};

export default init;
