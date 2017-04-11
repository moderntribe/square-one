/**
 *
 * Module: grunt-preprocess
 * Documentation: https://npmjs.org/package/grunt-preprocess
 *
 */

var postcssFunctions = require('../dev_components/theme/pcss/functions');

var compileOptions = {
	map: true,
	processors: [
		require('postcss-partial-import')({
			extension: ".pcss",
		}),
		require('postcss-mixins'),
		require('postcss-custom-properties'),
		require('postcss-simple-vars'),
		require('postcss-custom-media'),
		require('postcss-functions')({ functions: postcssFunctions }),
		require('postcss-quantity-queries'),
		require('postcss-aspect-ratio'),
		require('postcss-nested'),
		require('lost'),
		require('postcss-inline-svg'),
		require('postcss-cssnext'),
	],
};

var legacyOptions = {
	map: false,
	processors: [
		require('postcss-partial-import')({ extension: 'pcss', }),
		require('postcss-mixins'),
		require('postcss-custom-properties'),
		require('postcss-simple-vars'),
		require('postcss-nested'),
		require('postcss-cssnext')({ browsers: ['last 20 versions', 'ie 6'] }),
	],
};

var cssnanoOptions = {
	map: false,
	processors: [
		require('cssnano')({ zindex: false }),
	],
};

var lintOptions = {
	processors: [
		require('stylelint'),
		require('postcss-reporter')({ clearMessages: true, throwError: true, plugins: ['stylelint'], }),
	],
};

module.exports = {
	theme: {
		options: compileOptions,
		files: {
			'<%= pkg._core_theme_assets_path %>/css/master.css': '<%= pkg._core_theme_assets_path %>/pcss/master.pcss',
			'<%= pkg._core_theme_assets_path %>/css/print.css': '<%= pkg._core_theme_assets_path %>/pcss/print.pcss',
		},
	},

	themeWPEditor: {
		options: compileOptions,
		files: {
			'<%= pkg._core_theme_assets_path %>/css/admin/editor-style.css': '<%= pkg._core_theme_assets_path %>/pcss/admin/editor-style.pcss',
		},
	},

	themeWPLogin: {
		options: compileOptions,
		files: {
			'<%= pkg._core_theme_assets_path %>/css/admin/login.css': '<%= pkg._core_theme_assets_path %>/pcss/admin/login.pcss',
		},
	},

	themeLegacy: {
		options: legacyOptions,
		files: {
			'<%= pkg._core_theme_assets_path %>/css/legacy.css': '<%= pkg._core_theme_assets_path %>/pcss/legacy.pcss',
		},
	},

	// Task: Minification

	themeMin: {
		options: cssnanoOptions,
		files: {
			'<%= pkg._core_theme_assets_path %>/css/dist/master.min.css': '<%= pkg._core_theme_assets_path %>/css/master.css',
			'<%= pkg._core_theme_assets_path %>/css/dist/print.min.css': '<%= pkg._core_theme_assets_path %>/css/print.css',
		},
	},

	themeWPEditorMin: {
		options: cssnanoOptions,
		files: {
			'<%= pkg._core_theme_assets_path %>/css/admin/dist/editor-style.min.css': '<%= pkg._core_theme_assets_path %>/css/admin/editor-style.css',
		},
	},

	themeWPLoginMin: {
		options: cssnanoOptions,
		files: {
			'<%= pkg._core_theme_assets_path %>/css/admin/dist/login.min.css': '<%= pkg._core_theme_assets_path %>/css/admin/login.css',
		},
	},

	themeLegacyMin: {
		options: cssnanoOptions,
		files: {
			'<%= pkg._core_theme_assets_path %>/css/dist/legacy.min.css': '<%= pkg._core_theme_assets_path %>/css/legacy.css',
		},
	},

	// Task: Linting

	themeLint: {
		options: lintOptions,
		src: [
			'<%= pkg._core_theme_assets_path %>/pcss/**/*.pcss',
			'!<%= pkg._core_theme_assets_path %>/pcss/content/page/_legacy.pcss',
		],
	},
};
