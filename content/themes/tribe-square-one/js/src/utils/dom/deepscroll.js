'use strict';

/**
 * @function deepscroll
 * @desc A plugin that updates the url as targets are scrolled by using the data attribute
 * data-url-key. It depends on lodash and waypoints. This code is ie9 and up.
 *
 * @param opts Object The options object. Check below for available and defaults.
 */

export default function deepscroll( opts ) {

	this.options = _.assign( {
		attr         : 'data-url-key',
		controller   : null,
		targets      : null,
		offset       : 0
	}, opts );

	this.url = sprintf( '%s//%s%s', document.location.protocol, document.location.hostname, document.location.pathname );
	this.state = {};
	this.items = [];

	this.init = () => {

		if ( this.options.targets ) {

			this.nodes = [].slice.call( this.options.targets );
			this.nodes.forEach( ( el ) => this._apply_waypoint( el ) );

		} else {

			console.info( 'Deepscroll plugin was called but no nav elements where found.' );

		}

	};

	this._apply_waypoint = ( el ) => {

		let _this = this,
			data = {},
			url_key = el.getAttribute( this.options.attr ),
			title = el.getAttribute( 'data-nav-title' );

		data[ ( url_key ? url_key : _.uniqueId( 'way-' ) ) + '-down' ] = new Waypoint({
			element: el,
			handler: function( dir ) { this._handle_waypoint_down( dir, el ) }.bind( this ),
			offset: this.options.offset + 'px'
		});

		data[ ( url_key ? url_key : _.uniqueId( 'way-' ) ) + '-up' ] = new Waypoint({
			element: el,
			handler: function( dir ) { this._handle_waypoint_up( dir, el ) }.bind( this ),
			offset: function() {
				return -( this.element.clientHeight - _this.options.offset )
			}
		});

		this.items.push( {
			has_data: el.innerHTML.trim() !== "",
			url_key : url_key,
			title   : title,
			waypoint: data
		} );

	};

	this._handle_waypoint_down = ( dir, el ) => {

		if( dir === 'down' ){
			this._update_hash( el );
			this._trigger_scrollby( el );
		}

	};

	this._handle_waypoint_up = ( dir, el ) => {

		if( dir === 'up' ){
			this._update_hash( el );
			this._trigger_scrollby( el );
		}

	};

	this._update_hash = ( el ) => {

		if ( history.pushState && ! this.options.controller.scrolling ) {

			let hash = el.getAttribute( 'data-url-key' ) ? sprintf( '#%s', el.getAttribute( 'data-url-key' ) ) : window.location.pathname;
			history.replaceState( '', '', hash );

		}

	};

	this._trigger_scrollby = ( el ) => {

		$( document ).trigger( 'modern_tribe_scrolledto',  { el: el } );

	};

	return this;

};
