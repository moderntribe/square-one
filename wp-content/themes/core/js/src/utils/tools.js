/**
 * @module
 * @description Some vanilla js cross browser utils
 */

export const addClass = (el, className) => {
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

export const getChildren = (el) => {
	const children = [];
	let i = el.children.length;
	for (i; i--;) {
		if (el.children[i].nodeType !== 8) {
			children.unshift(el.children[i]);
		}
	}

	return children;
};

export const hasClass = (el, className) => {
	if (!el) {
		console.log(`Cant test class ${className} on null element.`);
		return null;
	}

	return el.classList ? el.classList.contains(className) : new RegExp(`(^| )${className}( |$)`, 'gi').test(el.className);
};

export const removeClass = (el, className) => {
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

export const convertElements = (elements) => {
	const converted = [];
	let i = elements.length;
	for (i; i--; converted.unshift(elements[i]));

	return converted;
};

export const isNodelist = (elements) => {
	const stringRepr = Object.prototype.toString.call(elements);

	return typeof elements === 'object' &&
		/^\[object (HTMLCollection|NodeList|Object)\]$/.test(stringRepr) &&
		elements.hasOwnProperty('length') &&
		(elements.length === 0 || (typeof elements[0] === 'object' && elements[0].nodeType > 0));
};

export const getNodes = (selector = '', convert = false, node = document) => {
	let nodes = node.querySelectorAll(`[data-js="${selector}"]`);
	if (nodes.length && convert) {
		nodes = convertElements(nodes);
	}
	return nodes;
};

export const closest = (el, selector) => {
	let matchesFn;
	let parent;

	['matches', 'webkitMatchesSelector', 'mozMatchesSelector', 'msMatchesSelector', 'oMatchesSelector'].some((fn) => {
		if (typeof document.body[fn] === 'function') {
			matchesFn = fn;
			return true;
		}
		return false;
	});

	while (el) {
		parent = el.parentElement;
		if (parent && parent[matchesFn](selector)) {
			return parent;
		}
		el = parent; // eslint-disable-line
	}

	return null;
};

export const insertAfter = (newNode, referenceNode) => {
	referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
};

export const insertBefore = (newNode, referenceNode) => {
	referenceNode.parentNode.insertBefore(newNode, referenceNode);
};
