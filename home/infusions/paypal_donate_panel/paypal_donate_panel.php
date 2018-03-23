<?php
require_once INFUSIONS."paypal_donate_panel/paypal_config.php";

openside($paypal_title);
echo "
<center>
<form action='https://www.paypal.com/cgi-bin/webscr' method='post'>
<input type='hidden' name='cmd' value='_xclick'>
<input type='hidden' name='business' value='".$paypal_email_address."'>
<input type='hidden' name='item_name' value='".$paypal_name."'>
<input type='hidden' name='no_note' value='1'>
<input type='hidden' name='currency_code' value='".$paypal_currency."'>
<input type='hidden' name='tax' value='0'>
<input type='image' src='".INFUSIONS."paypal_donate_panel/paypal.gif' name='submit' alt='Make payments with PayPal - its fast, free and secure!' style='border:0px'>
</form>
</center>";
closeside();
?>