var webpack = require('webpack');
var webpackCoreThemeConfig = require('../webpack.config.js');

module.exports = {
	options: webpackCoreThemeConfig,

	themeDev: {
		entry: './<%= pkg._corethemepath %>/js/src/index.js',
		output: {
			filename: 'scripts.js',
			path: './<%= pkg._corethemepath %>/js/dist/',
		},
		devtool: 'eval-source-map',
		debug: true,
	},

	themeProd: {
		entry: './<%= pkg._corethemepath %>/js/src/index.js',
		output: {
			filename: 'scripts.js',
			path: './<%= pkg._corethemepath %>/js/dist/',
		},
		debug: false,
		plugins: webpackCoreThemeConfig.plugins.concat(
			new webpack.DefinePlugin({
				'process.env': { NODE_ENV: JSON.stringify('production') },
			})
		),
	},
};
