var webpack = require( "webpack" );
var webpackCoreThemeConfig = require( "../webpack.config.js" );

module.exports = {
	
	options: webpackCoreThemeConfig,

	themedev: {
		entry        : './wp-content/themes/core/js/src/index.js',
		output       : {
			filename: 'scripts.js',
			path    : './wp-content/themes/core/js/dist/'
		},
		devtool: 'eval-source-map',
		debug  : true
	},

	themeprod: {
		entry        : './wp-content/themes/core/js/src/index.js',
		output       : {
			filename: 'scripts.js',
			path    : './wp-content/themes/core/js/dist/'
		},
		debug  : false,
		plugins: webpackCoreThemeConfig.plugins.concat(
			new webpack.DefinePlugin( {
				'process.env': {NODE_ENV: JSON.stringify( "production" )}
			} )
		)
	}
};
