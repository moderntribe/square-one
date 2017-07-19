var webpack = require('webpack');
var path = require('path');
var webpackCoreThemeConfig = require('../webpack.config.js');

module.exports = {
	options: webpackCoreThemeConfig,

	themeDev: {
		entry: {
			scripts: './<%= pkg._core_theme_assets_path %>/js/src/index.js',
			vendor: [
				'delegate',
				'verge',
				'spin.js'
			]
		},
		output: {
			filename: '[name].js',
			chunkFilename: '[name].js',
			path: path.resolve(__dirname + '/../', '<%= pkg._core_theme_assets_path %>/js/dist/'),
			publicPath: '/<%= pkg._core_theme_assets_path %>/js/dist/',
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
			scripts: './<%= pkg._core_theme_assets_path %>/js/src/index.js',
			vendorWebpack: [
				'delegate',
				'verge',
				'spin.js'
			]
		},
		output: {
			filename: '[name].min.js',
			chunkFilename: '[name].min.js',
			path: path.resolve(__dirname + '/../', '<%= pkg._core_theme_assets_path %>/js/dist/'),
			publicPath: '/<%= pkg._core_theme_assets_path %>/js/dist/',
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
