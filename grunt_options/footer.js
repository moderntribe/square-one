/**
 *
 * Module: grunt-footer
 * Documentation: https://github.com/sindresorhus/grunt-footer
 *
 */

module.exports = {
	coreIconsVariables: {
		options: {
			text: '}',
		},
		files: {
			'<%= pkg._core_theme_pcss_path %>utilities/variables/_icons.pcss': ['<%= pkg._core_theme_pcss_path %>utilities/variables/_icons.pcss'],
		},
	},
};
