/**
 *
 * Module: grunt-contrib-copy
 * Documentation: https://github.com/gruntjs/grunt-contrib-copy
 * Example:
 *
 */

module.exports = {
	themeJS: {
		files: [
			{
				expand: true,
				flatten: true,
				src: [
					'<%= pkg._npmpath %>/babel-polyfill/dist/polyfill.js',
					'<%= pkg._npmpath %>/jquery/dist/jquery.js',
					'<%= pkg._npmpath %>/jquery/dist/jquery.min.js',
					'<%= pkg._npmpath %>/jquery/dist/jquery.min.map',
					'<%= pkg._componentpath %>/theme/js/globals.js',
					'<%= pkg._npmpath %>/lazysizes/plugins/object-fit/ls.object-fit.js',
					'<%= pkg._npmpath %>/lazysizes/plugins/parent-fit/ls.parent-fit.js',
					'<%= pkg._npmpath %>/lazysizes/plugins/respimg/ls.respimg.js',
					'<%= pkg._npmpath %>/lazysizes/plugins/bgset/ls.bgset.js',
					'<%= pkg._npmpath %>/lazysizes/lazysizes.js',
					'<%= pkg._npmpath %>/tota11y/build/tota11y.min.js',
					'<%= pkg._npmpath %>/webfontloader/webfontloader.js',
				],
				dest: '<%= pkg._corethemepath %>/js/vendor/',
			},
		],
	},
};
