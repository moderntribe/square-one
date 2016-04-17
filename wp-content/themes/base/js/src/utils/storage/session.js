
let put = ( key, value ) => {
	window.sessionStorage.setItem( key, value );
};

let get = ( key ) => {
	return window.sessionStorage.getItem( key );
};

let remove = ( key ) => {
	return window.sessionStorage.removeItem( key );
};

let clear = () => {
	window.sessionStorage.clear();
};

export { put, get, remove, clear }