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

_ed_.easyComment = function() {
	var _me = $(this);

	_me.on( 'click', function( e) {
		e.stopPropagation(); e.preventDefault();

		var fld = $('<textarea name="comment" placeholder="enter a comment" rows="6" class="form-control" />')
		var data = {
			'action' : 'post comment',
			'comment' : '',
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

		_brayworth_.modal({
			width : 600,
			title : 'Enter a Comment',
			text : fld,
			buttons : {
				post : function( e) {
					$(this).modal('close');

					data.comment = fld.val();

					_brayworth_.post({
						url : _brayworth_.url('easyLog'),
						data : data,

					})
					.then( function( d) {
						_brayworth_.growl(d);

						$('[data-provide="easyLog-table"]').each( function( i, el) {
							_ed_.easyLog.call( el);

						});

					});

				}

			}

		})

		_me.blur();

	})

}
;
