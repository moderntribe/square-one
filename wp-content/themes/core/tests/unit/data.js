import expect from '../../../../../node_modules/expect/lib/index';

import queryToJSON from '../../js/src/utils/data/query-to-json';
import updateQueryVar from '../../js/src/utils/data/update-query-var';

describe('#queryToJSON()', () => {

	it('should return a json object from a param string ', () => {

		let json = queryToJSON('arg1=test1&arg2=test2');

		expect(json).toBeA('object');
		expect(json.arg1).toBe('test1');
		expect(json.arg2).toBe('test2');
	});

});

describe('#updateQueryVar()', () => {

	it('should update a param in a query string without corrupting any other part', () => {

		let key = 'arg2';
		let value = 'new_value';
		let href = 'http://www.test.com/#hash?arg1=test1&arg2=test2&arg3=test3';
		let updatedURI = updateQueryVar(key, value, href);

		expect(updatedURI)
			.toBeA('string')
			.toBe('http://www.test.com/#hash?arg1=test1&arg2=new_value&arg3=test3');

	});

});
