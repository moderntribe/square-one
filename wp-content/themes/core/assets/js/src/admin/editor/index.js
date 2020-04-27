import { unregisterBlockType } from '@wordpress/blocks';
import { BLOCK_BLACKLIST } from '../config/wp-settings';

/**
 * @function removeBlackListedBlocks
 * @description Takes an array supplied on our config object and unregisters those blocks from Gutenberg
 */

const removeBlackListedBlocks = () => {
	BLOCK_BLACKLIST.map( type => unregisterBlockType( type ) );

	console.info( 'SquareOne BE: Unregistered these blocks from Gutenberg: ', BLOCK_BLACKLIST );
};

/**
 * @function init
 * @description Initialize module
 */

const init = () => {
	removeBlackListedBlocks();
};

export default init;
