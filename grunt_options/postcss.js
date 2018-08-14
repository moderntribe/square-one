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
		require('postcss-preset-env')({ stage: 0 }),
		require('postcss-calc'),
	],
};

var legacyOptions = {
	map: false,
	processors: [
		require('postcss-partial-import')({
			extension: ".pcss",
		}),
		require('postcss-mixins'),
		require('postcss-custom-properties'),
		require('postcss-simple-vars'),
		require('postcss-nested'),
		require('postcss-preset-env')({ browsers: ['last 20 versions', 'ie 6'] }),
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
			'<%= pkg._core_theme_css_path %>master.css': '<%= pkg._core_theme_pcss_path %>master.pcss',
			'<%= pkg._core_theme_css_path %>print.css': '<%= pkg._core_theme_pcss_path %>print.pcss',
		},
	},

	themeWPEditor: {
		options: compileOptions,
		files: {
			'<%= pkg._core_admin_css_path %>editor-style.css': '<%= pkg._core_admin_pcss_path %>editor-style.pcss',
		},
	},

	themeWPLogin: {
		options: compileOptions,
		files: {
			'<%= pkg._core_admin_css_path %>login.css': '<%= pkg._core_admin_pcss_path %>login.pcss',
		},
	},

	themeWPAdmin: {
		options: compileOptions,
		files: {
			'<%= pkg._core_admin_css_path %>master.css': '<%= pkg._core_admin_pcss_path %>master.pcss',
		},
	},

	themeLegacy: {
		options: legacyOptions,
		files: {
			'<%= pkg._core_theme_css_path %>legacy.css': '<%= pkg._core_theme_pcss_path %>legacy.pcss',
		},
	},

	// Task: Minification

	themeMin: {
		options: cssnanoOptions,
		files: {
			'<%= pkg._core_theme_css_dist_path %>master.min.css': '<%= pkg._core_theme_css_path %>master.css',
			'<%= pkg._core_theme_css_dist_path %>print.min.css': '<%= pkg._core_theme_css_path %>print.css',
		},
	},

	themeWPEditorMin: {
		options: cssnanoOptions,
		files: {
			'<%= pkg._core_admin_css_dist_path %>editor-style.min.css': '<%= pkg._core_admin_css_path %>editor-style.css',
		},
	},

	themeWPAdminMin: {
		options: cssnanoOptions,
		files: {
			'<%= pkg._core_admin_css_dist_path %>master.min.css': '<%= pkg._core_admin_css_path %>master.css',
		},
	},

	themeWPLoginMin: {
		options: cssnanoOptions,
		files: {
			'<%= pkg._core_admin_css_dist_path %>login.min.css': '<%= pkg._core_admin_css_path %>login.css',
		},
	},

	themeLegacyMin: {
		options: cssnanoOptions,
		files: {
			'<%= pkg._core_theme_css_dist_path %>legacy.min.css': '<%= pkg._core_theme_css_path %>legacy.css',
		},
	},

	// Task: Linting

	themeLint: {
		options: lintOptions,
		src: [
			'<%= pkg._core_theme_pcss_path %>**/*.pcss',
			'!<%= pkg._core_theme_pcss_path %>content/page/_legacy.pcss',
		],
	},
};
