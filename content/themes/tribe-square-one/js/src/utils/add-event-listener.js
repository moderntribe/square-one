

export default function addEventListener(el, eventName, handler) {
	if (el.addEventListener) {
		el.addEventListener(eventName, handler);
	} else {
		el.attachEvent('on' + eventName, function(){
			handler.call(el);
		});
	}
}