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
	_brayworth_.moment = _ed_.moment = function( a,b,c,d) {
		/** we only want to work in the timezone of the office, not the browser */
		var d = moment( a,b,c,d)
		d.utcOffset( _ed_.utcOffset);
		return (d);

	};

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

// console.log( 'primo');
