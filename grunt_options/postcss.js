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
		require('postcss-inline-comment'),
		require('postcss-mixins'),
		require('postcss-custom-properties'),
		require('postcss-simple-vars'),
		require('postcss-custom-media'),
		require('postcss-functions')({ functions: postcssFunctions }),
		require('postcss-quantity-queries'),
		require('postcss-aspect-ratio'),
		require('postcss-cssnext')({ browsers: ['last 3 versions', 'ie 11'] }),
		require('postcss-nested'),
		require('lost'),
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
			'<%= pkg._corethemepath %>/css/master.css': '<%= pkg._corethemepath %>/pcss/master.pcss',
			'<%= pkg._corethemepath %>/css/print.css': '<%= pkg._corethemepath %>/pcss/print.pcss',
		},
	},

	themeWPEditor: {
		options: compileOptions,
		files: {
			'<%= pkg._corethemepath %>/css/admin/editor-style.css': '<%= pkg._corethemepath %>/pcss/admin/editor-style.pcss',
		},
	},

	themeWPLogin: {
		options: compileOptions,
		files: {
			'<%= pkg._corethemepath %>/css/admin/login.css': '<%= pkg._corethemepath %>/pcss/admin/login.pcss',
		},
	},

	themeLegacy: {
		options: legacyOptions,
		files: {
			'<%= pkg._corethemepath %>/css/legacy.css': '<%= pkg._corethemepath %>/pcss/legacy.pcss',
		},
	},

	// Task: Minification

	themeMin: {
		options: cssnanoOptions,
		files: {
			'<%= pkg._corethemepath %>/css/dist/master.min.css': '<%= pkg._corethemepath %>/css/master.css',
			'<%= pkg._corethemepath %>/css/dist/print.min.css': '<%= pkg._corethemepath %>/css/print.css',
		},
	},

	themeWPEditorMin: {
		options: cssnanoOptions,
		files: {
			'<%= pkg._corethemepath %>/css/admin/dist/editor-style.min.css': '<%= pkg._corethemepath %>/css/admin/editor-style.css',
		},
	},

	themeWPLoginMin: {
		options: cssnanoOptions,
		files: {
			'<%= pkg._corethemepath %>/css/admin/dist/login.min.css': '<%= pkg._corethemepath %>/css/admin/login.css',
		},
	},

	themeLegacyMin: {
		options: cssnanoOptions,
		files: {
			'<%= pkg._corethemepath %>/css/dist/legacy.min.css': '<%= pkg._corethemepath %>/css/legacy.css',
		},
	},

	// Task: Linting

	themeLint: {
		options: lintOptions,
		src: [
			'<%= pkg._corethemepath %>/pcss/**/*.pcss',
			'!<%= pkg._corethemepath %>/pcss/content/page/_legacy.pcss',
		],
	},
};
