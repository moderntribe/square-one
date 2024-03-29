import expect from 'expect';
import updateQueryVar from 'utils/data/update-query-var';

describe( 'updateQueryVar', () => {
	it( 'update the existing key/value pair on the specified url', () => {
		const url = 'http://localhost/?param1=true&param2=1024';
		const key = 'param1';
		const value = 'false';
		const expectedUrl = 'http://localhost/?param1=false&param2=1024';

		expect( updateQueryVar( key, value, url ) ).toBe( expectedUrl );
	} );

	it( 'update the existing key/value pair on the default url', () => {
		jsdom.reconfigure( {
			url: 'http://localhost/?param1=true&param2=1024',
		} );

		const key = 'param1';
		const value = 'false';
		const expectedUrl = 'http://localhost/?param1=false&param2=1024';

		expect( updateQueryVar( key, value ) ).toBe( expectedUrl );
	} );

	it( 'update a non-existing key/value pair on the specified url', () => {
		const url = 'http://localhost/#hash';
		const key = 'param1';
		const value = 'false';
		const expectedUrl = 'http://localhost/?param1=false#hash';

		expect( updateQueryVar( key, value, url ) ).toBe( expectedUrl );
	} );

	it( 'remove a key that has been set to undefined', () => {
		const url = 'http://localhost/?param1=true&param2=1024';
		const key = 'param1';
		const value = undefined;
		const expectedUrl = 'http://localhost/?param2=1024';

		expect( updateQueryVar( key, value, url ) ).toBe( expectedUrl );
	} );

	it( 'do not add a key that is set to undefined', () => {
		const url = 'http://localhost/';
		const key = 'param1';
		const value = undefined;
		const expectedUrl = 'http://localhost/';

		expect( updateQueryVar( key, value, url ) ).toBe( expectedUrl );
	} );
} );
