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
					replacement () {
						return 'url(\'var(--path-fonts)/icons-core/';
					},
				},
			],
		},
		files: [
			{
				expand: true,
				flatten: true,
				src: [
					'<%= pkg._core_theme_pcss_path %>base/_icons.pcss',
				],
				dest: '<%= pkg._core_theme_pcss_path %>base/',
			},
		],
	},
	coreIconsVariables: {
		options: {
			patterns: [
				{
					match: /(\\[a-f0-9]+)/g,
					replacement: '"$1"',
				},
				{
					match: /\$/g,
					replacement () {
						return '--';
					},
				},
				{
					match: /\$icomoon-font-path: "fonts" !default;/g,
					replacement () {
						return '';
					},
				},
			],
		},
		files: [
			{
				expand: true,
				flatten: true,
				src: [
					'<%= pkg._core_theme_pcss_path %>utilities/variables/_icons.pcss',
				],
				dest: '<%= pkg._core_theme_pcss_path %>utilities/variables/',
			},
		],
	},
};
