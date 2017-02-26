/**
 *
 * Module: grunt-contrib-uglify
 * Documentation: https://npmjs.org/package/grunt-contrib-uglify
 *
 */

module.exports = {
	themeMin: {
		options: {
			banner: '/* Core: JS Master */\n',
			sourceMap: false,
			compress: {
				drop_console: true,
			},
		},
		files: {
			'<%= pkg._corethemepath %>/js/dist/vendorGlobal.min.js': [
				'<%= pkg._corethemepath %>/js/vendor/polyfill.js',
				'<%= pkg._corethemepath %>/js/vendor/globals.js',
				'<%= pkg._corethemepath %>/js/vendor/ls.object-fit.js',
				'<%= pkg._corethemepath %>/js/vendor/ls.parent-fit.js',
				'<%= pkg._corethemepath %>/js/vendor/ls.respimg.js',
				'<%= pkg._corethemepath %>/js/vendor/ls.bgset.js',
				'<%= pkg._corethemepath %>/js/vendor/lazysizes.js',
			],
		},
	},
};
