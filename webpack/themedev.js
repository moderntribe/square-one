const { resolve } = require('path');
const webpack = require('webpack');
const merge = require('webpack-merge');
const common = require('./common.js');
const rules = require('./rules.js');
const vendor = require('./vendors');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;

module.exports = merge(common, {
	mode: 'development',
	entry: {
		scripts: './wp-content/themes/keepwild/js/src/index.js',
		vendor,
	},
	output: {
		filename: '[name].js',
		chunkFilename: '[name].[chunkhash].js',
		path: resolve(`${__dirname}/../`, 'wp-content/themes/keepwild/js/dist/'),
		publicPath: '/wp-content/themes/keepwild/js/dist/',
	},
	devtool: 'eval-source-map',
	module: {
		rules: [
			rules.miniExtractPlugin,
		],
	},
	plugins: [
		new webpack.HashedModuleIdsPlugin(),
		new MiniCssExtractPlugin({
			filename: '../../css/[name].css',
		}),
		new webpack.LoaderOptionsPlugin({
			debug: true,
		}),
		new BundleAnalyzerPlugin({
			analyzerMode: 'static',
			reportFilename: resolve(`${__dirname}/../`, 'reports/webpack-bundle.html'),
			openAnalyzer: false,
		}),
	],
	optimization: {
		namedModules: true, // NamedModulesPlugin()
		splitChunks: { // CommonsChunkPlugin()
			name: 'vendor',
			minChunks: 2,
		},
		noEmitOnErrors: true, // NoEmitOnErrorsPlugin
		concatenateModules: true, //ModuleConcatenationPlugin
	},
});
