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
					'<%= pkg._npm_path %>/babel-polyfill/dist/polyfill.js',
					'<%= pkg._npm_path %>/jquery/dist/jquery.js',
					'<%= pkg._npm_path %>/jquery/dist/jquery.min.js',
					'<%= pkg._npm_path %>/jquery/dist/jquery.min.map',
					'<%= pkg._component_path %>/theme/js/globals.js',
					'<%= pkg._npm_path %>/lazysizes/plugins/object-fit/ls.object-fit.js',
					'<%= pkg._npm_path %>/lazysizes/plugins/parent-fit/ls.parent-fit.js',
					'<%= pkg._npm_path %>/lazysizes/plugins/respimg/ls.respimg.js',
					'<%= pkg._npm_path %>/lazysizes/plugins/bgset/ls.bgset.js',
					'<%= pkg._npm_path %>/lazysizes/lazysizes.js',
					'<%= pkg._npm_path %>/tota11y/build/tota11y.min.js',
					'<%= pkg._npm_path %>/webfontloader/webfontloader.js',
				],
				dest: '<%= pkg._core_theme_assets_path %>/js/vendor/',
			},
		],
	}
};
