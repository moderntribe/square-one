/**
 *
 * Module: grunt-contrib-uglify
 * Documentation: https://npmjs.org/package/grunt-contrib-uglify
 *
 */

module.exports = {
	theme_min: {
		options: {
			banner: '/* Square One: JS Master */\n',
			sourceMap: false,
			compress: {
				drop_console: true,
			},
		},
		files: {
			'<%= pkg._corethemepath %>/js/dist/master.min.js': [
				'<%= pkg._corethemepath %>/js/vendor/polyfill.js',
				'<%= pkg._corethemepath %>/js/vendor/globals.js',
				'<%= pkg._corethemepath %>/js/dist/scripts.js',
			],
		},
	},
};
