(function($, window) {
	$(document).ready( function($) {
		$('.tribe-redirects tbody td:first-child').prepend('<span class="dashicons dashicons-screenoptions"></span>');
		$('.tribe-redirects tbody').sortable({
			axis: 'y',
			delay: 15,
			cursor: 'move'
		});

		$('.tribe-redirects td.delete').addClass('iconified').prepend('<span class="dashicons dashicons-trash"></span>');
		$('.tribe-redirects').on( 'click', 'td.delete', function() {
			var row = $(this).closest('tr');
			var checkbox = row.find('td.delete input');
			if ( checkbox.prop('checked') ) {
				checkbox.prop('checked', false);
				row.removeClass('deleted');
			} else {
				checkbox.prop('checked', true);
				row.addClass('deleted');
			}
		})
	});
})(jQuery, window);