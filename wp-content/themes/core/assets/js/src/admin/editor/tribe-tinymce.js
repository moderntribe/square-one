( function() {
	window.tinymce.PluginManager.add( 'tribe-tinymce', function( editor ) {
		let mceIframe;
		let currentSelection;

		editor.on( 'nodechange', function( event ) {
			currentSelection = event.element;
		} );

		editor.on( 'init', function() {
			mceIframe = document.getElementById( editor.id + '_ifr' );

			window.tinymce.$( '.mce-inline-toolbar-grp:not(.tribe-tinymce-processed)' ).each( ( index, toolbar ) => {
				toolbar.classList.add( 'tribe-tinymce-processed' );

				const observer = new MutationObserver( function( ) {
					// Only respond when there is a current selection and the toolbar has
					// the specific class condition we need to fix.
					if ( ! currentSelection || ! toolbar.classList.contains( 'mce-arrow-left' ) ) {
						return;
					}

					// We are essentially overriding the reposition() function in
					// wp-includes/js/tinymce/plugins/wordpress/plugin.js to prevent
					// inline toolbars from floating off the right side of the screen and
					// getting stuck in an infinite show/hide loop.
					const scrollX = window.pageXOffset || document.documentElement.scrollLeft,
						windowWidth = window.innerWidth,
						windowHeight = window.innerHeight,
						iframeRect = mceIframe ? mceIframe.getBoundingClientRect() : {
							top: 0,
							right: windowWidth,
							bottom: windowHeight,
							left: 0,
							width: windowWidth,
							height: windowHeight,
						},
						selection = currentSelection.getBoundingClientRect(),
						left = selection.left + iframeRect.left + scrollX;

					// This is the logic missing from the core plugin. If the right side
					// of the toolbar hangs off the right side of the screen, it will
					// cause an infinite loop of scroll events causing repositions causing
					// scroll events.
					if ( left + toolbar.offsetWidth >= iframeRect.left + iframeRect.width + scrollX ) {
						toolbar.classList.remove( 'mce-arrow-left' );
						toolbar.classList.add( 'mce-arrow-right' );
						toolbar.style.left = iframeRect.left + scrollX + selection.right - toolbar.offsetWidth + 'px';

						// The toolbar width may change when we change classes, so we look
						// it up again here after the browser has applied our new class.
						// Otherwise, our positioning will be off by the difference between
						// the toolbar width before and after the change.
						requestAnimationFrame( () => {
							toolbar.style.left = iframeRect.left + scrollX + selection.right - toolbar.offsetWidth + 'px';
						} );
					}
				} );

				observer.observe( toolbar, { attributes: true } );
			} );
		} );
	} );
} )();
