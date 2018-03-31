/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/
	*/
_ed_.datehelper = function( params) {
  let options = {
    date : _brayworth_.moment().format('YYYY-MM-DD'),
    element : $(this),
    parent : $(this).parent(),
    dateFormat : false,
    select : function( date) {
      let _el = $(this);

      if ( 'text' == options.element.prop('type')) {
        let m = _brayworth_.moment( date);
        options.element.val( m.format( options.dateFormat));
        // console.log( _el.text(), date, options.dateFormat);

      }

    }

  }

  $.extend( options, params);

  if ( !options.dateFormat) {
    let df = options.element.data('dateformat');
    if ( !!df) {
      if ( 'yyyy-mm-dd' == df) {
        options.dateFormat = 'YYYY-MM-DD';

      }
      else if ( 'dd/mm/yy' == df ) {
        options.dateFormat = 'L';

      }
      else {
        options.dateFormat = df;

      }

    }
    else {
      options.dateFormat = 'YYYY-MM-DD';

    }

  }

  let _el = $(this);

  let dtElement = function() {
    let m = _brayworth_.moment( options.date);

    let div = $('<div class="card date-helper" />');
    let box = {
      head : $('<div class="card-header bg-primary text-light py-1" />').appendTo(div),
      body : $('<div class="card-body py-1" />').appendTo(div),
      foot : $('<div class="card-footer py-1" />').appendTo(div),

    }

    let navMonth = function( i) {
      m.add( i, 'month');
      display( m.format('YYYY-MM-DD'), box);

    }

    let lastMonth = $('<a href="#">&lt;</a>').on( 'click', function( e) {
      e.stopPropagation(); e.preventDefault();
      navMonth( -1);

    });

    let nextMonth = $('<a href="#">&gt;</a>').on( 'click', function( e) {
      e.stopPropagation(); e.preventDefault();
      navMonth( 1);

    });

    let dow = ['S','M','T','W','T','F','S'];
    let r = $('<div class="row" />').appendTo( box.head)
    for ( let iD = 0; iD < 7; iD++) {
      $('<div class="col p-0 text-center" />').html(dow[iD]).appendTo(r);

    }

    r = $('<div class="row" />').appendTo( box.foot)
    $('<div class="col-1 p-0 text-center" />').append( lastMonth).appendTo(r);
    box.foot = $('<div class="col-10 p-0 text-center">---</div>').appendTo(r);
    $('<div class="col-1 p-0 text-center" />').append( nextMonth).appendTo(r);

    let display = function( seed, box ) {
      let m = _brayworth_.moment( seed);

      box.foot.html( m.format('MMM YYYY'));
      box.body.html( '');

      // console.log( 'display:', seed, m.format('YYYY-MM-01'));
      let start = _brayworth_.moment( m.format('YYYY-MM-01'));
      let startMonth = start.month();

      // console.log( 'display:', seed, m.format('YYYY-MM-01'), start.format('l'));

      for ( iW = 0; iW < 7; iW++) {
        if ( startMonth != start.month()) {
          break;

        }

        r = $('<div class="row" />').appendTo( box.body);
        for ( let iD = 0; iD < 7; iD++) {
          let cell = $('<div class="col p-0 text-center pointer" />').appendTo(r);
          if ( startMonth == start.month() && (start.date() > 1 || start.day() == iD)) {
            cell.data('date', start.format('YYYY-MM-DD')).html( start.date()).on( 'click', function( e) {
              e.stopPropagation(); e.preventDefault();
              options.select.call( this, $(this).data('date'));

            });
            start.add(1,'day');

          }

        }

      }

    }

    display( m.format('YYYY-MM-DD'), box)

    return (div);

  }

  let dt = dtElement();

  let c = $('<div style="position: relative; z-index: 1;" />').appendTo( options.parent);
  dt.css({
    'position' : 'absolute',
    'width' : '100%',
  })
  .appendTo( c);

  if ( 'text' == options.element.prop('type')) {
    options.element
    .on( 'focus.date-helper', function() {
      options.parent.addClass( 'date-helper-focused')

    })
    .on( 'blur.date-helper', function() {
      options.parent.removeClass( 'date-helper-focused')

    })

    // console.log( $('.date-helper', options.element));

    $('.date-helper', options.parent)
    .on( 'mouseover.date-helper', function() {
      // console.log( 'mouseover');
      options.parent.addClass( 'date-helper-mouseover')

    })
    .on( 'mouseout.date-helper', function() {
      // console.log( 'mouseout');
      options.parent.removeClass( 'date-helper-mouseover')

    })

    // console.log( 'text dude');

  }

  return dt;

}

$('[data-provide="date-helper"]').each( function( i, el) {
  // console.log( el);
  _ed_.datehelper.call( el);

});

;
