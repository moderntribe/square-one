const path = require( 'path' );
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );
const WatchExternalFilesPlugin = require( 'webpack-watch-files-plugin' ).default;
const { square1: { paths } } = require( '../../package.json' );
const { scripts } = require( '../after-emit' );

module.exports = {
	theme: [
		new MiniCssExtractPlugin( {
			filename: '../../../css/dist/theme/[name].css',
		} ),
		new WatchExternalFilesPlugin( {
			files: [
				path.resolve( `${ __dirname }/../../`, `${ paths.core_theme_js_common }**/*.js` ),
				path.resolve( `${ __dirname }/../../`, `${ paths.core_theme_js_util }**/*.js` ),
			],
		} ),
		scripts.theme,
	],
	admin: [
		new MiniCssExtractPlugin( {
			filename: '../../../css/dist/admin/[name].css',
		} ),
		new WatchExternalFilesPlugin( {
			files: [
				path.resolve( `${ __dirname }/../../`, `${ paths.core_theme_js_common }**/*.js` ),
				path.resolve( `${ __dirname }/../../`, `${ paths.core_theme_js_util }**/*.js` ),
			],
		} ),
		scripts.admin,
	],
};
