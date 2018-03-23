<?php
include "mainconfig.php";
?>

<?php
	/*
	 * 2007 (c) -={ MikeP }=-
	 * Settings class for setting up everything on the site.
	 * The class is a singleton so that we always have one and only instance of settings.
	 */
	
	class gSettings {
		// the only instance of the class
		private static $instance;
		
		private function __construct(){
			//
			// here we define all the settings
            //
			$this->db_user = $db_user;	// database user
			$this->db_password = $db_pass; // database password
			$this->db_host = $db_host; // database host:port
			$this->db_cname = $logon_db_name; // character database
			$this->db_wname = $world_db_name; // world database
			$this->db_sname = ''; //Will be used latertime
			$this->statfile = $stats_xml_loc;//Will be used latertime
			$this->realm = $realm_name;
		}
		
		private function __clone(){
			trigger_error('Clone is not allowed.', E_USER_ERROR);
		}
		
		// this is the only way to get properties:
		// $props = gSettings::get();
		public static function get(){
			if(!isset(self::$instance)){
				$c = __CLASS__;
				self::$instance = new $c;
			}
			return self::$instance;
		}
	}
	
?>