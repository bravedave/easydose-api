<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/	?>
<table class="table table-striped">
	<thead>
		<tr>
			<td>Name</td>
			<td>UserName</td>
			<td>Email</td>
		</tr>
	</thead>

	<tbody>
<?php	if ( $this->data) {
			while ( $dto = $this->data->dto()) {	?>
		<tr data-role="item" data-id="<?php print $dto->id ?>">
			<td><?php print $dto->name ?></td>
			<td><?php print $dto->username ?></td>
			<td><?php print $dto->email ?></td>

		</tr>

<?php		}

		}	?>

	</tbody>

</table>
<script>
$(document).ready( function() {
	$('tr[data-role="item"]').each( function( i, el) {
		var _el = $(el);
		var editURL = _brayworth_.urlwrite('users/edit/' + _el.data('id'));

		_el.css('cursor','pointer').on( 'click', function( e) {
			e.stopPropagation();
			window.location.href = editURL;

		});

		_el.on('contextmenu', function( e) {
			if ( e.shiftKey)
				return;

			e.stopPropagation(); e.preventDefault();

			_brayworth_.hideContexts();

			var _context = _brayworth_.context();

			_context.append( $('<a><i class="fa fa-fw fa-pencil"></i><strong>edit</strong></a>').attr( 'href', editURL));
			_context.append( $('<a href="#"><i class="fa fa-fw fa-trash"></i>delete</a>').on('click', function(evt) {
				_context.close();
				e.stopPropagation(); e.preventDefault();

				_brayworth_.modal({
					title : 'Are you Sure?',
					text : 'Delete record',
					buttons : {
						cancel : function( e) {
							$(this).modal('close');

						},
						OK : function( e) {
							$(this).modal('close');

							$.ajax({
								type : 'POST',
								url : _brayworth_.urlwrite( 'users'),
								data : {
									action : 'delete',
									id : _el.data('id'),

								}

							})
							.done( function( data) {
								$('body').growlAjax( data);
								window.location.reload();

							});

						}

					}

				});

			}));


			_context.open( e);

		});

	});

})
</script>