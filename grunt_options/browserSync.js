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
				'<%= pkg._corethemepath %>/css/master.css',
				'<%= pkg._corethemepath %>/**/*.php',
				'<%= pkg._corethemepath %>/js/dist/*.js',
				'<%= pkg._corethemepath %>/img/*.jpg',
				'<%= pkg._corethemepath %>/img/*.png',

				'<%= pkg._corepluginpath %>/assets/**/*.css',
				'<%= pkg._corepluginpath %>/assets/**/*.js',
				'<%= pkg._corepluginpath %>/**/*.php',
			],
		},
		options: {
			watchTask: true,
			debugInfo: true,
			logConnections: true,
			notify: true,
			proxy: '<%= dev.proxy %>',
			ghostMode: {
				scroll: true,
				links: true,
				forms: true,
			},
		},
	},
};
