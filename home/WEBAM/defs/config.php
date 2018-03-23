<?php
include "../mainconfig.php";
?>
<?php
/*
 * Project Name: WeBAM (web aspire manager)
 * Date: 31.01.2008 revised version (1.72)
 * Author: SixSixNine
 * Copyright: SixSixNine
 * Email: *****
 * License: GNU General Public License (GPL)
 * Updated/Edited for Ascent: gmaze 
 * Updated/Edited for Aspire: Tekkeryole aka WHOS and Tronyc
 */
//--------------------------------------------------------------- Database Settings -----------------------------------------------------------------//

  //---------- MySQL configuration ----------//
    $dbhost = $db_host; //MySQL host name (usually localhost)
    $dbuser = $db_user;	//MySQL user name (usually root)
    $dbpass = $db_pass;	//MySQL password (pass used for db access)
    $acct_db = $logon_db_name; //account database name
    $char_db = $logon_db_name; //character database name
    $world_db = $world_db_name; //world database name
  //-----------------------------------------//
  //---------- Game server configuration ----------//
    $ascent_rev = $site_title; //set ascent revision
    $db_rev = "ArcEmu"; //set database revision
    $server = $realm_address; //address of your game server(same as your realmlist.wtf [note:do not include the set realmlist part])
    $port = 3306;	//server port
  //-----------------------------------------------//
  //---------- Backup configuration ----------//
    $backup_dir = "./backup"; //make sure webserver has permission to write/read it!
    //list of tables from account db that will be saved on backup

    //list of tables in character db that will be saved on backup
    $tables_backup_char = $tables_backup_logon;
  //------------------------------------------//

//---------------------------------------------------------------------------------------------------------------------------------------------------//


//--------------------------------------------------------------- Website Settings ------------------------------------------------------------------//

  //---------- Global Settings ----------//
    $url = $url_webam; //address of your website plus path to webam (ex:www.google.com/Aspire-WEBAM)
    $realm_name = $realm_name; //set realm name
    $stats = false; //true = parsing main index page from ascents stats.xml dump
    $timezone = $timezone; //set your timezone here (will be three cap. letters) ex: EST, UTC, PST, ...
  //-------------------------------------//
  //---------- Layout configuration ----------//
    $title = $title_webam; //page title
    $itemperpage = 15; //number of items to display per page
    $messperpage = 5; //number of motd's to display per page
  //------------------------------------------//
  //---------- New account creation options ----------//
    $disable_acc_creation = false; //false = allow new accounts to be created
    $validate_mail_host = false; //false = do not use email validation for account creation
    $limit_acc_per_ip = false; //true = limit to one account per IP
    $enable_captcha = true; //false = no security image check (enable for protection against 'bot' registrations)
  //--------------------------------------------------//
  //---------- External Links ----------//
    $custom = "Links"; //title to display in header -->default set to links
    $link1 = $link1_webam; //format for links:<a target=\"_blank\" href=\"http://your.site.com/\">your site name</a>
    $link2 = $link2_webam; //insert your link where it says 'http://your.site.com', and your menu link name where it says 'your site name'
    $link3 = $link3_webam; //input links to external sites. forums, item/quest reference, add-ons, whatever you want  --><--
  //------------------------------------//
  //---------- Mail configuration ----------//
    //php mail config
    $admin_mail = "email@domain.com"; //receiving email address
    $mailer_type = "smtp"; //("mail", "sendmail", "smtp")
    $from_mail = "email@localhost.com"; //all emails will be sent from this email
    //smtp mail config
    $smtp_host = "localhost"; //mailer host
    $smtp_port = 25; //mailer port
    $smtp_username = "mail@domain.com"; //mailer user name
    $smtp_password = "mailpassword"; //mailer password
  //----------------------------------------//
  //---------- override PHP error reporting ----------//
    $debug = true; //true will set error reporting to E_ALL ^ E_NOTICE
  //--------------------------------------------------//

//---------------------------------------------------------------------------------------------------------------------------------------------------//

?>
