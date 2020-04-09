/**
 * External Dependencies
 */
const { sync } = require( 'glob' );

/**
 * Internal Dependencies
 */
const { square1: { paths } } = require( '../../package.json' );
const integrations = require( './integrations' );

module.exports = {
	scripts: [
		`./${ paths.core_theme_js_src }index.js`,
	],
	master: [
		`./${ paths.core_theme_pcss }master.pcss`,
	],
	print: [
		`./${ paths.core_theme_pcss }print.pcss`,
	],
	components: [
		...sync( `./${ paths.core_theme_components }**/index.pcss` ),
	],
	legacy: [
		`./${ paths.core_theme_pcss }legacy.pcss`,
	],
	...integrations,
};
