/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	test:
		_ed_.csv.call( <a table>, 'filename.csv');

	*/
_ed_.csv = function( fileName) {
	if ( !( this instanceof jQuery)) {
		if ( !this.tagName)
			throw 'Not a table';

		if ( !/table/i.test( this.tagName))
			throw 'Not a table (tagName)';

	}

	var data = [];

	$('thead tr', this).each( function( i, tr) {
		var a = [];
		$('td', tr).each( function( i, el) {
			a.push( el.innerHTML);
		});
		data.push( a);

	})

	$('tbody tr', this).each( function( i, tr) {
		var a = [];
		$('td', tr).each( function( i, el) {
			var s = el.innerText;
			if ( i == 0) {
				var els = $('img', el);
				if ( els.length > 0)
					s = els.first().attr('title');

			}

			a.push( s.replace( /\n+$/g, ''));

		});
		data.push( a);
		//~ console.log(
	})

	//~ console.log( data);

	var csvContent = "data:text/csv;charset=utf-8,";
	data.forEach(function(infoArray, index){
		var dataString = JSON.stringify(infoArray);
		dataString = dataString.replace( /(^\[|\]$)/g,'');	// remove enclosing []
		csvContent += index < data.length ? dataString+ "\n" : dataString;

	});

	var encodedUri = encodeURI(csvContent);
	var link = document.createElement("a");
		link.setAttribute("href", encodedUri);
		link.setAttribute("download", fileName);
	document.body.appendChild(link); // Required for FF

	link.click(); // This will download the data file named "my_data.csv".

	//~ console.log( csvContent);

}
;
