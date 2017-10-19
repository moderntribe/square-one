/**
 * Term Sorter Js
 *
 * Main js file for the Term Sorter plugins
 *
 * @uses cued only on the taxonomy terms editing pages
 *
 * @author Mat Lipe
 *
 */
jQuery(function($) {

	termSorter.init($);
});

/**
 * Term Sorter
 *
 * Main Object for Term Sorting
 *
 */
var termSorter = {

	/**
	 * The Terms
	 * 
	 * Holds the terms list
	 * 
	 * @var obj 
	 */
	theTerms : null,


	/**
	 *  init
	 * 
	 *  Turn Eveything on
	 * 
	 *  @return void
	 */
	init : function($) {
		this.makeSortable($);

	},


	/**
	 * Make Sortable
	 * 
	 * Manage the drag and drop terms lists
	 * 
	 * @param jQuery $
	 * 
	 * @return void
	 *  
	 */
	makeSortable : function($) {
		termSorter.theTerms = $('#the-list');

		termSorter.theTerms.sortable({
			items : ' > tr',
			cursor : 'move',
			axis : 'y',
			containment : 'table.widefat',
			scrollSensitivity : 40,
			cancel : '.inline-edit-row',
			distance : 5,
			opacity : .85,
			forceHelperSize : true,
			update : function(event, ui) {
				termSorter.theTerms.sortable('disable').addClass('ts-updating');

				ui.item.addClass('ts-updating-row');
				ui.item.find('.check-column').css('background', 'url(' + adminImagesUrl + 'wpspin_light.gif) center center no-repeat');
				var termid = ui.item[0].id.substr(4);

				var prevtermid = false;
				var prevterm = ui.item.prev();
				if (prevterm.length > 0) {
					prevtermid = prevterm.attr('id').substr(4);
				}

				var nexttermid = false;
				var nextterm = ui.item.next();
				if (nextterm.length > 0) {
					nexttermid = nextterm.attr('id').substr(4);
				}

				// go do the sorting stuff via ajax
				$.post(ajaxurl, {
					action   : 'term_sort_update',
					id       : termid,
					previd   : prevtermid,
					nextid   : nexttermid,
					taxonomy : $('input[name="taxonomy"]').val()
				}, termSorter.updateOrder);

				// fix cell colors
				var table_rows = document.querySelectorAll('#the-list tr'), table_row_count = table_rows.length;
				while (table_row_count--) {
					if (table_row_count % 2 == 0) {
						$(table_rows[table_row_count]).addClass('alternate');
					} else {
						$(table_rows[table_row_count]).removeClass('alternate');
					}
				}
			}
		});

	},

	/**
	 * Update Order
	 *
	 * Updates the term order then removes anything added by the dragging process
	 *
	 * @uses added to the jQuery.post() callback by self.makeSortable
	 *
	 * @param {Object} response
	 * 
	 * @return void
	 */
	updateOrder : function( response ) {
		var $ = jQuery.noConflict();
		if ('children' === response ) {
			window.location.reload();
			return;
		}

		var changes = jQuery.parseJSON(response);

		var new_pos = changes.new_pos;
		for (var key in new_pos ) {
			if ('next' === key)
				continue;

			var inline_key = document.getElementById('inline_' + key);
			if (null !== inline_key && new_pos.hasOwnProperty(key)) {
				var dom_term_order = inline_key.querySelector('.term_order');

				if (undefined !== new_pos[key]['term_order']) {
					if (null !== dom_term_order)
						dom_term_order.innerHTML = new_pos[key]['term_order'];


					var dom_parent = inline_key.querySelector('.parent');
					if (null !== dom_parent)
						dom_parent.innerHTML = new_pos[key]['parent'];

					var term_name = null;
					var dom_term_name = inline_key.querySelector('.name');
					if (null !== dom_term_name)
						term_name = dom_term_name.innerHTML;

					var dashes = 0;
					while (dashes < new_pos[key]['depth']) {
						term_name = '&mdash; ' + term_name;
						dashes++;
					}
					var dom_row_title = inline_key.parentNode.querySelector('.row-title');
					if (null !== dom_row_title && null !== term_name)
						dom_row_title.innerHTML = term_name;
				} else if (null !== dom_term_order) {
					dom_term_order.innerHTML = new_pos[key];
				}
			}
		}

        //if we have changes to do
		if (changes.next) {
			$.post(ajaxurl, {
				action : 'simple_page_ordering',
				id : changes.next['id'],
				previd : changes.next['previd'],
				nextid : changes.next['nextid'],
				start : changes.next['start'],
				excluded : changes.next['excluded']
			}, termSorter.updateOrder );
			
	    //where done, remove the updating notifications
		} else {			
			$(document.querySelector('.ts-updating-row')).each(function() {
				$(this).removeClass('ts-updating-row');
				$(this).find('.check-column').css('background', 'none');
			});

			termSorter.theTerms.removeClass('ts-updating').sortable('enable');
		}

	}
};
