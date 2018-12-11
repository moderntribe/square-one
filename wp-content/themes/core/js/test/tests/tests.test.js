import 'utils/__mocks__/localStorage';
import * as tests from 'utils/tests';

describe('isJson', () => {
	it('returns true if json string is valid', () => {
		const str = '{ "test": true }';

		expect(tests.isJson(str)).toBe(true);
	});

	it('returns false if json string is not valid', () => {
		const str = 'lorem ipsum';

		expect(tests.isJson(str)).toBe(false);
	});
});

describe('canLocalStore', () => {
	it('returns true if browser supports local storage', () => {
		expect(tests.canLocalStore()).toBe(true);
	});

	it('returns false if browser does not support local storage', () => {
		localStorage.setItem = () => {
			throw new Error();
		};
		expect(tests.canLocalStore()).toBe(false);
	});
});
