/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/
	*/
if ( 'undefined' == typeof _ed_) {
	_ed_ = {};

}

_ed_.easyLog = function() {
	var _me = $(this);

	var data = {
		'action' : 'get-log',
		'site' : 0,
		'guid' : 0,
		'user' : 0,

	}

	if ( !!_me.data( 'site')) {
		data.site = _me.data( 'site');

	}
	if ( !!_me.data( 'guid')) {
		data.guid = _me.data( 'guid');

	}
	if ( !!_me.data( 'user')) {
		data.user = _me.data( 'user');

	}

	// console.log( data);

	_brayworth_.post({
		url : _brayworth_.url('easyLog'),
		data : data,

	})
	.then( function( d) {
		if ( 'ack' == d.response) {

			_me.html('');

			var btn = $('<a href="#" class="btn btn-primary pull-right" data-provide="add-comment">Add Comment</a>').appendTo( _me);
			btn.data('site', data.site);
			btn.data('guid', data.guid);
			btn.data('user', data.user);

			_ed_.easyComment.call(btn);

			$('<h3>easyLog</h3>').appendTo( _me);

			var t = $('<table class="table table-striped table-sm"></table>').appendTo( _me);
			t.append('<colgroup><col style="width: 7em;" /><col /><col style="width: 6em;" /></colgroup>');

			var head = $('<thead></thead>').appendTo(t);
			var r = $('<tr></tr>').appendTo( head);

			$('<td>created</td>').appendTo( r);
			$('<td>comment</td>').appendTo( r);
			$('<td>user</td>').appendTo( r);

			var body = $('<tbody></tbody>').appendTo(t);

			$.each( d.data, function( i, el) {
				var r = $('<tr></tr>').appendTo( body);

				$('<td></td>').html( _ed_.moment( el.created).format( 'L')).appendTo( r);
				$('<td></td>').html( el.comment.replace(/\n/g,'<br />')).appendTo( r);
				$('<td></td>').html( el.user_name).appendTo( r);

			})

			// console.log( d);

		}
		else {
			_brayworth_.growl( d);

		}

	})

}
;
