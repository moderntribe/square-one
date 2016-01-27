'use strict';

/**
 * @function deepscroll
 * @desc A plugin that updates the url as targets are scrolled by using the data attribute
 * data-url-key. It depends on lodash and waypoints. This code is ie9 and up.
 *
 * @param opts Object The options object. Check below for available and defaults.
 */

let deepscroll = function( opts ) {

	let options = _.assign( {
			attr         : 'data-url-key',
			targets      : null,
			offset       : 0
		}, opts ),
		url = `${document.location.protocol}//${document.location.hostname}${document.location.pathname}`,
		items = [],
		nodes;

	let _update_hash = ( el ) => {

		if ( history.pushState ) {

			if( el ){
				let hash = el.getAttribute( 'data-url-key' ) ? `#${el.getAttribute( 'data-url-key' )}` : window.location.pathname;
				history.replaceState( '', '', hash );
			} else {
				history.replaceState( '', '', url );
			}


		}

	};

	let _trigger_scrollby = ( el ) => {

		$( document ).trigger( 'modern_tribe/scrolledto',  { el: el } );

	};

	let _handle_waypoint_down = ( dir, el ) => {

		if( dir === 'down' ){
			_update_hash( el );
			_trigger_scrollby( el );
		}

		if( dir === 'up' && $( el ).is( '.panel-count-0') ){
			_update_hash( null );
			_trigger_scrollby( null );
		}

	};

	let _handle_waypoint_up = ( dir, el ) => {

		if( dir === 'up' ){
			_update_hash( el );
			_trigger_scrollby( el );
		}

	};

	let _apply_waypoint = ( el ) => {

		let data = {},
			url_key = el.getAttribute( options.attr ),
			title = el.getAttribute( 'data-nav-title' );

		data[ ( url_key ? url_key : _.uniqueId( 'way-' ) ) + '-down' ] = new Waypoint({
			element: el,
			handler: function( dir ) { _handle_waypoint_down( dir, el ) },
			offset: options.offset + 'px'
		});

		data[ ( url_key ? url_key : _.uniqueId( 'way-' ) ) + '-up' ] = new Waypoint({
			element: el,
			handler: function( dir ) { _handle_waypoint_up( dir, el ) },
			offset: function() {
				return -( this.element.clientHeight - options.offset )
			}
		});

		items.push( {
			has_data: el.innerHTML.trim() !== "",
			url_key : url_key,
			title   : title,
			waypoint: data
		} );

	};

	let _execute_resize = () => {

		Waypoint.refreshAll();

	};

	let _refresh = () => {

		_.delay( () => Waypoint.refreshAll(), 1000 );

	};

	let _bind_events = () => {

		document.addEventListener( 'modern_tribe/refresh_waypoints', _execute_resize );
		document.addEventListener( 'modern_tribe/resize_executed', _execute_resize );
		document.addEventListener( 'modern_tribe/accordion_animated', _execute_resize );
		window.addEventListener( 'load', _refresh );

	};

	if ( options.targets ) {

		nodes = [].slice.call( options.targets );
		nodes.forEach( ( el ) => _apply_waypoint( el ) );

		_bind_events();

	}

};

export default deepscroll;
