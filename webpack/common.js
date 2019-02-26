const path = require('path');
const webpack = require('webpack');
const resolve = require('./resolve');

module.exports = {
	cache: true,
	externals: {
		jquery: 'jQuery',
	},
	resolve,
	resolveLoader: {
		modules: [
			path.resolve(`${__dirname}/../`, 'node_modules'),
		],
	},
	module: {
		noParse: /node_modules\/vex-js\/dist\/js\/vex.js/,
		exprContextCritical: false,
		rules: [
			{
				test: /\.js$/,
				exclude: [/(node_modules)(?![/|\\](dom7|swiper))/],
				use: [
					{
						loader: 'babel-loader',
					},
				],
			},
		],
	},
	plugins: [
		new webpack.IgnorePlugin(/^\.\/locale$/, /moment$/),
		new webpack.ProvidePlugin({
			jQuery: 'jquery',
			$: 'jquery',
		})
	],
};
