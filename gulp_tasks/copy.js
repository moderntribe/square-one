const gulp = require( 'gulp' );
const rename = require( 'gulp-rename' );
const pkg = require( '../package.json' );

module.exports = {
	coreIconsFonts() {
		return gulp
			.src( [
				`${ pkg._component_path }/theme/icons/core/fonts/*`,
			] )
			.pipe( gulp.dest( `${ pkg._core_theme_fonts_path }icons-core/` ) );
	},
	coreIconsStyles() {
		return gulp
			.src( [
				`${ pkg._component_path }/theme/icons/core/style.css`,
			] )
			.pipe( rename( '_icons.pcss' ) )
			.pipe( gulp.dest( `${ pkg._core_theme_pcss_path }base/` ) );
	},
	coreIconsVariables() {
		return gulp
			.src( [
				`${ pkg._component_path }/theme/icons/core/variables.scss`,
			] )
			.pipe( rename( '_icons.pcss' ) )
			.pipe( gulp.dest( `${ pkg._core_theme_pcss_path }utilities/variables/` ) );
	},
	themeJS() {
		return gulp
			.src( [
				`${ pkg._npm_path }/jquery/dist/jquery.js`,
				`${ pkg._npm_path }/jquery/dist/jquery.min.js`,
				`${ pkg._npm_path }/jquery/dist/jquery.min.map`,
				`${ pkg._component_path }/theme/js/globals.js`,
				`${ pkg._npm_path }/es6-promise/dist/es6-promise.auto.js`,
				`${ pkg._npm_path }/swiper/dist/js/swiper.js`,
				`${ pkg._npm_path }/lazysizes/plugins/object-fit/ls.object-fit.js`,
				`${ pkg._npm_path }/lazysizes/plugins/parent-fit/ls.parent-fit.js`,
				`${ pkg._npm_path }/lazysizes/plugins/respimg/ls.respimg.js`,
				`${ pkg._npm_path }/lazysizes/plugins/bgset/ls.bgset.js`,
				`${ pkg._npm_path }/lazysizes/lazysizes.js`,
				`${ pkg._npm_path }/tota11y/build/tota11y.min.js`,
				`${ pkg._npm_path }/webfontloader/webfontloader.js`,
			] )
			.pipe( gulp.dest( pkg._core_theme_js_vendor_path ) );
	},
};
