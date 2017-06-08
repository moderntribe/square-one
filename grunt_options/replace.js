/**
 *
 * Module: grunt-replace
 * Documentation: https://github.com/outaTiME/grunt-replace
 *
 */

module.exports = {
	coreIconsStyle: {
		options: {
			patterns: [
				{
					match: /url\('fonts\//g,
					replacement: function () {
						return 'url(\'var(--path-fonts)/icons-core/';
					}
				}
			],
		},
		files: [
			{
				expand: true,
				flatten: true,
				src: [
					'<%= pkg._core_theme_assets_path %>/pcss/base/_icons.pcss'
				],
				dest: '<%= pkg._core_theme_assets_path %>/pcss/base/'
			}
		]
	},
	coreIconsVariables: {
		options: {
			patterns: [
				{
					match: /\$/g,
					replacement: function () {
						return '--';
					}
				},
				{
					match: /\$icomoon-font-path: "fonts" !default;/g,
					replacement: function () {
						return '';
					}
				},
			],
		},
		files: [
			{
				expand: true,
				flatten: true,
				src: [
					'<%= pkg._core_theme_assets_path %>/pcss/utilities/variables/_icons.pcss'
				],
				dest: '<%= pkg._core_theme_assets_path %>/pcss/utilities/variables/'
			}
		]
	},
};
