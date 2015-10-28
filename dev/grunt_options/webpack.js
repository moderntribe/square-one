var webpackConfig = require("../webpack.config.js");

module.exports = {
	options : webpackConfig,
	themedev: {
		devtool: 'source-map',
		debug  : true
	}
};
