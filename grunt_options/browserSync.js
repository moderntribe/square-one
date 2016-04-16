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
				'css/master.css',
				'**/*.php',
				'js/dist/*.js',
				'img/*.jpg',
				'img/*.png'
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