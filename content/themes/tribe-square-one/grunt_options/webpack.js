var webpack = require("webpack");
var webpackConfig = require("../webpack.config.js");

module.exports = {
	options : webpackConfig,

	dev: {
		devtool: 'eval-source-map',
		debug  : true
	},

	prod: {
		debug  : false,
		plugins: webpackConfig.plugins.concat(
			new webpack.DefinePlugin({
				'process.env': {NODE_ENV: JSON.stringify("production")}
			})
		)
	}
};
