
var clickDebugBarExtenderLink = function( targetsGroupId, obj) {
		var sectionDivs = document.getElementById( targetsGroupId ).childNodes;
		for ( var i = 0; i < sectionDivs.length; i++ ) {
				if ( 1 != sectionDivs[i].nodeType ) {
						continue;
				}
				sectionDivs[i].style.display = 'none';
		}
		document.getElementById( obj.href.substr( obj.href.indexOf( '#' ) + 1 ) ).style.display = 'block';

		for ( var i = 0; i < obj.parentNode.parentNode.childNodes.length; i++ ) {
				if ( 1 != obj.parentNode.parentNode.childNodes[i].nodeType ) {
						continue;
				}
				obj.parentNode.parentNode.childNodes[i].removeAttribute( 'class' );
		}				 obj.parentNode.setAttribute( 'class', 'current' );
		return false;
};
