/*
	By Osvaldas Valutis, www.osvaldas.info
	Available for use under the MIT License
*/

'use strict';

;( function ( document, window, index )
{
	var inputs = document.querySelectorAll( '.inputfile' );
	Array.prototype.forEach.call( inputs, function( input )
	{
		var label	 = input.nextElementSibling,
			labelVal = label.innerHTML;

		input.addEventListener( 'change', function( e )
		{
			var fileName = '';

			if( this.files && this.files.length > 1 ) {
				
				var filesize = this.files[0].size;
				//alert(this.files[0].size);

				if (filesize > 1000000) {
				//	alert("File size must be less than 1MB", "Error");
				//	return false;
				}
				
				
				fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
			} else {

				fileName = e.target.value.split( '\\' ).pop();
			}

			if( fileName )
				label.querySelector( 'span' ).innerHTML = fileName;
			else
				label.innerHTML = labelVal;
		});

		// Firefox bug fix
		input.addEventListener( 'focus', function(){ input.classList.add( 'has-focus' ); });
		input.addEventListener( 'blur', function(){ input.classList.remove( 'has-focus' ); });
	});
}( document, window, 0 ));