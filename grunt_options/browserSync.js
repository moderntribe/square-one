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
				'<%= pkg._basethemepath %>/css/master.css',
				'<%= pkg._basethemepath %>/**/*.php',
				'<%= pkg._basethemepath %>/js/dist/*.js',
				'<%= pkg._basethemepath %>/img/*.jpg',
				'<%= pkg._basethemepath %>/img/*.png',

				'<%= pkg._corepluginpath %>/assets/**/*.css',
				'<%= pkg._corepluginpath %>/assets/**/*.js',
				'<%= pkg._corepluginpath %>/**/*.php'
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