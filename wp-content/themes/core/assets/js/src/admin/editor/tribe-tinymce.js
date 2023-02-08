/**
 * This custom TinyMCE plugin fixes an issue where the TinyMCE floating/inline
 * toolbar (usually when editing a link in the WYSIWYG) "flickers" endlessly.
 * This is especially likely to happen when the TinyMCE editor is inside of a
 * repeater (as in the Accordion block, for example) due the restricted
 * horizontal space.
 *
 * The issue is ticketed here: https://core.trac.wordpress.org/ticket/44911.
 *
 * The issue is caused by some poor positioning logic in
 * `wp-includes/js/tinymce/plugins/wordpress/plugin.js`, which sometimes places
 * the floating toolbar slightly off-screen when positioning it next to the
 * inline element being edited. Overflowing the body this way causes horizontal
 * scrollbars to appear and triggers a `scrollwindow` event. The same plugin
 * hides the toolbar during scroll events to be repositioned over the inline
 * element being edited once the scrolling finishes. Hiding the toolbar causes
 * the scrollbars to disappear and the plugin then shows the toolbar again,
 * triggering the scrollbars, triggering the hiding of the toolbar, etc, etc.
 *
 * The fix should be rather simple, but difficult to apply. It would essentially
 * be a one-liner in the core TinyMCE Wordpress plugin, were that a normal
 * library you could patch or replace. Instead, we use this custom plugin to
 * observe the toolbar and fix the positioning in the trouble cases. TinyMCE
 * plugins are meant to be independent and "closed", so there's no way to fix
 * the errant positioning function directly. Instead we quickly recalculate and
 * reposition the element ourselves after the bad positioning has been done.
 */
( function() {
	window.tinymce.PluginManager.add( 'tribe-tinymce', function( editor ) {
		let currentSelection;

		editor.on( 'nodechange', function( event ) {
			currentSelection = event.element;
		} );

		editor.on( 'init', function() {
			window.tinymce.$( '.mce-inline-toolbar-grp:not(.tribe-tinymce-processed)' ).each( ( index, toolbar ) => {
				toolbar.classList.add( 'tribe-tinymce-processed' );

				const observer = new MutationObserver( function() {
					// Only respond when there is a current selection and the toolbar has
					// the specific class condition we need to fix.
					if ( ! currentSelection || ! toolbar.classList.contains( 'mce-arrow-left' ) ) {
						return;
					}

					// Get the current editor Iframe.
					const mceIframe = document.getElementById( window.wpActiveEditor + '_ifr' );

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
