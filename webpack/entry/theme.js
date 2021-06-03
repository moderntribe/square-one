/**
 * Internal Dependencies
 */
const { square1: { paths } } = require( '../../package.json' );

module.exports = {
	scripts: [
		`./${ paths.core_theme_js_src }index.js`,
	],
};
