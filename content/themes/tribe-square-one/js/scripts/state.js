/**
 * @namespace modern_tribe.state
 * @since 1.0
 * @desc modern_tribe.state is were we can store various state variables, like browser info, viewport height, width etc.
 */

t.state = {
	desktop_initialized: false,
	domain             : location.protocol + '//' + location.host + '/',
	is_desktop         : false,
	is_mobile          : false,
	mobile_initialized : false,
	v_height           : 0,
	v_width            : 0
};