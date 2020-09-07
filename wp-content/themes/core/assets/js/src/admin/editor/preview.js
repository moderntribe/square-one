import 'lazysizes';
import 'lazysizes/plugins/object-fit/ls.object-fit';
import 'lazysizes/plugins/parent-fit/ls.parent-fit';
import 'lazysizes/plugins/respimg/ls.respimg';
import 'lazysizes/plugins/bgset/ls.bgset';

/**
 * @function appendSinkCssClasses
 * @description Appends our kitchen sink CSS classes to the block editor --> block list container.
 */

const appendSinkCssClasses = () => {
	const editorBlockList = document.querySelector( '.is-root-container' );
	if ( editorBlockList ) {
		editorBlockList.classList.add( 't-sink', 's-sink' );
	}
};

/**
 * @function init
 * @description Initialize module
 */

const init = () => {
	appendSinkCssClasses();

	console.info( 'SquareOne Admin: Initialized scripts needed for the editor preview.' );
};

export default init;
