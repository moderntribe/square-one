/**
 * @module
 * @description Some vanilla js cross browser utils
 */

const addClass = (el, className) => {
	const element = el;
	if (!element) {
		console.log(`Cant apply class ${className} on null element.`);
		return null;
	}

	if (element.classList) {
		element.classList.add(className);
	} else {
		element.className += ` ${className}`;
	}

	return element;
};

const getChildren = (el) => {
	const children = [];
	let i = el.children.length;
	for (i; i--;) {
		if (el.children[i].nodeType !== 8) {
			children.unshift(el.children[i]);
		}
	}

	return children;
};

const hasClass = (el, className) => {
	if (!el) {
		console.log(`Cant test class ${className} on null element.`);
		return null;
	}

	return el.classList ? el.classList.contains(className) : new RegExp(`(^| )${className}( |$)`, 'gi').test(el.className);
};

const removeClass = (el, className) => {
	const element = el;
	if (!element) {
		console.log(`Cant remove class ${className} on null element.`);
		return null;
	}

	if (element.classList) {
		element.classList.remove(className);
	} else {
		element.className = element.className.replace(new RegExp(`(^|\\b)${className.split(' ').join('|')}(\\b|$)`, 'gi'), ' ');
	}

	return element;
};

const convertElements = (elements) => {
	const converted = [];
	let i = elements.length;
	for (i; i--; converted.unshift(elements[i]));

	return converted;
};

const isNodelist = (elements) => {
	const stringRepr = Object.prototype.toString.call(elements);

	return typeof elements === 'object' &&
		/^\[object (HTMLCollection|NodeList|Object)\]$/.test(stringRepr) &&
		elements.hasOwnProperty('length') &&
		(elements.length === 0 || (typeof elements[0] === 'object' && elements[0].nodeType > 0));
};

export { addClass, getChildren, hasClass, removeClass, convertElements, isNodelist };
