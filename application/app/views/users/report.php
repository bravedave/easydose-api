<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/	?>
<style>
i[title="download as CSV"] {margin-top: -18px;}
</style>
<div class="row">
  <div class="col-md-6">
      <input type="search" name="<?= $sid = uniqid( 'ed') ?>"
        id="<?= $sid ?>" placeholder="search..." class="form-control"
        autofocus />

  </div>

</div>

<div class="row">
	<div class="col">
		<table class="table table-sm" id="<?= $tblID = uniqid( 'ed') ?>">
			<thead class="small">
				<tr>
					<td role="sort-header" data-key="name">Proprietor</td>
					<td role="sort-header" data-key="username" class="d-none d-sm-table-cell">UserName</td>
					<td role="sort-header" data-key="email" class="d-none d-md-table-cell">Email</td>
					<td class="text-center" role="sort-header" data-key="admin" data-sorttype="numeric">Admin</td>
				</tr>
			</thead>

			<tbody>
				<?php	if ( $this->data) {
					while ( $dto = $this->data->dto()) {	?>
						<tr data-role="item"
							data-id="<?= $dto->id ?>"
							data-name="<?= $dto->name ?>"
							data-username="<?= $dto->username ?>"
							data-email="<?= $dto->email ?>"
							data-admin="<?= (int)$dto->admin ?>"
							>
							<td><?= sprintf( '%s<div class="text-muted small">%s</div>', $dto->name, $dto->site ) ?></td>
							<td class="d-none d-sm-table-cell">
								<div class="text-truncate" style="width: 12rem"><?= $dto->username ?></div>

							</td>
							<td class="d-none d-md-table-cell"><?= $dto->email ?></td>
							<td class="text-center"><?= ( $dto->admin ? strings::html_tick : '&nbsp;' ) ?></td>

						</tr>

					<?php		}

				}	?>

			</tbody>

		</table>

	</div>

</div>
<script>
$(document).ready( function() {
	$('#<?= $sid ?>').on( 'keyup', function(e) {
		var _me = $(this);
		var t = _me.val();

		$('tr[data-role="item"]').each( function( i, tr) {
			var _tr = $(tr);

			if ( t == '') {
				_tr.removeClass('d-none');
			}
			else {
				var rex = new RegExp(t,'i')
				// console.log( t, _tr.text())
				if ( rex.test( _tr.text())) {
					_tr.removeClass('d-none');
				}
				else {
					_tr.addClass('d-none');

				}

			}

		});

	});

	$('tr[data-role="item"]').each( function( i, el) {
		var _el = $(el);
		var editURL = _brayworth_.url('users/view/' + _el.data('id'));

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
							this.modal('close');

						},
						OK : function( e) {
							this.modal('close');

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

	/*--[ a CSV download icon ]--*/
  (function( tbl) {
		if ( tbl.length == 1) {
			$('<i class="fa fa-fw fa-table noprint pointer pull-right" title="download as CSV"></i>')
			.on( 'click', function( e) {
				_ed_.csv.call( tbl, 'users-list.csv');
			})
			.insertBefore( tbl);

		}

	})( $("#<?= $tblID ?>"));

})
</script>
