/**
 * @module
 * @description Some vanilla js cross browser utils
 */

const addClass = (el, className) => {
	if (!el) {
		console.log(`Cant apply class ${className} on null element.`);
		return null;
	}

	if (el.classList) {
		el.classList.add(className);
	} else {
		el.className += ' ' + className;
	}

	return el;
};

const getChildren = (el) => {
	let children = [];
	for (var i = el.children.length; i--;) {
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

	if (el.classList) {
		return el.classList.contains(className);
	} else {
		return new RegExp('(^| )' + className + '( |$)', 'gi').test(el.className);
	}
};

const removeClass = (el, className) => {

	if (!el) {
		console.log(`Cant remove class ${className} on null element.`);
		return null;
	}

	if (el.classList) {
		el.classList.remove(className);
	} else {
		el.className = el.className.replace(new RegExp('(^|\\b)' + className.split(' ').join('|') + '(\\b|$)', 'gi'), ' ');
	}

	return el;
};

const convertElements = (elements) => {

	let converted = [];
	for (var i = elements.length; i--; converted.unshift(elements[i]));

	return converted;

};

const isNodelist = (elements) => {

	let stringRepr = Object.prototype.toString.call(elements);

	return typeof elements === 'object' &&
		/^\[object (HTMLCollection|NodeList|Object)\]$/.test(stringRepr) &&
		elements.hasOwnProperty('length') &&
		(elements.length === 0 || (typeof elements[0] === 'object' && elements[0].nodeType > 0));

};

export { addClass, getChildren, hasClass, removeClass, convertElements, isNodelist };
