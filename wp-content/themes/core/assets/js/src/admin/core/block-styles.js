/**
 * @function unregisterStyles
 * @description Unregisters core block styles added client-side. Styles added
 * 				server-side using register_block_style should be unregistered
 * 				using the server-side unregister_block_styles function.
 */


const unregisterStyles = () => {
	wp.blocks.unregisterBlockStyle( 'core/image', [ 'rounded', 'default' ] );
};

export default unregisterStyles;
