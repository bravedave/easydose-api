<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

  security  : admin

	*/ ?>
<div class="row py-1">
  <div class="col col-2 small">
    id
  </div>

  <div class="col col-10">
    <td><?php print $this->data->dto->id ?></td>

  </div>

</div>

<div class="row py-1">
  <div class="col col-2 small">
    guid
  </div>

  <div class="col col-10">
    <?php print $this->data->dto->guid ?>

  </div>

</div>

<div class="row">
  <div class="col col-2 py-1 small">
    account
  </div>

  <div class="col col-10">
    <?php
    if ( $this->data->account) {
      printf( '<a href="%s" class="btn btn-link pl-0">%s<i class="fa fa-fw fa-link"></i></a>', url::tostring('users/view/' . $this->data->account->id), $this->data->account->name);
      printf( '<button class="btn btn-sm btn-outline-primary" id="detach-account">detach account</button>');

    }
    else {
      printf( '<button class="btn btn-sm btn-outline-primary" id="assign-account">assign account</button>');

    }

    ?>
  </div>

</div>

<div class="row py-1">
  <div class="col col-2 small">
    created
  </div>

  <div class="col col-10">
    <?php print date( \config::$DATE_FORMAT, strtotime( $this->data->dto->created)) ?>

  </div>

</div>

<div class="row py-1">
  <div class="col col-2 small">
    updated
  </div>

  <div class="col col-10">
    <?php print date( \config::$DATE_FORMAT, strtotime( $this->data->dto->updated)) ?>

  </div>

</div>

<div class="row py-1">
  <div class="col col-2 small">
    sites
  </div>

  <div class="col col-10">
    <table class="table table-striped table-sm">
      <thead>
        <tr>
          <td>site</td>
          <td>workstation</td>
          <td>state</td>
          <td class="text-right">act/tot</td>
<?php     if ( !$this->data->account) { ?>
          <td>&nbsp;</td>
<?php     } // if ( !$this->data->account) ?>

        </tr>

      </thead>

      <tbody>
<?php while ($dto = $this->data->sites->dto()) {  ?>
        <tr data-id="<?php print $dto->id ?>" site>
          <td><?php print $dto->site ?></td>
          <td><?php print $dto->workstation ?></td>
          <td><?php print $dto->state ?></td>
          <td class="text-right"><?php print $dto->patientsActive ?>/<?php print $dto->patients ?></td>
<?php     if ( !$this->data->account) { ?>
          <td class="text-center"><a href="#" class="btn btn-pimary">Create Account</a></td>
<?php     } // if ( !$this->data->account) ?>

        </tr>

<?php } // while ($dto = $this->data->sites->dto())  ?>

      </tbody>

    </table>

  </div>

</div>

<div class="row py-1">
  <div class="col offset-2 col-10">
    <div class="form-check">
      <input type="checkbox" class="form-check-input"
      id="version-2-license-check"
      name="<?= uniqid('easydose') ?>"
      <?php if ( $this->data->dto->use_license) print 'checked'; ?> />

      <label class="form-check-label">
        Use this site for license (version 2)

      </label>

    </div>

  </div>

</div>

<div class="row py-1">
  <div class="col offset-2 col-10">
    <div class="form-check">
      <input type="checkbox" class="form-check-input"
      id="development-check"
      name="<?php print uniqid('easydose') ?>" <?php if ( $this->data->dto->development) print 'checked'; ?> />
      <label class="form-check-label">
        Developement Database

      </label>

    </div>

  </div>

</div>

<?php
  if ( $this->data->license) {  ?>

<div class="row pb-1">
  <div class="col col-2 small">
    license
  </div>

  <div class="col col-10">
    <table class="table table-striped table-sm">
      <colgroup>
          <col style="width: 5em" />
          <col />
          <col style="width: 4em" />
          <col style="width: 12em" />

      </colgroup>

      <thead>
        <tr>
          <td>type</td>
          <td>product</td>
          <td>wks</td>
          <td>expires</td>

        </tr>

      </thead>

      <tbody>
        <tr>
          <td><?php print $this->data->license->type; ?></td>
          <td><?php print $this->data->license->product; ?></td>
          <td><?php print $this->data->license->workstations; ?></td>
          <td><?php print strings::asShortDate( $this->data->license->expires); ?></td>

        </tr>

      </tbody>

    </table>

<?php
    // sys::dump( $this->data->dto, NULL, FALSE);
    ?>

  </div>

</div>

<?php
  } ?>

