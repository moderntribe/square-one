var webpack = require( "webpack" );
var webpackBaseThemeConfig = require( "../webpack.config.js" );

module.exports = {
	
	options: webpackBaseThemeConfig,

	themedev: {
		entry        : './wp-content/themes/base/js/src/index.js',
		output       : {
			filename: 'scripts.js',
			path    : './wp-content/themes/base/js/dist/'
		},
		devtool: 'eval-source-map',
		debug  : true
	},

	themeprod: {
		entry        : './wp-content/themes/base/js/src/index.js',
		output       : {
			filename: 'scripts.js',
			path    : './wp-content/themes/base/js/dist/'
		},
		debug  : false,
		plugins: webpackBaseThemeConfig.plugins.concat(
			new webpack.DefinePlugin( {
				'process.env': {NODE_ENV: JSON.stringify( "production" )}
			} )
		)
	}
};
