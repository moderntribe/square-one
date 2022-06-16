import { addFilter } from '@wordpress/hooks';

/**
 * Add some additional classes to the core column block
 * @param props
 * @param blockType
 * @returns {*}
 */
const addColumnBlockClassName = ( props, blockType ) => {
	if ( blockType.name === 'core/column' ) {
		props.className = props.className + ' t-sink s-sink';
	}

	return props;
};

const registerColumnBlock = () => {
	addFilter( 'blocks.getSaveContent.extraProps', 'tribe/columns/addCustomClasses', addColumnBlockClassName );
};

export default registerColumnBlock;
