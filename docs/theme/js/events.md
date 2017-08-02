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

* [Overview](/docs/theme/js/README.md)
* [Selectors](/docs/theme/js/selectors.md)
* [Events](/docs/theme/js/events.md)
* [Jquery](/docs/theme/js/jquery.md)