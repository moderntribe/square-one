var path = require('path');
var webpack = require('webpack');

module.exports = {
	cache: true,
	externals: {
		jquery: 'jQuery',
	},
	resolve: {
		alias: {
			utils: path.resolve(__dirname, 'wp-content/themes/core/js/src/utils'),
		},
		extensions: ['.js'],
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
				exclude: /node_modules\/(?!(dom7|swiper)\/).*/,
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
