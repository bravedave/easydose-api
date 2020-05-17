<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/
	*/

	// sys::dump( $this->data->dto());
	?>
<h1 class="d-none d-print-block"><?= $this->title ?></h1>

<table class="table table-sm">
	<thead class="small">
		<tr class="align-bottom">
			<td class="border-0">proprietor</td>
			<td class="border-0">business</td>
			<td class="border-0">license</td>
			<td class="text-center border-0">wks</td>
			<td class="text-center border-0">expires</td>
			<td class="text-center border-0">last<br />invoice<br />no.</td>
			<td class="text-center border-0">state</td>
			<td class="text-center border-0">unpaid<br />invoice<br />no.</td>
			<td class="text-center border-0">&nbsp;</td>
			<td class="text-center border-0">created</td>

		</tr>

	</thead>

	<tbody id="<?= $uidBody = uniqid('ed_') ?>">
	<?php
	if ( $this->data) {
		while ( $dto = $this->data->dto()) {	?>
		<tr data-id="<?= $dto->id ?>" <?php
			if ( $dto->due == dao\users::expired) {
				print 'class="bg-danger text-white"';

			}
			elseif ( $dto->due == dao\users::due) {
				print 'class="bg-warning"';

			}
			?>>
			<td><?= $dto->name ?></td>
			<td><div style="width: 250px;" class="text-truncate"><?= $dto->business_name ?></div></td>
			<td><?= strings::ShortLicense( $dto->license) ?></td>
			<td class="text-center"><?= $dto->workstations ?></td>
			<td class="text-center"><?= strings::asShortDate( $dto->expires) ?></td>
			<td class="text-center"><?= $dto->last_invoice ?></td>
			<td class="text-center"><?= $dto->last_invoice_state ?></td>
			<td class="text-center"><?php if ( $dto->unpaid_invoice_number) print $dto->unpaid_invoice_number; ?></td>
			<td class="text-center"><?php
				if ( $dto->unpaid_invoice == dao\users::created) {
					print '...';

				}
				elseif ( $dto->unpaid_invoice == dao\users::sent) {
					print '<i class="fa fa-paper-plane-o"></i>';

				} ?></td>
			<td class="text-center"><?= strings::asShortDate( $dto->last_invoice_created) ?></td>

		</tr>

	<?php
		}	// while ( $dto = $this->data->dto())

	}	// if ( $this->data)	?>

	</tbody>

</table>
<script>
$(document).ready( function() {
	$('#<?= $uidBody ?> > tr').each( function( i, el) {
		$(el).addClass('pointer').on( 'click', function( e) {
			e.stopPropagation(); e.preventDefault();

			let _tr = $(this);
			let id = _tr.data('id');

			if ( e.ctrlKey) {
				window.open( _ed_.url('users/view/' + id));

			}
			else {
				window.location.href = _ed_.url('users/view/' + id);

			}

		}).on( 'contextmenu', function( e) {
			if ( e.shiftKey)
				return;

			e.stopPropagation();e.preventDefault();

			_brayworth_.hideContexts();

			let _context = _brayworth_.context();
			let _tr = $(this);
			let id = _tr.data('id');

			_context.append( $('<a><i class="fa fa-user"></i><strong>Goto Account</strong></a>').attr('href',_ed_.url('users/view/' + id)));

			_brayworth_.post({
				url : _ed_.url('users'),
				data : {
					action : 'get-invoices-for-user',
					id : id

				}

			}).then( function( d) {
				if ( 'ack' == d.response && d.data.length > 0) {
					_context.append( '<div>Invoices...</div>');
					$.each( d.data, function( i, el) {
						let invDate = _ed_.moment( el.created);
						let inv = el.id + '. ' + invDate.format( 'll') + ' - ' + el.state;
						_context.append( $('<a></a>').html( inv).prepend('<i class="fa fa-file-text-o"></i>').attr('href',_ed_.url('account/invoice/' + el.id)));

					});

				}

			});

			_context.open( e);

		});

	});

});
</script>
