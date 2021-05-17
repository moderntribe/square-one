const { resolve } = require( 'path' );
const util = require( './util' );
const { square1: { paths } } = require( '../../package.json' );

module.exports = {
	apply: compiler => {
		compiler.hooks.afterEmit.tap( 'generate-css-asset-maps', async() => {
			const themeCss = resolve( `${ __dirname }/../../`, paths.core_theme_css_dist );
			const adminCss = resolve( `${ __dirname }/../../`, paths.core_admin_css_dist );
			await util.writeCSSData( themeCss );
			await util.writeCSSData( adminCss, 'admin' );
		} );
	},
};
