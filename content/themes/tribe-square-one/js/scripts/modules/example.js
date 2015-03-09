/**
 * @namespace modern_tribe.subdirectory.example
 * @since 1.0
 * @desc modern_tribe.subdirectory.example is an example module boilerplate. clone it to setup your own new module.
 * Use subdirectories were possible/applicable. This module assumes its actually living in a folder called "subdirectory".
 * Please strip the unused portions/comments of this boiler ;)
 * Please don't delete this boiler, its excluded in the compile process.
 */

t.subdirectory = t.subdirectory || {};

t.subdirectory.example = {

	$el: $( '#root-dom-element-if-applicable' ),

	// hook up this init in core/execute_ready or core/execute_load

	init: function(){

		// only bind events and setup this module if our target element exists on the page

		if( this.$el.length ){

			// return scope to module level where needed

			_.bindAll( this, '_execute_resize' );

			// stack your initialize function calls here

			this._bind_events();

		}

	},

	_bind_events: function() {

		t.$el.doc
			.on( 'modern_tribe_resize_executed', this._execute_resize );

	},

	_execute_resize: function(){

		// resize code for this module that runs after global debounced resize functions are executed

	}

};
