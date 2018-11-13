import * as bodyLock from 'utils/dom/body-lock';

describe( 'bodyLockaa', () => {
	
	it( 'lock the body scroll', () => {
		bodyLock.lock();

		let style = document.body.style;

		expect( style.position ).toBe( 'fixed' );
		expect( style.marginTop ).toBe( '-0px' ); // weird thing jest's browser has it as -0px
	} );

	it( 'unlock the body scroll', () => {
		bodyLock.unlock();

		let style = document.body.style;

		expect( style.position ).toBe( 'static' );
		expect( style.marginTop ).toBe( '0px' );
	} );

} );