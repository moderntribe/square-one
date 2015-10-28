/**
 *
 * Module: grunt-browser-sync
 * Documentation: https://npmjs.org/package/grunt-contrib-clean
 *
 */

module.exports = {

	dev: {
		bsFiles: {
			src: [
				'<%= pkg._themepath %>/css/master.css',
				'<%= pkg._themepath %>/css/print.css',
				'<%= pkg._themepath %>/css/admin/editor-style.css',
				'<%= pkg._themepath %>/css/admin/login.css',
				'<%= pkg._themepath %>/**/*.php',
				'<%= pkg._themepath %>/js/dist/scripts.js',
				'<%= pkg._themepath %>/js/scripts.js',
				'<%= pkg._themepath %>/js/libs.js',
				'<%= pkg._themepath %>/js/templates.js',
				'<%= pkg._themepath %>/img/**/*.jpg',
				'<%= pkg._themepath %>/img/**/*.png'
			]
		},
		options: {
			watchTask     : true,
			debugInfo     : true,
			logConnections: true,
			notify        : true,
			proxy         : '<%= dev.proxy %>',
			ghostMode     : {
				scroll: true,
				links : true,
				forms : true
			}
		}
	}

};