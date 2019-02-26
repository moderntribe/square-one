const { resolve } = require('path');
const merge = require('webpack-merge');
const common = require('./common.js');
const appCommon = require('./app-common.js');

module.exports = merge(common, {
	mode: 'development',
	entry: {
		scripts: './resources/assets/js/apps/EmailSettings/index.js',
	},
	output: {
		filename: 'email-settings-app.js',
		path: resolve(`${__dirname}/../`, 'public/js/'),
		publicPath: 'https://localhost:3000/'
	},
	...appCommon,
});
