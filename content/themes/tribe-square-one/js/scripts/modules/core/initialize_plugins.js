/**
 * @function modern_tribe.core.initialize_plugins
 * @desc Function to wrap all simple third part plugin inits.
 * Please don't do complex configs here, make a module.
 */

t.core.initialize_plugins = function() {

	FastClick.attach( document.body );

};