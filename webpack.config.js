var path = require('path');
var webpack = require('webpack');

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
	resolve: {
		alias: {
			masonry: 'masonry-layout',
			isotope: 'isotope-layout',
		},
		modules: [
			path.resolve(__dirname, 'wp-content/plugins/core/assets/theme/js/src'),
			path.resolve(__dirname, 'node_modules'),
		],
		extensions: ['.js', '.jsx'],
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
								["es2015", { "modules": false }] // IMPORTANT
							]
						}
					}
				]
			}
		]
	},
	plugins: [
		new webpack.ProvidePlugin({
			jQuery: 'jquery',
			$: 'jquery',
		}),
	],
};
