import { retrieve } from '../api';

export function fetchExample() {
	return retrieve( 'example', {
		method: 'GET',
	} );
}
