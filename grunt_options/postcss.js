/**
 *
 * Module: grunt-preprocess
 * Documentation: https://npmjs.org/package/grunt-preprocess
 *
 */

module.exports = {

	// Task: Auto Prefixing

	theme_prefix: {
		options: {
			map       : false,
			processors: [
				require('autoprefixer')({browsers: ['last 3 versions', 'ie 10']})
			]
		},
		files  : {
			'css/master.css' : 'css/master-temp.css',
			'css/print.css'  : 'css/print-temp.css'
		}
	},

	theme_wp_editor_prefix: {
		options: {
			map: false,
			processors: [
				require('autoprefixer')({browsers: ['last 3 versions', 'ie 10']})
			]
		},
		files  : {
			'css/admin/editor-style.css' : 'css/admin/editor-style-temp.css'
		}
	},

	theme_wp_login_prefix: {
		options: {
			map       : false,
			processors: [
				require('autoprefixer')({browsers: ['last 3 versions', 'ie 10']})
			]
		},
		files  : {
			'css/admin/login.css' : 'css/admin/login-temp.css'
		}
	},

	theme_legacy_prefix: {
		options: {
			map       : false,
			processors: [
				require('autoprefixer')({browsers: ['last 20 versions', 'ie 6']})
			]
		},
		files  : {
			'css/legacy.css' : 'css/legacy-temp.css'
		}
	},

	// Task: Minification

	theme_min: {
		options: {
			map       : false,
			processors: [
				require('cssnano')()
			]
		},
		files  : {
			'css/dist/master.min.css' : 'css/master.css',
			'css/dist/print.min.css'  : 'css/print.css'
		}
	},

	theme_wp_editor_min: {
		options: {
			map       : false,
			processors: [
				require('cssnano')()
			]
		},
		files  : {
			'css/admin/dist/editor-style.min.css' : 'css/admin/editor-style.css'
		}
	},

	theme_wp_login_min: {
		options: {
			map       : false,
			processors: [
				require('cssnano')()
			]
		},
		files  : {
			'css/admin/dist/login.min.css' : 'css/admin/login.css'
		}
	},

	theme_legacy_min: {
		options: {
			map       : false,
			processors: [
				require('cssnano')()
			]
		},
		files  : {
			'css/dist/legacy.min.css' : 'css/legacy.css'
		}
	}

};