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
				'<%= pkg._core_theme_assets_path %>/css/master.css',
				'<%= pkg._core_theme_path %>/**/*.php',
				'<%= pkg._core_theme_assets_path %>/js/dist/*.js',
				'<%= pkg._core_theme_assets_path %>/img/*.jpg',
				'<%= pkg._core_theme_assets_path %>/img/*.png',

				'<%= pkg._core_plugin_path %>/assets/**/*.css',
				'<%= pkg._core_plugin_path %>/assets/**/*.js',
				'<%= pkg._core_plugin_path %>/**/*.php',
			],
		},
		options: {
			watchTask: true,
			debugInfo: true,
			logConnections: true,
			notify: true,
			open: 'external',
			host: '<%= dev.proxy %>',
			proxy: 'https://<%= dev.proxy %>',
			https: {
				key: '<%= dev.certs_path %>/<%= dev.proxy %>.key',
				cert: '<%= dev.certs_path %>/<%= dev.proxy %>.crt'
			},
			ghostMode: {
				scroll: true,
				links: true,
				forms: true,
			},
		},
	},
};
