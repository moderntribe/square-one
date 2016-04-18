
import expect from '../../../../../node_modules/expect/lib/index';

import { add_class, has_class, remove_class, convert_elements } from '../../js/src/utils/tools';

describe( '#add_class()', () => {

	it( 'should add an html class string to the classlist of a dom element', () => {

		let div = document.createElement( 'div' );

		add_class( div, 'test' );

		expect( div.classList.contains( 'test' ) ).toExist();

	});

});

describe( '#remove_class()', () => {

	it( 'should remove an html class string from the classlist of a dom element', () => {

		let div = document.createElement( 'div' );

		div.classList.add( 'test' );
		remove_class( div, 'test' );

		expect( div.classList.contains( 'test' ) ).toNotExist();

	});

});

describe( '#has_class()', () => {

	it( 'should test if a dom element has an html class', () => {

		let div = document.createElement( 'div' );

		div.classList.add( 'test' );

		expect( has_class( div, 'test' ) ).toExist();

	});

});

