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
			path.resolve('./wp-content/plugins/core/assets/theme/js/src'),
			path.resolve(__dirname, 'node_modules'),
		],
		extensions: ['.js', '.jsx'],
	},
	module: {
		noParse: /node_modules\/vex-js\/dist\/js\/vex.js/,
		loaders: [
			{
				test: /\.js$/,
				loader: 'babel-loader',
				exclude: /node_modules/,
			}
		],
	},
	plugins: [
		new webpack.ProvidePlugin({
			jQuery: 'jquery',
			$: 'jquery',
		}),
	],
};
