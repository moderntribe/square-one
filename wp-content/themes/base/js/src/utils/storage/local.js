
let put = ( key, value ) => {
	window.localStorage.setItem( key, value );
};

let get = ( key ) => {
	return window.localStorage.getItem( key );
};

let remove = ( key ) => {
	return window.localStorage.removeItem( key );
};

let clear = () => {
	window.localStorage.clear();
};

export { put, get, remove, clear }