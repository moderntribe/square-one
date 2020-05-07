import hooks from './hooks';
import types from './types';

/**
 * @function init
 * @description Initialize module
 */

const init = () => {
	hooks();
	types();
};

export default init;
