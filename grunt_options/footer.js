/**
 *
 * Module: grunt-footer
 * Documentation: https://github.com/sindresorhus/grunt-footer
 *
 */

module.exports = {
	coreIconsVariables: {
		options: {
			text: '}'
		},
		files: {
			'<%= pkg._core_theme_assets_path %>/pcss/utilities/variables/_icons.pcss': ['<%= pkg._core_theme_assets_path %>/pcss/utilities/variables/_icons.pcss'],
		},
	},
};
