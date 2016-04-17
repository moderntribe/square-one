
import expect from '../../../../../node_modules/expect/lib/index';

import query_to_json from '../../js/src/utils/data/query-to-json';
import update_query_var from '../../js/src/utils/data/update-query-var';

import { is_json } from '../../js/src/utils/tests';

describe( '#query_to_json()', () => {

	it( 'should return a json object from a param string ', () => {

		let json = query_to_json( 'arg1=test1&arg2=test2' );

		expect( json ).toBeA( 'object' );
		expect( json.arg1 ).toBe( 'test1' );
		expect( json.arg2 ).toBe( 'test2' );
	});

});

describe( '#update_query_var()', () => {

	it( 'should update a param in a query string without corrupting any other part', () => {

		let key = 'arg2';
		let value = 'new_value';
		let href = 'http://www.test.com/#hash?arg1=test1&arg2=test2&arg3=test3';
		let updated_uri = update_query_var( key, value, href );

		expect( updated_uri )
				.toBeA( 'string' )
				.toBe( 'http://www.test.com/#hash?arg1=test1&arg2=new_value&arg3=test3' );

	});

});

describe( '#is_json()', () => {

	it( 'should return true or false when testing a passed string for if it can be parsed as json', () => {

		let can_json = '{"key":"value"}';
		let cant_json = 'not json string';

		expect( is_json( can_json ) ).toExist();
		expect( is_json( cant_json ) ).toNotExist();

	});

});



