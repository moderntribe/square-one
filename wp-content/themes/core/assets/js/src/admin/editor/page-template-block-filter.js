import { select, subscribe } from '@wordpress/data';
import { BLOCK_PAGE_TEMPLATE_FILTER } from '../config/wp-settings';
import { getBlockTypes, unregisterBlockType, registerBlockType } from '@wordpress/blocks';

const state = {
	template: 'default', // The default WordPress template
	deletedBlockMap: new Map(), // Stores the state of blocks that have been unregistered.
};

const { isTyping } = select( 'core/block-editor' );

/**
 * Registers a block if it's been unregistered.
 *
 * @param {string} block - The block name.
 */
const restoreBlock = ( block ) => {
	if ( ! state.deletedBlockMap.has( block ) ) {
		return;
	}

	registerBlockType( block, state.deletedBlockMap.get( block ) );
	state.deletedBlockMap.delete( block );
};

const updateAvailableBlocks = () => {
	const templateMap = new Map( Object.entries( BLOCK_PAGE_TEMPLATE_FILTER ) );
	const blockTypeMap = new Map( getBlockTypes().map( block => [ block.name, block ] ) );

	if ( templateMap.size < 1 ) {
		return false;
	}

	if ( templateMap.has( state.template ) ) {
		const blocks = templateMap.get( state.template );
		const restored = [];

		for ( const block of blocks ) {
			restoreBlock( block );
			restored.push( block );
		}

		// Remove the blocks assigned to this template, so they aren't unregistered.
		templateMap.delete( state.template );

		if ( restored.length ) {
			console.info( `SquareOne Admin: Restored blocks: "${ restored.join( ',' ) }" for template: ${ state.template }` );
		}
	}

	// Unregister all page template blocks that aren't for the current page template.
	const removedBlocks = [];

	templateMap.forEach( blocks => {
		for ( const block of blocks ) {
			if ( blockTypeMap.has( block ) && ! state.deletedBlockMap.get( block ) ) {
				state.deletedBlockMap.set( block, blockTypeMap.get( block ) );
				unregisterBlockType( block );
				removedBlocks.push( block );
			}
		}
	} );

	if ( removedBlocks.length ) {
		console.info( `SquareOne Admin: Removed blocks: "${ removedBlocks.join( ',' ) }"` );
	}
};

/**
 * Subscribes an event listener to determine if we need
 * to adjust the available blocks based on if the page template changed.
 */
const pageTemplateBlockFilter = () => {
	subscribe( () => {
		if ( isTyping() === true ) {
			return false;
		}

		const newTemplate = select( 'core/editor' ).getEditedPostAttribute( 'template' );

		if ( newTemplate !== undefined && newTemplate !== state.template ) {
			state.template = newTemplate;
			updateAvailableBlocks();
		}
	} );
};

/**
 * @function init
 * @description Initialize module
 */

const init = () => {
	// Set the initial block state
	updateAvailableBlocks();
	pageTemplateBlockFilter();
};

export default init;
