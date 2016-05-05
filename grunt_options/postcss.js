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
		require('postcss-partial-import')({ extension: 'pcss', }),
		require('postcss-inline-comment'),
		require('postcss-mixins'),
		require('postcss-custom-properties'),
		require('postcss-simple-vars'),
		require('postcss-custom-media'),
		require('postcss-functions')({ functions: postcssFunctions }),
		require('lost'),
		require('postcss-quantity-queries'),
		require('postcss-aspect-ratio'),
		require('postcss-cssnext')({ browsers: ['last 3 versions', 'ie 10'] }),
		require('postcss-nested'),
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

module.exports = {

	theme: {
		options: compileOptions,
		files: {
			'<%= pkg._corethemepath %>/css/master.css': '<%= pkg._corethemepath %>/pcss/master.pcss',
			'<%= pkg._corethemepath %>/css/print.css': '<%= pkg._corethemepath %>/pcss/print.pcss',
		},
	},

	theme_wp_editor: {
		options: compileOptions,
		files: {
			'<%= pkg._corethemepath %>/css/admin/editor-style.css': '<%= pkg._corethemepath %>/pcss/admin/editor-style.pcss',
		},
	},

	theme_wp_login: {
		options: compileOptions,
		files: {
			'<%= pkg._corethemepath %>/css/admin/login.css': '<%= pkg._corethemepath %>/pcss/admin/login.pcss',
		},
	},

	theme_legacy: {
		options: legacyOptions,
		files: {
			'<%= pkg._corethemepath %>/css/legacy.css': '<%= pkg._corethemepath %>/pcss/legacy.pcss',
		},
	},

	// Task: Minification

	theme_min: {
		options: cssnanoOptions,
		files: {
			'<%= pkg._corethemepath %>/css/dist/master.min.css': '<%= pkg._corethemepath %>/css/master.css',
			'<%= pkg._corethemepath %>/css/dist/print.min.css': '<%= pkg._corethemepath %>/css/print.css',
		},
	},

	theme_wp_editor_min: {
		options: cssnanoOptions,
		files: {
			'<%= pkg._corethemepath %>/css/admin/dist/editor-style.min.css': '<%= pkg._corethemepath %>/css/admin/editor-style.css',
		},
	},

	theme_wp_login_min: {
		options: cssnanoOptions,
		files: {
			'<%= pkg._corethemepath %>/css/admin/dist/login.min.css': '<%= pkg._corethemepath %>/css/admin/login.css',
		},
	},

	theme_legacy_min: {
		options: cssnanoOptions,
		files: {
			'<%= pkg._corethemepath %>/css/dist/legacy.min.css': '<%= pkg._corethemepath %>/css/legacy.css',
		},
	},
};
