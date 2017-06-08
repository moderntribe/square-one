/**
 *
 * Module: grunt-header
 * Documentation: https://github.com/sindresorhus/grunt-header
 *
 */

module.exports = {
	coreIconsStyle: {
		options: {
			text: '' +
			'/* -----------------------------------------------------------------------------\n' +
			' *\n' +
			' * Font Icons: Icons (via IcoMoon)\n' +
			' *\n' +
			' * ----------------------------------------------------------------------------- */\n' +
			'\n' +
			'/* stylelint-disable */\n'
		},
		files: {
			'<%= pkg._core_theme_assets_path %>/pcss/base/_icons.pcss': ['<%= pkg._core_theme_assets_path %>/pcss/base/_icons.pcss'],
		},
	},

	coreIconsVariables: {
		options: {
			text: '' +
			'/* -----------------------------------------------------------------------------\n' +
			' * Font Icons (via IcoMoon)\n' +
			' * ----------------------------------------------------------------------------- */\n' +
			'\n' +
			'/* stylelint-disable */\n' +
			'\n' +
			':root {'
		},
		files: {
			'<%= pkg._core_theme_assets_path %>/pcss/utilities/variables/_icons.pcss': ['<%= pkg._core_theme_assets_path %>/pcss/utilities/variables/_icons.pcss'],
		},
	},

	theme: {
		options: {
			text: '/* Core: Global CSS */',
		},
		files: {
			'<%= pkg._core_theme_assets_path %>/css/dist/master.min.css': ['<%= pkg._core_theme_assets_path %>/css/dist/master.min.css'],
		},
	},

	themePrint: {
		options: {
			text: '/* Core: Print CSS */',
		},
		files: {
			'<%= pkg._core_theme_assets_path %>/css/dist/print.min.css': ['<%= pkg._core_theme_assets_path %>/css/dist/print.min.css'],
		},
	},

	themeWPEditor: {
		options: {
			text: '/* Core: Visual Editor CSS */',
		},
		files: {
			'<%= pkg._core_theme_assets_path %>/css/admin/dist/editor-style.min.css': ['<%= pkg._core_theme_assets_path %>/css/admin/dist/editor-style.min.css'],
		},
	},

	themeWPLogin: {
		options: {
			text: '/* Core: WordPress Login CSS */',
		},
		files: {
			'<%= pkg._core_theme_assets_path %>/css/admin/dist/login.min.css': ['<%= pkg._core_theme_assets_path %>/css/admin/dist/login.min.css'],
		},
	},

	themeLegacy: {
		options: {
			text: '/* Core: Legacy Page CSS */',
		},
		files: {
			'<%= pkg._core_theme_assets_path %>/css/dist/legacy.min.css': ['<%= pkg._core_theme_assets_path %>/css/dist/legacy.min.css'],
		},
	},
};
