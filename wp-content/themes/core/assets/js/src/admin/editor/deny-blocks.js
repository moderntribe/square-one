import { getBlockTypes, unregisterBlockType, unregisterBlockVariation } from '@wordpress/blocks';
import { BLOCK_DENYLIST } from '../config/wp-settings';

/**
 * @function removeDenyListedBlocks
 * @description Takes an array supplied on our config object and unregisters those blocks from Gutenberg after first
 * checking that they are registered in the current admin context
 */

const removeDenyListedBlocks = () => {
	const registeredBlockTypes = getBlockTypes().map( block => block.name );
	const blocksToUnregister = BLOCK_DENYLIST.filter( blockName => registeredBlockTypes.includes( blockName ) );
	const embedVariationToBlock = BLOCK_DENYLIST.filter( blockName => blockName.includes( 'embed' ) );

	if ( ! blocksToUnregister.length && ! embedVariationToBlock.length ) {
		return;
	}

	blocksToUnregister.forEach( type => unregisterBlockType( type ) );
	embedVariationToBlock.forEach( variation => unregisterBlockVariation( 'core/embed', variation.replace( 'embed/', '' ) ) );

	console.info( 'SquareOne Admin: Unregistered these blocks from Gutenberg: ', blocksToUnregister );
	console.info( 'SquareOne Admin: Unregistered these embed variations from Gutenberg: ', embedVariationToBlock );
};

/**
 * @function init
 * @description Initialize module
 */

const init = () => {
	removeDenyListedBlocks();
};

export default init;
