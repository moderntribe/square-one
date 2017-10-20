/**
 *
 * Module: grunt-contrib-copy
 * Documentation: https://github.com/gruntjs/grunt-contrib-copy
 * Example:
 *
 */

module.exports = {
	coreIconsFonts: {
		files: [
			{
				expand: true,
				flatten: true,
				src: [
					'<%= pkg._component_path %>/theme/icons/core/fonts/*',
				],
				dest: '<%= pkg._core_theme_fonts_path %>icons-core/',
			},
		],
	},

	coreIconsStyles: {
		files: [
			{
				expand: true,
				flatten: true,
				src: [
					'<%= pkg._component_path %>/theme/icons/core/style.css',
				],
				dest: '<%= pkg._core_theme_pcss_path %>base/',
				rename: function(dest, src) {
					return dest + src.replace('style.css', '_icons.pcss');
				},
			},
		],
	},

	coreIconsVariables: {
		files: [
			{
				expand: true,
				flatten: true,
				src: [
					'<%= pkg._component_path %>/theme/icons/core/variables.scss',
				],
				dest: '<%= pkg._core_theme_pcss_path %>utilities/variables/',
				rename: function(dest, src) {
					return dest + src.replace('variables.scss', '_icons.pcss');
				},
			},
		],
	},

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
				dest: '<%= pkg._core_theme_js_vendor_path %>',
			},
		],
	}
};
