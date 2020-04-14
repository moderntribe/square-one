const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );
const { scripts } = require( '../after-emit' );

module.exports = {
	theme: [
		new MiniCssExtractPlugin( {
			filename: '../../../css/dist/theme/[name].css',
		} ),
		scripts.theme,
	],
	admin: [
		new MiniCssExtractPlugin( {
			filename: '../../../css/dist/admin/[name].css',
		} ),
		scripts.admin,
	],
};
