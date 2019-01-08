<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		<a href="http://creativecommons.org/licenses/by/4.0/"></a>
	*/ ?>

<h3>EasyDose Account Backend</h3>

<p>
  <em>we can have some news here .. anyone ...</em>
</p>

<?php if ( currentUser::isAdmin()) {  ?>
<h1>About EasyDose-API</h1>

<h4>Sites</h4>
<p>
  Sites are collectively all the workstations that use easydose, a single
  site may have many workstations that access a database.
</p>

<h4>Pharmacy Database(s)</h4>
<p>
  Each workstation accesses a Pharmacy Database using the EasyDose client software
  according to the rules of the License. Each Pharmacy Database is uniquely identified
  by a Globally Unique Identifier (GUID) which is generated when the Database
  initially contacts my.easydose.net.au.
</p>

<h4>Account</h4>
<p>
  Within my.easydose.net.au accounts are established. The Pharmacy Database is linked
  to an account, each account may have only one Pharmacy Database. The license
  purchased by that account is the license that is exposed to the EasyDose
  client software.
</p>
<p>
  <strong>User</strong> = <strong>Account</strong>. These terms are ubiquitos. A
  user has an account.
</p>

<h4>License</h4>
<p>
  A License is calculated from the FREE license which kicks easydose off.
  The default FREE license is <strong>easydoseFREE</strong>. Extensions to the
  License are calculated based on the products which are attached to paid invoices.
</p>

<h4>Invoices</h4>
<p>
  Invoices group products together to contribute to a license. e.g. 1 x EasyDose
  OPEN product + 1 x 1 additional EasyDose workstation groups together for an Open
  EasyDose License with 2 workstations.
</p>
<p>
  Invoices have status (they are in a state)
  <ul>
    <li>No Status (blank state)</li>
    <li>Sent</li>
    <li>Approved <em>(final state : no payment required because it was either
      paid or free)</em></li>

  </ul>

</p>

<?php } ?>

<h4>Status</h4>
<p>Released</p>

<p>
  Please report any issues to <a href="mailto:<?php print \config::$SUPPORT_EMAIL ?>?subject=EasyDose Backend" target="_blank">
    <?php printf( "%s &lt;%s&gt;", \config::$SUPPORT_NAME, \config::$SUPPORT_EMAIL )?>
  </a>

</p>
