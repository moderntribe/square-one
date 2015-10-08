
var path = require("path");
var webpack = require("webpack");

module.exports = {
	cache: true,
	entry  : './<%= pkg._themepath %>/js/src/index.js',
	output : {
		filename: 'scripts.js',
		path    : '<%= pkg._themepath %>/js/dist'
	},
	resolveLoader: {
		root: path.join(__dirname, "node_modules") }
	,
	resolve: {
		extensions: ['', '.js', '.jsx'],
		modulesDirectories: ["./dev/node_modules", "./dev/bower_components"]
	},
	module: {
		loaders: [
			{
				test: /\.js$/,
				loader: 'babel',
				exclude: /node_modules/
			}
		]
	},
	externals: {
		"jquery": "jQuery"
	},
	plugins: [
		new webpack.ProvidePlugin( {
			gsap  : "gsap",
			_     : "lodash"
		} )
	]
};