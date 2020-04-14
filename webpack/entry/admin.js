const { square1: { paths } } = require( '../../package.json' );

module.exports = {
	scripts: [
		`./${ paths.core_admin_js_src }index.js`,
	],
};
