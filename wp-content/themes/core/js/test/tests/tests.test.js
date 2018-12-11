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

	// aditional test mainly for coverage
	it('local storage returns null when item is not found', () => {
		expect(localStorage.getItem('lorem')).toBe(null);
	});

	// aditional test mainly for coverage
	it('local storage clears all items', () => {
		expect(localStorage.clear()).toBe(undefined);
	});
});

describe('browserTests', () => {
	it('returns browser test object', () => {
		expect(tests.browserTests()).toMatchObject({
			android: false,
			chrome: false,
			edge: false,
			firefox: false,
			ie: false,
			ios: false,
			iosMobile: false,
			opera: false,
			safari: true,
			os: '',
		});
	});
});
