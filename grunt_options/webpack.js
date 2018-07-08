const webpack = require('webpack');
const path = require('path');
const UglifyJSPlugin = require('uglifyjs-webpack-plugin');
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
		mode: 'development',
		entry: {
			scripts: './<%= pkg._core_theme_js_src_path %>index.js',
			vendor: themeVendors
		},
		output: {
			filename: '[name].js',
			chunkFilename: '[name].[chunkhash].js',
			path: path.resolve(`${__dirname}/../`, '<%= pkg._core_theme_js_dist_path %>'),
			publicPath: '/<%= pkg._core_theme_js_dist_path %>'
		},
		devtool: 'eval-source-map',
		plugins: webpackCommonConfig.plugins.concat(
			new webpack.HashedModuleIdsPlugin(),
			new webpack.LoaderOptionsPlugin({
				debug: true
			})
		),
		optimization: {
			namedModules: true, // NamedModulesPlugin()
			splitChunks: { // CommonsChunkPlugin()
				name: 'vendor',
				minChunks: 2
			},
			noEmitOnErrors: true, // NoEmitOnErrorsPlugin
			concatenateModules: true //ModuleConcatenationPlugin
		}
	},

	themeProd: {
		mode: 'production',
		entry: {
			scripts: './<%= pkg._core_theme_js_src_path %>index.js',
			vendorWebpack: themeVendors
		},
		output: {
			filename: '[name].min.js',
			chunkFilename: '[name].[chunkhash].min.js',
			path: path.resolve(`${__dirname}/../`, '<%= pkg._core_theme_js_dist_path %>'),
			publicPath: '/<%= pkg._core_theme_js_dist_path %>'
		},
		plugins: webpackCommonConfig.plugins.concat(
			new webpack.DefinePlugin({
				'process.env': { NODE_ENV: JSON.stringify('production') }
			}),
			new webpack.HashedModuleIdsPlugin(),
			new webpack.LoaderOptionsPlugin({
				debug: false
			})
		),
		optimization: {
			namedModules: true, // NamedModulesPlugin()
			splitChunks: { // CommonsChunkPlugin()
				name: 'vendor',
				minChunks: 2
			},
			noEmitOnErrors: true, // NoEmitOnErrorsPlugin
			concatenateModules: true, //ModuleConcatenationPlugin
			minimizer: [
				// we specify a custom UglifyJsPlugin here to get source maps in production
				new UglifyJSPlugin({
					cache: true,
					parallel: true,
					sourceMap: true,
					uglifyOptions: {
						compress: {
							warnings: false,
							drop_console: true,
						},
						output: {
							comments: false,
						},
					}
				})
			]
		}
	},

	adminDev: {
		mode: 'development',
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
			chunkFilename: '[name].[chunkhash].js',
			path: path.resolve(`${__dirname}/../`, '<%= pkg._core_admin_js_dist_path %>'),
			publicPath: '/<%= pkg._core_admin_js_dist_path %>'
		},
		devtool: 'eval-source-map',
		plugins: webpackCommonConfig.plugins.concat(
			new webpack.HashedModuleIdsPlugin(),
			new webpack.LoaderOptionsPlugin({
				debug: true
			})
		),
		optimization: {
			namedModules: true, // NamedModulesPlugin()
			splitChunks: { // CommonsChunkPlugin()
				name: 'vendor',
				minChunks: 2
			},
			noEmitOnErrors: true, // NoEmitOnErrorsPlugin
			concatenateModules: true //ModuleConcatenationPlugin
		}
	},

	adminProd: {
		mode: 'production',
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
			chunkFilename: '[name].[chunkhash].min.js',
			path: path.resolve(`${__dirname}/../`, '<%= pkg._core_admin_js_dist_path %>'),
			publicPath: '/<%= pkg._core_admin_js_dist_path %>'
		},
		plugins: webpackCommonConfig.plugins.concat(
			new webpack.DefinePlugin({
				'process.env': { NODE_ENV: JSON.stringify('production') },
			}),
			new webpack.HashedModuleIdsPlugin(),
			new webpack.LoaderOptionsPlugin({
				debug: false
			})
		),
		optimization: {
			namedModules: true, // NamedModulesPlugin()
			splitChunks: { // CommonsChunkPlugin()
				name: 'vendor',
				minChunks: 2
			},
			noEmitOnErrors: true, // NoEmitOnErrorsPlugin
			concatenateModules: true, //ModuleConcatenationPlugin
			minimizer: [
				// we specify a custom UglifyJsPlugin here to get source maps in production
				new UglifyJSPlugin({
					cache: true,
					parallel: true,
					sourceMap: true,
					uglifyOptions: {
						compress: {
							warnings: false,
							drop_console: true,
						},
						output: {
							comments: false,
						},
					}
				})
			]
		}
	},

	componentsDocsDev: {
		mode: 'development',
		entry: {
			scripts: './<%= pkg._components_docs_js_path %>index.js',
		},
		output: {
			filename: '[name].js',
			chunkFilename: '[name].[chunkhash].js',
			path: path.resolve(`${__dirname}/../`, '<%= pkg._components_docs_js_dist_path %>'),
			publicPath: '/<%= pkg._components_docs_js_dist_path %>'
		},
		devtool: 'eval-source-map',
		plugins: webpackCommonConfig.plugins.concat(
			new webpack.HashedModuleIdsPlugin(),
			new webpack.LoaderOptionsPlugin({
				debug: true
			})
		),
		optimization: {
			namedModules: true, // NamedModulesPlugin()
			noEmitOnErrors: true, // NoEmitOnErrorsPlugin
			concatenateModules: true //ModuleConcatenationPlugin
		}
	},

	componentsDocsProd: {
		mode: 'production',
		entry: {
			scripts: './<%= pkg._components_docs_js_path %>index.js',
		},
		output: {
			filename: '[name].min.js',
			chunkFilename: '[name].[chunkhash].min.js',
			path: path.resolve(`${__dirname}/../`, '<%= pkg._components_docs_js_dist_path %>'),
			publicPath: '/<%= pkg._components_docs_js_dist_path %>'
		},
		plugins: webpackCommonConfig.plugins.concat(
			new webpack.DefinePlugin({
				'process.env': { NODE_ENV: JSON.stringify('production') }
			}),
			new webpack.HashedModuleIdsPlugin(),
			new webpack.LoaderOptionsPlugin({
				debug: false
			})
		),
		optimization: {
			namedModules: true, // NamedModulesPlugin()
			noEmitOnErrors: true, // NoEmitOnErrorsPlugin
			concatenateModules: true, //ModuleConcatenationPlugin
			minimizer: [
				// we specify a custom UglifyJsPlugin here to get source maps in production
				new UglifyJSPlugin({
					cache: true,
					parallel: true,
					sourceMap: true,
					uglifyOptions: {
						compress: {
							warnings: false,
							drop_console: true,
						},
						output: {
							comments: false,
						},
					}
				})
			]
		}
	},
};
