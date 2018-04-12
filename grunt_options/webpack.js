const webpack = require('webpack');
const path = require('path');
const webpackCommonConfig = require('../webpack.config.js');

const themeVendors = [
	'delegate',
	'verge',
	'spin.js',
	'swiper',
];

const adminVendors = [
	'delegate',
	'verge',
	'spin.js',
];

module.exports = {
	options: webpackCommonConfig,

	themeDev: {
		entry: {
			scripts: './<%= pkg._core_theme_js_src_path %>index.js',
			vendor: themeVendors
		},
		output: {
			filename: '[name].js',
			chunkFilename: '[name].js',
			path: path.resolve(`${__dirname}/../`, '<%= pkg._core_theme_js_dist_path %>'),
			publicPath: '/<%= pkg._core_theme_js_dist_path %>'
		},
		devtool: 'eval-source-map',
		plugins: webpackCommonConfig.plugins.concat(
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
			scripts: './<%= pkg._core_theme_js_src_path %>index.js',
			vendorWebpack: themeVendors
		},
		output: {
			filename: '[name].min.js',
			chunkFilename: '[name].min.js',
			path: path.resolve(`${__dirname}/../`, '<%= pkg._core_theme_js_dist_path %>'),
			publicPath: '/<%= pkg._core_theme_js_dist_path %>'
		},
		plugins: webpackCommonConfig.plugins.concat(
			new webpack.DefinePlugin({
				'process.env': { NODE_ENV: JSON.stringify('production') }
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
					drop_console: true
				},
				output: {
					comments: false,
				},
			})
		),
	},

	adminDev: {
		entry: {
			scripts: './<%= pkg._core_admin_js_src_path %>index.js',
			vendor: adminVendors
		},
		resolve: {
			modules: [
				'./<%= pkg._core_admin_js_src_path %>',
				'./<%= pkg._npm_path %>',
			],
			extensions: ['.js', '.jsx']
		},
		output: {
			filename: '[name].js',
			chunkFilename: '[name].js',
			path: path.resolve(`${__dirname}/../`, '<%= pkg._core_admin_js_dist_path %>'),
			publicPath: '/<%= pkg._core_admin_js_dist_path %>'
		},
		devtool: 'eval-source-map',
		plugins: webpackCommonConfig.plugins.concat(
			new webpack.LoaderOptionsPlugin({
				debug: true
			}),
			new webpack.optimize.CommonsChunkPlugin({
				names: ['vendor', 'manifest']
			})
		),
	},

	adminProd: {
		entry: {
			scripts: './<%= pkg._core_admin_js_src_path %>index.js',
			vendor: adminVendors
		},
		resolve: {
			modules: [
				'./<%= pkg._core_admin_js_src_path %>',
				'./<%= pkg._npm_path %>',
			],
			extensions: ['.js', '.jsx']
		},
		output: {
			filename: '[name].min.js',
			chunkFilename: '[name].min.js',
			path: path.resolve(`${__dirname}/../`, '<%= pkg._core_admin_js_dist_path %>'),
			publicPath: '/<%= pkg._core_admin_js_dist_path %>'
		},
		plugins: webpackCommonConfig.plugins.concat(
			new webpack.DefinePlugin({
				'process.env': { NODE_ENV: JSON.stringify('production') },
			}),
			new webpack.optimize.CommonsChunkPlugin({
				names: ['vendor', 'manifest']
			}),
			new webpack.LoaderOptionsPlugin({
				debug: false
			}),
			new webpack.optimize.UglifyJsPlugin({
				sourceMap: true,
				compress: {
					warnings: false,
					drop_console: true,
				},
				output: {
					comments: false,
				},
			})
		),
	},
};
