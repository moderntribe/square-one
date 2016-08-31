var AttachmentHelper = window.AttachmentHelper || {};
(function($, AttachmentHelper) {
	AttachmentHelper.counter = 0;
	AttachmentHelper.init = function( container, settings ) {
		var plupload_settings = settings.plupload_init;
		//var index = container.data('id');
		var index = AttachmentHelper.counter++;
		container.attr('id', 'uploadContainer-'+index);
		var field = container.find('.attachment_helper_value');
		container.find('.plupload-upload-ui').attr('id', 'plupload-upload-ui-'+index);
		container.find('.drag-drop-area').attr('id', 'drag-drop-area-'+index);
		container.find('.plupload-browse-button').attr('id', 'plupload-browse-button-'+index);
		container.find('.uploaderSection').attr('id', 'uploaderSection-'+index);
		container.find('.current-uploaded-image').attr('id', 'current-uploaded-image-'+index);
		container.find('.attachment_helper_library_button').attr('id', 'attachment_helper_library_button-'+index);
		container.find('.remove-image').attr('id', 'remove-image-'+index);


		plupload_settings.container = 'plupload-upload-ui-' + index;
		plupload_settings.drop_element = 'drag-drop-area-' + index;
		plupload_settings.browse_button = 'plupload-browse-button-' + index;
		plupload_settings.multipart_params.size = container.data( 'size' );

		// Create uploader and pass configuration:
		var uploader = new plupload.Uploader( plupload_settings );
		uploader.size =  container.data( 'size' );
		uploader.type =  container.data( 'type' );

		// Check for drag'n'drop functionality:
		uploader.bind('Init', function(up) {

			var uploaddiv = $('#plupload-upload-ui-' + index);

			// Add classes and bind actions:
			if (uploader.features.dragdrop) {
				uploaddiv.addClass('drag-drop');
				$('#drag-drop-area-' + index).bind('dragover.wp-uploader', function() {
					uploaddiv.addClass('drag-over');
				}).bind('dragleave.wp-uploader, drop.wp-uploader', function() {
					uploaddiv.removeClass('drag-over');
				});

			} else {
				uploaddiv.removeClass('drag-drop');
				$('#drag-drop-area-' + index).unbind('.wp-uploader');
			}
		});


		// Initiate uploading script:
		uploader.init();


		// File queue handler:
		uploader.bind('FilesAdded', function(up, files) {
			// Refresh and start:
			uploader.refresh();
			uploader.start();

			// Set sizes and hide container:
			var currentHeight = $('#uploaderSection-' + index).outerHeight();
			$('#uploaderSection-' + index).css({
				height : currentHeight
			});
			$('#plupload-upload-ui-' + index).fadeOut('medium');
			$('#uploaderSection-' + index + ' .loading').fadeIn('medium');

		});

		// A new file was uploaded:
		uploader.bind('FileUploaded', function(up, file, response) {
			// Toggle image:
			$('#current-uploaded-image-' + index).slideUp('medium', function() {

				// Parse response AS JSON:
				try {
					response = $.parseJSON(response.response);
				} catch( e ) {
					return;
				}

				field.val( response.id ).trigger( 'change' );
			});

		});

		$( '#uploadContainer-' + index ).on( 'click', 'a.attachment_helper_library_button, img.attachment-thumbnail, .wp-caption', function(e) {
			var type  = container.data( 'type' );
			var size  = container.data( 'size' );

			e.preventDefault();

			// Set frame object:
			var frame = wp.media({
				multiple : false,
				library : {
					type : type
				}
			});

			frame.on('open', function() {
				var selection = frame.state().get('selection');
				var attachment_id = field.val();
				if ( attachment_id ) {
					var attachment = wp.media.attachment(attachment_id);
					attachment.fetch();
					selection.add( attachment ? [attachment] : [] );
				}
			});

			// On select image:
			frame.on('select', function() {
				var attachment = frame.state().get('selection').first().toJSON();
				field.val( attachment.id ).trigger( 'change' );
			});

			// Display:
			frame.open();
		});


		//remove image link
		$('#remove-image-'+index ).click( function(e){
			e.preventDefault();
			field.val( '' ).trigger( 'change' );
		});


		var field_changed = function() {
			var attachment_id = field.val();
			if ( !attachment_id ) {
				AttachmentHelper.remove_preview( index );
			} else {
				AttachmentHelper.generate_preview( attachment_id, index, container.data( 'size' ) );
			}
		};

		field.bind( 'change', field_changed).trigger( 'change' );
	};

	AttachmentHelper.remove_preview = function( index ) {
		$('#current-uploaded-image-' + index).fadeOut('medium', function() {
			$('#current-uploaded-image-' + index + ' img' ).removeAttr( 'src' );
			$('#uploadContainer-' + index + ' .wp-caption' ).html( '' );
			$('#uploaderSection-' + index).fadeIn('medium');
		});
	};

	AttachmentHelper.generate_preview = function( id, index, size ) {
		if ( id == "" || id == 0 ) {
			return;
		}

		var wp_attachment = wp.media.attachment( id );
		wp_attachment.fetch().done( function() {
			var attachment = wp_attachment.toJSON();


			if (attachment.type == 'image') {
				var attachment_url = attachment.url;
				if (attachment.sizes.hasOwnProperty(size) && typeof ( attachment.sizes[size].url ) != "undefined") {
					attachment_url = attachment.sizes[size].url;
				}
				AttachmentHelper.load_image_preview(attachment_url, index, size, attachment.caption);
			} else {
				AttachmentHelper.load_text_preview(attachment.title, attachment.filename, index);
			}
		});
	};

	AttachmentHelper.load_text_preview = function( title, filename, index ) {
		var caption = '<span>'+title + ' (' + filename + ')</span>';
		$('#uploadContainer-' + index + ' .wp-caption' ).html( '<p>'+caption+'</p>' );

		// Display container:
		$('#current-uploaded-image-' + index + ' img').hide();
		$('#current-uploaded-image-' + index).fadeIn('medium');

		// Fade in upload container:
		$('div#plupload-upload-ui-' + index).show();
		$('#uploaderSection-' + index + ' .loading').hide();
		$('#uploaderSection-' + index).hide();
	};

	AttachmentHelper.load_image_preview = function( url, index, size, caption ) {
		
		// Update image with new info:
		var imageObject = $('#current-uploaded-image-' + index + ' img');

		imageObject.attr('src', url);
		imageObject.removeAttr('width');
		imageObject.removeAttr('height');
		imageObject.removeAttr('title');
		imageObject.removeAttr('alt');

		if( typeof( caption ) != "undefined" ){
			$('#uploadContainer-' + index + ' .wp-caption' ).html( '<p>'+caption+'</p>' );
		} else {
			$('#uploadContainer-' + index + ' .wp-caption' ).html( '' );
		}

		// Hide container:
		imageObject.load(function() {

			// Display container:
			imageObject.show();
			$('#current-uploaded-image-' + index).fadeIn('medium');

			// Fade in upload container:
			$('div#plupload-upload-ui-' + index).show();
			$('#uploaderSection-' + index + ' .loading').hide();
			$('#uploaderSection-' + index).hide();

		});

	};


	$(document).ready(function() {

		$('.attachment-helper-uploader').each(function() {
			var settings_name = $(this).data('settings');
			var settings = {};
			if ( AttachmentHelper.settings.hasOwnProperty(settings_name) ) {
				settings = AttachmentHelper.settings[settings_name];
				AttachmentHelper.init( $(this), settings );
			}
		});

	});

})(jQuery, AttachmentHelper);
