const put = ( key, value ) => {
	window.sessionStorage.setItem( key, value );
};

const get = ( key ) => {
	return window.sessionStorage.getItem( key );
};

const remove = ( key ) => {
	return window.sessionStorage.removeItem( key );
};

const clear = () => {
	window.sessionStorage.clear();
};

export { put, get, remove, clear }