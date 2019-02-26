const fs = require('fs');
const concat = require('concat');
const { resolve, sep } = require('path');
const webpack = require('webpack');
const merge = require('webpack-merge');
const common = require('./common.js');
const rules = require('./rules.js');
const vendor = require('./vendors');
const EventHooksPlugin = require('event-hooks-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = merge(common, {
	mode: 'development',
	entry: {
		scripts: './resources/assets/js/index.js',
		vendor,
	},
	output: {
		filename: '[name].js',
		chunkFilename: '[name].[chunkhash].js',
		path: resolve(`${__dirname}/../`, 'public/js/'),
		publicPath: '/js/'
	},
	devtool: 'eval-source-map',
	module: {
		rules: [
			rules.miniExtractPlugin,
		],
	},
	plugins: [
		new EventHooksPlugin({
			beforeRun: () => {
				// delete all files in js public dir
				const path = resolve(`${__dirname}/../`, 'public/js/');
				fs.readdir(path, (error, files) => {
					console.log('\n');
					if (error) throw error;
					files.forEach(file => fs.unlink(`${path}${sep}${file}`, (err) => {
						if (file === 'scripts.js') {
							return;
						}
						if (err) throw err;
						console.log(`${path}${sep}${file} was deleted`);
					}));
					console.log('\n');
				});
			},
			done: () => {
				const nodePath = resolve(`${__dirname}/../`, 'node_modules');
				const path = resolve(`${__dirname}/../`, 'public/js/');
				const fpath = resolve(`${__dirname}/../`, 'resources/assets/foundation-custom');
				concat( [
					`${nodePath}/jquery/dist/jquery.js`,
					`${fpath}/foundation.js`,
				], `${path}${sep}vendor.js` );
				fs.copyFile(`${nodePath}/es6-promise/dist/es6-promise.auto.js`, `${path}${sep}es6-promise.auto.js`, (err) => {
					if (err) throw err;
				});
			}
		}),
		new webpack.HashedModuleIdsPlugin(),
		new MiniCssExtractPlugin({
			filename: '../css/[name].css',
		}),
		new webpack.LoaderOptionsPlugin({
			debug: true
		})
	],
	optimization: {
		namedModules: true, // NamedModulesPlugin()
		splitChunks: { // CommonsChunkPlugin()
			name: 'vendor',
			minChunks: 2
		},
		noEmitOnErrors: true, // NoEmitOnErrorsPlugin
		concatenateModules: true //ModuleConcatenationPlugin
	}
});