<div class="row pb-1">
  <div class="col col-2 small">
    license override
  </div>

  <div class="col col-10">
    <form class="form" method="post" action="<?php url::write('guid') ?>">
      <input type="hidden" name="id" value="<?php print $this->data->dto->id; ?>" />
      <table class="table table-striped table-sm">
        <colgroup>
          <col style="width: 5em" />
          <col />
          <col style="width: 4em" />
          <col style="width: 12em" />

        </colgroup>

        <thead>
          <tr>
            <td>&nbsp;</td>
            <td>product</td>
            <td>wks</td>
            <td>expires</td>

          </tr>

        </thead>

        <tbody>
          <tr>
            <td>&nbsp;</td>
            <td>
              <select class="form-control" name="grace_product">
                <option></option>
                <option value="easydose5" <?php if ( 'easydose5' == (string)$this->data->dto->grace_product) print "selected"; ?>>easydose5</option>
                <option value="easydose10" <?php if ( 'easydose10' == (string)$this->data->dto->grace_product) print "selected"; ?>>easydose10</option>
                <option value="easydoseOPEN" <?php if ( 'easydoseOPEN' == (string)$this->data->dto->grace_product) print "selected"; ?>>easydoseOPEN</option>

              </select>

            </td>
            <td>
              <input type="number" name="grace_workstations" class="form-control" value="<?php print $this->data->dto->grace_workstations ?>" />

            </td>
            <td>
              <input type="date" name="grace_expires" class="form-control" value="<?php print $this->data->dto->grace_expires ?>" />

            </td>

          </tr>

          <tr>
            <td>&nbsp;</td>
            <td colspan="3">
              <input type="submit" name="action" class='btn btn-primary' value="apply license override" />
              <input type="submit" name="action" class='btn btn-danger' value="remove license override" />

            </td>

          </tr>

        </tbody>

      </table>

      <?php
          // sys::dump( $this->data->dto, NULL, FALSE);
          ?>
    </form>

  </div>

</div>

<script>
$(document).ready( function() {
  $('tr[site]').each( function( i, tr) {
    var _tr = $(tr);
    var id = _tr.data( 'id');

    _tr
    .addClass('pointer')
    .on( 'click', function( e) {
      window.location.href = _brayworth_.url('sites/view/'+id);

    })
    .on( 'contextmenu', function( e) {
      if (e.shiftKey)
        return;

      e.stopPropagation(); e.preventDefault();

      var _context = _brayworth_.context();
      _context.append($('<a><i class="fa fa-link"></i>view</a>').attr('href',_brayworth_.url('sites/view/'+id)))
      _context.append($('<a href="#"><i class="fa fa-trash"></i>delete</a>').on('click', function(e) {
        e.stopPropagation(); e.preventDefault();

        _brayworth_.modal({
          title: 'confirm',
          text: 'Are you sure ?',
          buttons : {
            yes : function( e) {
              hourglass.on();
              window.location.href = _brayworth_.url( 'sites/remove/' + id + '/<?php print $this->data->dto->id ?>');

            }

          }

        });

        _context.close();

      }));

      _context.open( e);

    })

  });

  $('#assign-account').on('click', function(e) {
    e.stopPropagation();

    var fld = $('<input type="text" class="form-control" autocomplete="email" placeholder="@" />');

		var d = $('<div />');
		d.append( fld);

		var modal = _brayworth_.modal({
			title : 'email address',
			text : d,
			width : 400,
			onOpen : function() {
				fld.on('keypress', function( e) {
          if ( e.keyCode == 13) {
            var _fld = $(this);
            var email = _fld.val();
            if ( email.isEmail()) {

              modal.modal('close');

              _brayworth_.post({
                url : _brayworth_.url('api'),
                data : {
                  action : 'set-account',
                  email : email,
                  guid : '<?php print $this->data->dto->guid ?>',
                }

              })
              .then( function(d) {
                _brayworth_.growl(d);
                if ( 'ack' == d.response)
                  setTimeout( function() { window.location.reload()}, 500);

              })

            }

          }

				});

			},

		});

  });

  $('#version-2-license-check').on( 'change', function() {
    var _me = $(this);

    _brayworth_.post({
      url : _brayworth_.url('guid'),
      data : {
          action : 'use-version-2-license',
          value : _me.prop('checked') ? 1 : 0,
          id : <?php print $this->data->dto->id; ?>,

      }

    })
    .then( function(d) {
      _brayworth_.growl(d);

    });

  });

  $('#development-check').on( 'change', function() {
    var _me = $(this);

    _brayworth_.post({
      url : _brayworth_.url('guid'),
      data : {
          action : 'development-mark',
          value : _me.prop('checked') ? 1 : 0,
          id : <?php print $this->data->dto->id; ?>,

      }

    })
    .then( function(d) {
      _brayworth_.growl(d);

    });

  });

  $('#detach-account').on('click', function( e) {
    e.stopPropagation(); e.preventDefault();

    _brayworth_.modal({
      title: 'confirm',
      text: 'Are you sure ?',
      buttons : {
        yes : function( e) {
          hourglass.on();
          _brayworth_.post({
            url : _brayworth_.url('guid'),
            data : {
              action : 'detach-account',
              id : '<?php print $this->data->dto->id ?>',

            }

          })
          .then( function(d) {
            hourglass.on();
            _brayworth_.growl(d);
            if ( 'ack' == d.response) {
              setTimeout( function() { window.location.reload()}, 500);
            }

          });

        }

      }

    });

  });

})
</script>
