<?php
include "../mainconfig.php";
?>

<?php
//Mysql Information. This is the information for the site's database, NOT your ascent server's.
define(MYSQL_HOST,$db_host); // The mysql host for your webserver.
define(MYSQL_USER,$db_user); // Mysql username
define(MYSQL_PASS,$db_pass); // Mysql password
define(MYSQL_DATA,$donations_db_name); // Database the donation tables are in.

// Path information, EXTREMELY IMPORTANT that you do these right or this will not work.
define(SITE_URL,$site_url); // COMPLETE url to your web site, NO TRAILING SLASH!
define(SYS_PATH,"/Donations"); // Path to the directory this file is in, beginning with a slash.

// Currency information.
define(CURRENCY_CODE,$currency); // Currency code to be used by PayPal.
define(CURRENCY_CHAR,$currency_character); // Symbol representing your currency code.

// PayPal information. Use 'www.sandbox.paypal.com' if you wish to test with the sandbox.
define(PAYPAL_URL,$paypal_url); // Only change this for sandbox testing.
define(PAYPAL_EMAIL,$paypal_email); // The account that donations will go to.

// Mail information.
define(MAIL_SUBJECT,"Thank You"); // Subject of the reward mail.
define(MAIL_BODY,"Thank you for supporting our server! Here is your reward!"); // Mail message.

//Misc
define(ACP_USERNAME,$donate_acp_user); // Username to access the ACP
define(ACP_PASSWORD,$donate_acp_pass); // Password to access the ACP
?>