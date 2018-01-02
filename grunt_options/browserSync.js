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
				'<%= pkg._core_theme_css_path %>master.css',
				'<%= pkg._core_admin_css_path %>master.css',
				'<%= pkg._core_admin_css_path %>login.css',
				'<%= pkg._core_admin_css_path %>editor-style.css',
				'<%= pkg._core_theme_path %>/**/*.php',
				'<%= pkg._core_theme_path %>/**/*.twig',
				'<%= pkg._core_theme_js_dist_path %>*.js',
				'<%= pkg._core_admin_js_dist_path %>*.js',
				'<%= pkg._core_theme_img_path %>**/*.jpg',
				'<%= pkg._core_admin_img_path %>**/*.jpg',

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
			proxy: '<%= dev.proxy %>',
			ghostMode: {
				scroll: true,
				links: true,
				forms: true,
			},
		},
	},
	devDocker: {
		bsFiles: {
			src: [
				'<%= pkg._core_theme_css_path %>master.css',
				'<%= pkg._core_admin_css_path %>master.css',
				'<%= pkg._core_admin_css_path %>login.css',
				'<%= pkg._core_admin_css_path %>editor-style.css',
				'<%= pkg._core_theme_path %>/**/*.php',
				'<%= pkg._core_theme_path %>/**/*.twig',
				'<%= pkg._core_theme_js_dist_path %>*.js',
				'<%= pkg._core_admin_js_dist_path %>*.js',
				'<%= pkg._core_theme_img_path %>**/*.jpg',
				'<%= pkg._core_admin_img_path %>**/*.jpg',

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
			host: '<%= dev.proxy %>', /* https://192.168.1.199:3000/ will still work for mobile device testing */
			proxy: 'https://<%= dev.proxy %>',
			https: {
				key: '<%= dev.certs_path %>/<%= dev.proxy %>.key',
				cert: '<%= dev.certs_path %>/<%= dev.proxy %>.crt',
			},
			ghostMode: {
				scroll: true,
				links: true,
				forms: true,
			},
		},
	},
};
