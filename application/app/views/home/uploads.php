<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/  ?>

<table class="table" id="<?= $_tbl = strings::rand() ?>">
    <thead>
        <tr>
            <td>file</td>
            <td class="text-center">date</td>
            <td class="text-center">size</td>
            <td>&nbsp;</td>

        </tr>

    </thead>
    <tbody>
    <?php
    foreach ($this->data->files as $fileInfo) {
        print '<tr>';

        printf( '<td>%s</td>', $fileInfo->getFileName());
        printf( '<td class="text-center">%s</td>', strings::asShortDate( date( 'c', $fileInfo->getMTime()), true));
        printf( '<td class="text-center">%s Mb</td>', number_format( (int)$fileInfo->getSize()/1024/1024));
        printf( '<td class="py-0 text-right">
                <a class="btn btn-light" href="%s"><i class="fa fa-download"></i></a>
                <btn class="btn btn-light" href="#" data-file=%s trash><i class="fa fa-trash"></i></btn>
            </td>',
            strings::url( $this->route . '/uploads?f=' . urlencode( $fileInfo->getFileName())),
            json_encode( $fileInfo->getFileName(), JSON_UNESCAPED_SLASHES)

        );

        print '</tr>';

    }?>
    </tbody>

</table>
<script>
$(document).ready( () => {
    $('btn[trash]', '#<?= $_tbl ?>')
    .on( 'click', function( e) {
        let _me = $(this);
        let _data = _me.data();

        _brayworth_.ask({
			headClass: 'text-white bg-danger',
			text: 'Are you sure ?',
			title: 'Confirm Delete',
			buttons : {
				yes : function() {
					$(this).modal('hide');
                    _me.trigger( 'delete');

				}

			}

        });

    })
    .on( 'delete', function( e) {
        let _me = $(this);
        let _data = _me.data();

        _brayworth_.post({
            url : _brayworth_.url('<?= $this->route ?>'),
            data : {
                action : 'delete-upload',
                file : _data.file

            }

        }).then( function( d) {
            if ( 'ack' == d.response) {
                _me.closest('tr').remove();

            }
            else {
                _brayworth_.growl( d);

            }

        });

    });

});
</script>