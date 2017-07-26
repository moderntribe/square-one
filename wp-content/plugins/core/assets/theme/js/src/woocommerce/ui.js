/**
 * @module
 * @description Module handles WooCommerce UI.
 */

const el = {
	container: document.getElementsByClassName('woocommerce')[0],
};

/**
 * @function customizeCheckboxLoginRemember
 * @description Customize login remember checkbox markup.
 */

const customizeCheckboxLoginRemember = () => {
	if (!$('.woocommerce-checkout').length) {
		return;
	}

	const $checkbox = $('#rememberme');
	if (!$checkbox.length) {
		return;
	}

	const label = $checkbox.parent();

	label.attr('for', 'rememberme');
	label.wrapAll('<span class="form-control-checkbox form-control-custom-style"></span>');
	$checkbox.insertBefore(label);
};

/**
 * @function refineUI
 * @description Kick off this modules functions
 */

const refineUI = () => {
	if (!el.container) {
		return;
	}

	customizeCheckboxLoginRemember();

	console.info('Initialized WC UI script.');
};

export default refineUI;
