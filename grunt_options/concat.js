/**
 *
 * Module: grunt-contrib-concat
 * Documentation: https://npmjs.org/package/grunt-contrib-concat
 *
 */

module.exports = {
	themeMinVendors: {
		src: [
			'<%= pkg._corethemepath %>/js/dist/vendorGlobal.min.js',
			'<%= pkg._corethemepath %>/js/dist/vendorWebpack.min.js'
		],
		dest: '<%= pkg._corethemepath %>/js/dist/vendor.min.js',
	}
};
