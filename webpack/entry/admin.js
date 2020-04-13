const { square1: { paths } } = require( '../../package.json' );

module.exports = {
	'scripts': [
		`./${ paths.core_admin_js_src }index.js`,
	],
	'master': [
		`./${ paths.core_admin_pcss }master.pcss`,
	],
	'login': [
		`./${ paths.core_admin_pcss }login.pcss`,
	],
	'editor-style': [
		`./${ paths.core_admin_pcss }editor-style.pcss`,
	],
};
