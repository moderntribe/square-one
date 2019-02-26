const TerserPlugin = require('terser-webpack-plugin');
const { resolve, sep } = require('path');
const concat = require('concat');
const webpack = require('webpack');
const common = require('./common.js');
const rules = require('./rules.js');
const vendor = require('./vendors');
const merge = require('webpack-merge');
const UglifyJSPlugin = require('uglifyjs-webpack-plugin');
const EventHooksPlugin = require('event-hooks-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const OptimizeCSSAssetsPlugin = require("optimize-css-assets-webpack-plugin");

module.exports = merge(common, {
	mode: 'production',
	entry: {
		scripts: './resources/assets/js/index.js',
		vendor,
	},
	output: {
		filename: '[name].min.js',
		chunkFilename: '[name].[chunkhash].min.js',
		path: resolve(`${__dirname}/../`, 'public/js/'),
		publicPath: '/js/'
	},
	module: {
		rules: [
			rules.miniExtractPlugin,
		],
	},
	plugins: [
		new EventHooksPlugin({
			done: () => {
				const nodePath = resolve(`${__dirname}/../`, 'node_modules');
				const path = resolve(`${__dirname}/../`, 'public/js/');
				concat( [
					`${nodePath}/jquery/dist/jquery.min.js`,
					`${nodePath}/foundation-sites/dist/js/foundation.min.js`,
				], `${path}${sep}vendor.min.js` );
			}
		}),
		new webpack.DefinePlugin({
			'process.env': { NODE_ENV: JSON.stringify('production') }
		}),
		new webpack.HashedModuleIdsPlugin(),
		new webpack.LoaderOptionsPlugin({
			debug: false
		}),
		new MiniCssExtractPlugin({
			filename: '../css/[name].min.css',
		})
	],
	optimization: {
		namedModules: true, // NamedModulesPlugin()
		splitChunks: { // CommonsChunkPlugin()
			name: 'vendor',
			minChunks: 2,
		},
		noEmitOnErrors: true, // NoEmitOnErrorsPlugin
		concatenateModules: true, //ModuleConcatenationPlugin
		minimizer: [
			new TerserPlugin({
				cache: true,
				parallel: true,
				sourceMap: true, // Must be set to true if using source-maps in production
				terserOptions: {
					compress: {
						warnings: false,
						drop_console: true,
					},
					output: {
						comments: false,
					},
				}
			}),
			new OptimizeCSSAssetsPlugin({})
		]
	}
});
