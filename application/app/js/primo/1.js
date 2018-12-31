/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/
*/
if ( typeof _cms_ == 'undefined')
	_ed_ = {};

_ed_.utcOffset = '<?php print \config::$UTC_OFFSET ?>';

if ( typeof moment != 'undefined') {
	moment().utcOffset( _ed_.utcOffset);
	_brayworth_.moment = _ed_.moment = moment;	// reset

	moment.updateLocale('en', {
		longDateFormat : {
			LT: "h:mm A",
			LTS: "h:mm:ss A",
			L: "DD/MM/YYYY",
			l: "D/M/YYYY",
			LL: "MMMM Do YYYY",
			ll: "MMM D YYYY",
			LLL: "MMMM Do YYYY LT",
			lll: "MMM D YYYY LT",
			LLLL: "dddd, MMMM Do YYYY LT",
			llll: "ddd, MMM D YYYY LT"
		}

	});

};

_brayworth_.urlwrite = _ed_.url = function( _url, withProtocol) {
	if ( 'undefined' == typeof _url) { _url = ''; }
	if ( 'undefined' == typeof withProtocol) { withProtocol = false; }

	if ( withProtocol)  {
		return ( '<?php printf( '%s%s', url::$PROTOCOL, url::$URL) ?>' + _url);

	}

	return ( '<?= url::$URL ?>' + _url);

};


// console.log( 'primo');
