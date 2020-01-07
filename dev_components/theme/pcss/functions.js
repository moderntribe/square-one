/**
 * Custom PostCSS functions defined locally.
 *
 * @type {Color}
 */

var color = require('css-color-converter');

module.exports = {
	/**
	 * Darken a color.
	 *
	 * Converts a color value into RGBA via `css-color-converter` and darkens each channel individually
	 * before returning a HEX color value.
	 *
	 * @param value
	 * @param frac
	 * @returns string color in HEX format.
	 */
	darken: function (value, frac) {
		var darken = 1 - parseFloat(frac);
		var rgba = color(value).toRgbaArray();
		var r = rgba[0] * darken;
		var g = rgba[1] * darken;
		var b = rgba[2] * darken;
		return color([r, g, b]).toHexString();
	},

	/**
	 * Darken a color.
	 *
	 * Converts a color value into RGBA via `css-color-converter` and lightens each channel individually
	 * before returning a HEX color value.
	 *
	 * Note: lightening can only work on non-zero values. Thus, a color such as `#ff0000` cannot become lighter than it is.
	 * This is because `#ff0000` becomes `rgb(255, 0, 0)` and multiplying each of those values by any multiplier
	 * Won't change them. 255 is already as "light" as possible and 0 * any value is always 0.
	 *
	 * @param value
	 * @param frac
	 * @returns string color in HEX format.
	 */
	lighten: function (value, frac) {
		var lighten = 1 + parseFloat(frac);
		var rgba = color(value).toRgbaArray();
		var r = rgba[0] * lighten;
		var g = rgba[1] * lighten;
		var b = rgba[2] * lighten;
		return color([r, g, b]).toHexString();
	},
};
