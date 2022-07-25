import { BLOCK_STYLE_DENYLIST } from '../config/wp-settings';
import { unregisterBlockStyle } from '@wordpress/blocks';

/**
 * @function unregisterStyles
 * @description Unregisters core block styles.
 */

const unregisterStyles = () => {
	const blockStyles = [];

	for ( const [ block, styles ] of Object.entries( BLOCK_STYLE_DENYLIST ) ) {
		unregisterBlockStyle( block, styles );
		blockStyles.push( `${ block }:${ styles.toString() }` );
	}

	if ( ! blockStyles.length ) {
		console.info( 'SquareOne Admin: No block styles to unregister from Gutenberg.' );
		return;
	}

	console.info( 'SquareOne Admin: Unregistered these styles from Gutenberg: ', blockStyles );
};

export default unregisterStyles;
