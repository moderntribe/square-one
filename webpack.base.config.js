
var path = require("path");
var webpack = require("webpack");

module.exports = {
	cache: true,
	entry  : './js/src/index.js',
	output : {
		filename: 'scripts.js',
		path    : './js/dist'
	},
	externals: {
		"jquery": "jQuery"
	},
	resolveLoader: {
		root: path.join(__dirname, "node_modules") }
	,
	resolve: {
		extensions: ['', '.js', '.jsx', 'json'],
		modulesDirectories: ["node_modules"],
		fallback: path.join(__dirname, "node_modules")
	},
	module: {
		loaders: [
			{
				test: /\.js$/,
				loader: 'babel-loader',
				exclude: /node_modules/
			},
			{
				include: /\.json$/,
				loaders: ["json-loader"]
			}
		]
	},
	plugins: [
		new webpack.ProvidePlugin( {
			jQuery       : "jquery",
			$            : "jquery",
			_            : "lodash"
		} )
	]
};