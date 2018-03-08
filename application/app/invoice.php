<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/
	*/

use dvc\html;

class invoice {
  protected $sys, $account, $invoice;

  function __construct( $sys, $account, $invoice) {
    $this->sys = $sys;
    $this->account = $account;
    $this->invoice = $invoice;

  }

  function render() {

    /*-- -------------[ head of invoice ]------------- -- -
    +---------------------+---------------------+
    |                     | Company Details     |
    +---------------------+---------------------+
    | Customer Details    |                     |
    +---------------------+---------------------+
    --*/

    $thead = new html\table("table table-sm borderless m-0");

    $tr = $thead->tr();
    $tr->td('&nbsp;', ['style' => 'width: 50%;']);
    $td = $tr->td( NULL, ['style' => 'width: 50%;']);

    $td->appendChild( new html\div( $this->sys->name));
    $td->appendChild( new html\div( $this->sys->street));
    $td->appendChild( new html\div( $this->sys->town));
    $td->appendChild( new html\div( sprintf( '%s %s', $this->sys->state, $this->sys->postcode)));
    $td->appendChild( new html\div( sprintf('ABN: %s', $this->sys->abn)));

    $tr = $thead->tr();
    $td = $tr->td( NULL, ['style' => 'width: 50%;']);
    $tr->td('&nbsp;', ['style' => 'width: 50%;']);

    $td->appendChild( new html\div( $this->account->name));
    $td->appendChild( new html\div( $this->account->business_name));
    $td->appendChild( new html\div( $this->account->street));
    $td->appendChild( new html\div( sprintf( '%s, %s %s', $this->account->town,
                      $this->account->state, $this->account->postcode )));
    $td->appendChild( new html\div( sprintf('ABN: %s', $this->account->abn)));
    $td->appendChild( new html\div( $this->account->email));

    $tr = $thead->tr();
    $tr->td( new html\div( sprintf('Invoice Number: # %s', $this->invoice->id)));
    $tr->td( new html\div( sprintf('Invoice Date: # %s', date( \config::$DATE_FORMAT, strtotime($this->invoice->created)))));

    /*-- ---[ body of invoice ]---
    +--------------------+----------+----------+
    | Description        | Rate     | Term     |
    +--------------------+----------+----------+
    | EasyDose 10        | 550      | Year     |
    +--------------------+----------+----------+
    | Total              | 550      |          |
    | Includes GST       |  50      |          |
    +--------------------+----------+----------+
    --*/

    $tbody = new html\table("table borderless");
    $tr = $tbody->head()->tr();
    $tr->td('Description');
    $tr->td('Rate',['class' => 'text-right']);
    $tr->td('Term',['class' => 'text-right']);

    foreach ( $this->invoice->lines as $dto) {
      $tr = $tbody->tr();
      $tr->td( sprintf( '%s<br />%s', $dto->name, $dto->description));
      $tr->td( number_format( $dto->rate, 2), ['class' => 'text-right']);
      $tr->td($dto->term, ['class' => 'text-right']);

    }	// foreach ( $this->invoice->lines as $dto)

    $tr = $tbody->tr();
    $tr->td( '<strong>Total:</strong>',[ 'style' => 'border-top: 6px double #dee2e6;']);
    $tr->td( sprintf( '<strong>%s</strong>', number_format( $this->invoice->total, 2)),
      [ 'style' => 'border-top: 6px double #dee2e6;', 'class' => 'text-right']);
    $tr->td( '&nbsp;',[ 'style' => 'border-top: 6px double #dee2e6;']);

    $tr = $tbody->tr();
    $tr->td( 'Total includes GST:');
    $tr->td( number_format( $this->invoice->tax, 2), [ 'class' => 'text-right']);
    $tr->td( '&nbsp;');

    /*-- ---[ foot of invoice ]---
    +-------------------------------------------+
    | text                                      |
    +-------------------------------------------+
    --*/
    $tfoot = new html\table("table borderless");
    $tr = $tfoot->tr();
    $td = $tr->td('<div>
          <strong>year</strong>

        </div>

        <p>
          Products with a term of <em>year</em> are valid 1 year from the payment date.
          Where the product is an extension, the product will be valid 1 year from the
          expiry date of the product
        </p>', ['class' => 'small']);

    $ret = new html\table("table borderless table-invoice");
    $ret->tr()->td( $thead);
    $ret->tr()->td( $tbody);
    $ret->tr()->td( $tfoot);

    // return ( $ret);

    $html = $ret->render( TRUE);
    // create instance
    $cssToInlineStyles = new TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;
    $parts = [
      __DIR__,
      'public',
      'css',
      'minimum.css'
    ];
    $css = file_get_contents( implode( DIRECTORY_SEPARATOR, $parts));

    // output
    $html = $cssToInlineStyles->convert( $html, $css);
    return ( $html);


  }

}
