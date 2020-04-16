const { resolve } = require( 'path' );
const util = require( './util' );
const { square1: { paths } } = require( '../../package.json' );

module.exports = {
	theme: {
		apply: compiler => {
			compiler.hooks.afterEmit.tap( 'generate-theme-js-asset-map', async() => {
				const themeJs = resolve( `${ __dirname }/../../`, paths.core_theme_js_dist );
				await util.writeJSData( themeJs );
			} );
		},
	},
	admin: {
		apply: compiler => {
			compiler.hooks.afterEmit.tap( 'generate-admin-js-asset-map', async() => {
				const adminJs = resolve( `${ __dirname }/../../`, paths.core_admin_js_dist );
				await util.writeJSData( adminJs, 'admin' );
			} );
		},
	},
};
