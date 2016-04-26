import expect from '../../../../../node_modules/expect/lib/index';

import { addClass, hasClass, removeClass } from '../../js/src/utils/tools';

describe('#addClass()', () => {

	it('should add an html class string to the classlist of a dom element', () => {

		let div = document.createElement('div');

		addClass(div, 'test');

		expect(div.classList.contains('test')).toExist();

	});

});

describe('#removeClass()', () => {

	it('should remove an html class string from the classlist of a dom element', () => {

		let div = document.createElement('div');

		div.classList.add('test');
		removeClass(div, 'test');

		expect(div.classList.contains('test')).toNotExist();

	});

});

describe('#hasClass()', () => {

	it('should test if a dom element has an html class', () => {

		let div = document.createElement('div');

		div.classList.add('test');

		expect(hasClass(div, 'test')).toExist();

	});

});

