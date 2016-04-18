'use strict';

/**
 * @function loader
 * @desc A loader animation utility fo masking and displaying a loading system
 */

import { trigger } from '../events';

const loader = ( opts ) => {

	let options = $.extend( {
			background          : 'rgba(0, 0, 0, 0.3)',
			classes             : 'regular-loader',
			fade                : false,
			top                 : '50%',
			spin_background     : 'transparent',
			spin_animation_color: '#ffffff',
			target              : $( 'body' ),
			width               : 200
		}, opts ),

		loader = {

			$el     : $(),
			markup  : '',
			template: '',

			destroy_loader() {

				loader.$el.remove();
				loader = null;

			},

			show_loader() {

				if ( loader.$el.length ) {
					loader.$el.show();
				}
				else {
					loader.template = t.templates[ 'loader' ];
					loader.markup = loader.template( options );
					loader.$el = $( loader.markup );

					options.target.append( loader.$el );
					loader.set_loader_styles();
				}

			},

			hide_loader() {

				if ( options.fade ) {
					loader.$el.fadeOut( 300, function() {
						trigger( { event: 'modern_tribe/loader_hidden', native: false } );
					} );
				}
				else {
					loader.$el.hide();
					trigger( { event: 'modern_tribe/loader_hidden', native: false } );
				}

			},

			set_loader_styles() {

				if ( ! options.target.is( 'body' ) ) {
					loader.$el.css( 'position', 'absolute' ).parent().css( 'position', 'relative' );
				}

				loader.$el
					.css( {
						'background-color': options.background
					} )
					.find( '.sls-loading-spinner' )
					.css( {
						'background-color': options.spin_background,
						'margin-left'     : `-${options.width / 2}px`,
						'margin-top'      : `-${options.width / 2}px`,
						'height'          : options.width + 'px',
						'top'             : options.top,
						'width'           : options.width + 'px'
					} );

				var anim_height = loader.$el.find( 'i' ).first().height();

				loader.$el
					.find( 'i' )
					.css( {
						'background-color': options.spin_animation_color,
						'top'             : `${(options.width / 2) - (anim_height / 2)}px`
					} );

			}
		};

	return {
		destroy: loader.destroy_loader,
		hide   : loader.hide_loader,
		show   : loader.show_loader
	};

};

export default loader;