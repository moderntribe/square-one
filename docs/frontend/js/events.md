# Events and Delegation

For event delegation we often want to use jQuery's `on`, to bind a handler to a parent node and list for events on children. This system has a plain js module that does the same task. It's called delegate, here is how to use it:

``` javascript
import delegate from 'delegate';
import * as tools from '../utils/tools';

const el = {
	container: tools.getNodes('program-filters')[0],
};

const handleFilterClick = (e) => {
	const trigger = e.delegateTarget;
	if (trigger.classList.contains('filter__button--active')) {
		trigger.classList.remove('filter__button--active');
	} else {
		trigger.classList.add('filter__button--active');
	}
};

const cacheElements = () => {
	el.filterTrigger = tools.getNodes('filter-trigger', true, el.container);
};

const bindEvents = () => {
	delegate(el.container, '[data-js="filter-trigger"]', 'click', handleFilterClick);
};

const init = () => {
	if (!el.container) {
		return;
	}
	
	cacheElements();
	bindEvents();
};

```

Note here we must use `e.delegateTarget` not `e.currentTarget`.

## Table of Contents

* [Overview](/docs/frontend/js/README.md)
* [Code Splitting](/docs/frontend/js/code-splitting.md)
* [Polyfills](/docs/frontend/js/polyfills.md)
* [Selectors](/docs/frontend/js/selectors.md)
* [Events](/docs/frontend/js/events.md)
* [Jquery](/docs/frontend/js/jquery.md)
