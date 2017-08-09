/* global tinymce */
(function() {
	tinymce.PluginManager.add( 'typekit', function( editor, url ) {
		console.log('TypeKit ' + tinymce_typekit_id);

		function addScriptToHead() {
			// Get the DOM document object for the IFRAME
			var doc = editor.getDoc();

			// Create the script we will add to the header asynchronously
			var jscript = "(function() {\n\
				var config = {\n\
					kitId: tinymce_typekit_id\n\
				};\n\
				var d = false;\n\
				var tk = document.createElement('script');\n\
				tk.src = '//use.typekit.net/' + config.kitId + '.js';\n\
				tk.type = 'text/javascript';\n\
				tk.async = 'true';\n\
				tk.onload = tk.onreadystatechange = function() {\n\
					var rs = this.readyState;\n\
					if (d || rs && rs != 'complete' && rs != 'loaded') return;\n\
					d = true;\n\
					try { Typekit.load(config); } catch (e) {}\n\
				};\n\
				var s = document.getElementsByTagName('script')[0];\n\
				s.parentNode.insertBefore(tk, s);\n\
			})();";

			// Create a script element and insert the TypeKit code into it
			var script = doc.createElement( 'script' );
			script.type = 'text/javascript';
			script.appendChild( doc.createTextNode( jscript ) );

			// Add the script to the header
			doc.getElementsByTagName( 'head' )[0].appendChild( script );
		}

		// Support both TinyMCE 3 and 4.
		if ( 3 < parseInt( tinymce.majorVersion ) ) {
			editor.on( 'preInit', function() {
				addScriptToHead();
			});
		} else {
			editor.onPreInit.add( function( editor ) {
				addScriptToHead();
			});
		}
	});
});
