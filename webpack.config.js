const path = require('path');
const webpack = require('webpack');

module.exports = {
	cache: true,
	externals: {
		jquery: 'jQuery',
	},
	resolveLoader: {
		modules: [
			path.resolve(__dirname, 'node_modules'),
		],
	},
	module: {
		noParse: /node_modules\/vex-js\/dist\/js\/vex.js/,
		rules: [
			{
				test: /\.js$/,
				exclude: /node_modules/,
				use: [
					{
						loader: 'babel-loader',
						options: {
							presets: [
								['es2015', { modules: false }], // IMPORTANT
							],
						},
					},
				],
			},
		],
	},
	plugins: [
		new webpack.ProvidePlugin({
			jQuery: 'jquery',
			$: 'jquery',
		}),
	],
};
