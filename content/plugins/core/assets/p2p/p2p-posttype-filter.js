(function($, window) {
	$(document).ready( function($) {

		$('.p2p-box').each( function() {
			var box = $(this);
			var type = box.data('p2p_type');
			if ( !type || $.inArray( type, Tribe_P2P_Posttype_Filter.relationships ) < 0 ) {
				return;
			}

			try {
				var post_types = Tribe_P2P_Posttype_Filter.post_types[type];
			} catch ( e ) {
				return;
			}

			if (post_types.length < 2 ) {
				// One post type only, no point in filtering post types
				return;
			}

			var options = $('<select name="p2p-posttype-filter" />');
			$.each( post_types, function( post_type, label) {
				var option = $('<option />');
				option.text( label );
				option.attr('value', post_type);
				options.append( option );
			} );

			box.addClass('has-posttype-filter').find( '.p2p-search' ).append( options );

			P2PAdmin.boxes[type].candidates.sync = function() {
				var params, _this = this;
				params = {
					subaction: 'search',
					post_type: options.val()
				};
				return this.ajax_request(params, function(response) {
					var _ref = response.navigation;
					_this.total_pages = (_ref ? _ref['total-pages-raw'] : void 0) || 1;
					_this.trigger('sync', response);
				});
			};

			options.change( P2PAdmin.boxes[type].candidates.sync.bind(P2PAdmin.boxes[type].candidates) );
		});
	});
})(jQuery, window);