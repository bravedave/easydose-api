<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/ ?>
<style>
i[title="download as CSV"] {margin-top: -18px;}
</style>
<div class="row">
  <div class="col-md-6 p-0">
      <input type="text" name="<?php print $sid = uniqid( 'ed') ?>"
        id="<?php print $sid ?>" placeholder="search..." class="form-control"
        autofocus />

  </div>

</div>

<div class="row">
  <div class="col p-0">
    <table class="table table-striped table-sm small" sites-list>
      <thead>
        <tr>
          <td>State</td>
          <td role="sort-header" data-key="site">Site</td>
          <td class="d-none d-lg-table-cell">Tel.</td>
          <td class="d-none d-lg-table-cell text-center"><i class="fa fa-user"></i></td>
          <td class="d-none d-lg-table-cell text-center">@</td>
          <td class="d-none d-lg-table-cell text-center">ABN</td>
          <td class="d-none d-xl-table-cell">IP</td>
          <td>Product</td>
          <td class="d-none d-md-table-cell">Active/<br />Patients</td>
          <td class="d-none d-xl-table-cell">OS</td>
          <td class="d-none d-xl-table-cell">Workstation</td>
          <td class="d-none d-xl-table-cell">Deploy</td>
          <td class="d-none d-lg-table-cell" role="sort-header" data-key="version">Version</td>
          <td class="text-center">Act</td>
          <td class="d-none d-md-table-cell" role="sort-header" data-key="expires">Expires</td>
          <td class="d-none d-lg-table-cell" role="sort-header" data-key="updated">Update</td>

        </tr>

      </thead>

      <tbody>
<?php
$isites = 0;
$phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
while ( $dto = $this->data->sites->dto()) {
  $isites++;
  $tel = '';
  $number = $phoneUtil->parse( $dto->tel, config::country_code);
  if ( $phoneUtil->isValidNumber( $number)) {
    $tel = $phoneUtil->format( $number, \libphonenumber\PhoneNumberFormat::NATIONAL);
  }
  ?>
        <tr
          data-id="<?php print $dto->id ?>"
          data-site="<?php print $dto->site ?>"
          data-version="<?php print $dto->version ?>"
          data-expires="<?php print $dto->expires ?>"
          data-updated="<?php print $dto->updated ?>"
          site>

          <td><?php print $dto->state ?></td>
          <td>
            <?php print $dto->site ?>
          </td>
          <td class="d-none d-lg-table-cell text-nowrap"><?php print $tel ?></td>
          <td class="d-none d-lg-table-cell"><i class="fa fa-fw <?php print ( $dto->guid_user_id ? 'fa-check text-info' : 'fa-times text-danger') ?>"></i></td>
          <td class="d-none d-lg-table-cell"><i class="fa fa-fw <?php print ( $dto->email ? 'fa-check text-info' : 'fa-times text-danger') ?>"></i></td>
          <td class="d-none d-lg-table-cell"><i class="fa fa-fw <?php print ( $dto->abn ? 'fa-check text-info' : 'fa-times text-danger') ?>"></i></td>
          <td class="d-none d-xl-table-cell"><?php print $dto->ip ?></td>
          <td><?php print strings::ShortLicense( $dto->productid); ?></td>
          <td class="d-none d-md-table-cell"><?php print sprintf( '%s/%s', $dto->patientsActive, $dto->patients) ?></td>
          <td class="d-none d-xl-table-cell"><?php print strings::StringToOS($dto->os) ?></td>
          <td class="d-none d-xl-table-cell"><?php print $dto->workstation ?></td>
          <td class="d-none d-xl-table-cell"><?php print $dto->deployment ?></td>
          <td class="d-none d-lg-table-cell"><?php print $dto->version ?></td>
          <td class="text-center"><i class="fa fa-fw <?php print ( $dto->activated ? 'fa-circle text-info' : 'fa-times text-danger') ?>"></i></td>
          <td class="d-none d-md-table-cell"><?php print date( \config::$DATE_FORMAT, strtotime( $dto->expires )) ?></td>
          <td class="d-none d-lg-table-cell"><?php print strings::asShortDateTime( $dto->updated) ?></td>

        </tr>

<?php
} // while ( $dto = $this->data->sites->dto()) ?>

      </tbody>

    </table>

  </div>

</div>

<div class="row">
  <div class="col">
    <em><?php printf( 'count: %s', $isites); ?></em>

  </div>

  <div class="col text-right">
    <em><?php print date( 'c'); ?></em>

  </div>

</div>
<script>
$(document).ready( function() {
  $('#<?php print $sid ?>').on( 'keyup', function(e) {
    var _me = $(this);
    var t = _me.val();

    $('tr[site]').each( function( i, tr) {
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

    })

  })

  $('tr[site]').each( function( i, tr) {
    var _tr = $(tr);

    _tr.addClass('pointer').on( 'click', function( e) {
      if ( e.shiftKey) {
        return;

      }

      window.location.href = _brayworth_.url('sites/view/' + _tr.data('id'));

    })
    .on( 'contextmenu', function(e) {
      if ( e.shiftKey) {
        return;

      }

      e.stopPropagation(); e.preventDefault();

      _brayworth_.hideContexts();

      let context = _brayworth_.context();
      let updated = _brayworth_.moment( _tr.data('updated'));
      let duration = moment.duration( _brayworth_.moment().diff( updated));

      if ( duration.asMonths() > 1) {
        context.append( $('<a href="#"><i class="fa fa-trash" />delete</a>').on( 'click', function(e) {
          e.stopPropagation(); e.preventDefault();

          context.close();

          _brayworth_.modal({
            title : 'confirm',
            text : 'Are you Sure ?',
            width : 300,
            buttons : {
              'Yes - Delete' : function() {
                this.close();
                hourglass.on();

                _brayworth_.post({
                  url : _brayworth_.url('sites'),
                  data : {
                    action : 'delete',
                    id : _tr.data('id'),

                  }

                })
                .then( function(d) {
                  _brayworth_.growl(d);
                  if ( 'ack' == d.response) {
                    _tr.remove();

                  }

                  hourglass.off()

                });

              }

            }

          });

        }));

        context.open( e);

      }

    });

  });

  /*--[ a CSV download icon ]--*/
  var sitesTable = $('table[sites-list]');
  if ( sitesTable.length == 1) {
    $('<i class="fa fa-fw fa-table noprint pointer pull-right" title="download as CSV" />')
    .on( 'click', function( e) {
      _ed_.csv.call( sitesTable, 'sites-list.csv');
    })
    .insertBefore( sitesTable);

  }

})
</script>
