import React from 'react';
import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';

/**
 * @function withStyleClassName
 * @description Higher order component that adds style classes to the outer block wrapper on init and change in the editor
 */

const withStyleClassName = createHigherOrderComponent( ( BlockListBlock ) => {
	return ( props ) => {
		// eslint-disable-next-line
		const { attributes: { className } } = props;
		return <BlockListBlock { ...props } className={ className } />;
	};
}, 'withStyleClassName' );

/**
 * @function init
 * @description Initialize module
 */

const init = () => {
	addFilter( 'editor.BlockListBlock', 'tribe/with-style-class-name', withStyleClassName );
};

export default init;
