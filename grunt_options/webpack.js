var webpack = require('webpack');
var webpackCoreThemeConfig = require('../webpack.config.js');

module.exports = {
	options: webpackCoreThemeConfig,

	themeDev: {
		entry: {
			scripts: './<%= pkg._corethemepath %>/js/src/index.js',
			vendor: [
				'fastclick',
				'delegate',
				'verge',
				'spin.js'
			]
		},
		output: {
			filename: '[name].js',
			chunkFilename: '[name].js',
			path: './<%= pkg._corethemepath %>/js/dist/',
			publicPath: '/<%= pkg._corethemepath %>/js/dist/',
		},
		devtool: 'eval-source-map',
		plugins: webpackCoreThemeConfig.plugins.concat(
			new webpack.LoaderOptionsPlugin({
				debug: true
			}),
			new webpack.optimize.CommonsChunkPlugin({
				names: ['vendor', 'manifest']
			})
		),
	},

	themeProd: {
		entry: {
			scripts: './<%= pkg._corethemepath %>/js/src/index.js',
			vendorWebpack: [
				'fastclick',
				'delegate',
				'verge',
				'spin.js'
			]
		},
		output: {
			filename: '[name].min.js',
			chunkFilename: '[name].min.js',
			path: './<%= pkg._corethemepath %>/js/dist/',
			publicPath: '/<%= pkg._corethemepath %>/js/dist/',
		},
		plugins: webpackCoreThemeConfig.plugins.concat(
			new webpack.DefinePlugin({
				'process.env': { NODE_ENV: JSON.stringify('production') },
			}),
			new webpack.optimize.CommonsChunkPlugin({
				names: ['vendorWebpack', 'manifest']
			}),
			new webpack.LoaderOptionsPlugin({
				debug: false
			}),
			new webpack.optimize.UglifyJsPlugin({
				sourceMap: true,
				compress: {
					warnings: false,
					drop_console: true,
				}
			})
		),
	},
};
