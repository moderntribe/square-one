/**
 * @function
 * @description Check if a url is to a file
 */

let is_file_url = ( url = '' ) => {

	let ext = url.split( '/' ).pop();
    return ext.indexOf( '.' ) !== -1;
};

export default is_file_url;