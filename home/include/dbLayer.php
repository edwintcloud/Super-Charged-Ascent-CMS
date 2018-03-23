<?php
	/*
	* 2007 (c) -={MikeP}=-
	* Database abstraction layer
	*/
	
	require_once('include/settings.php');
	
	class dbLayer {
		private $dbConn;
		private $result;
		
		private static $wdb;
		private static $cdb;
		private static $sdb;
		
		// constructor is private so that you can't use it without parameters
		private static function connect($login, $password, $host, $db){
			// connect to database here and select the database of interest
			$dbr = @mysql_connect($host,$login,$password,true);
			if(!$dbr) throw new Exception('Unable to connect to database server');
			// select the database
			if( ! (@mysql_select_db($db,$dbr)) ) throw new Exception('Database is not available.');
			return $dbr;
		}
		
		private function __construct(&$dbconn){
			$this->dbConn = $dbconn;
		}
		
		// get character database connection
		public static function getCharDB(){
			if(!isset(self::$cdb)){
				$s = gSettings::get();
				self::$cdb = dbLayer::connect($s->db_user, $s->db_password, $s->db_host, $s->db_cname);
			}
			return new dbLayer(self::$cdb);
		}
		
		// get world database connection
		public static function getWorldDB(){
			if(!isset(self::$wdb)){
				$s = gSettings::get();
				self::$wdb = dbLayer::connect($s->db_user, $s->db_password, $s->db_host, $s->db_wname);
			}
			return new dbLayer(self::$wdb);
		}
		
		// get site database connection
		public static function getSiteDB(){
			if(!isset(self::$sdb)){
				$s = gSettings::get();
				self::$sdb = dbLayer::connect($s->db_user, $s->db_password, $s->db_host, $s->db_sname);
			}
			return new dbLayer(self::$sdb);
		}
		
		// executes the query - returns true on success or throws an exception
		public function Execute($query){
			// check if we have results from previous query
			if( is_resource($this->result) ) @mysql_free_result($this->result);
			// now execute the query
			$this->result = @mysql_query($query,$this->dbConn);
			if( !$this->result ) throw new Exception('Execute query failed: '.mysql_error($this->dbConn));
			return true;
		}
		
		// fetches one result from the previous Execute call and returns an object.
		// returns FALSE on end of rows for SELECT or if result of Execute was not resource
		public function Fetch(){
			if( is_resource($this->result) ){
				return @mysql_fetch_object($this->result);
			}else{
				return false;
			}
		}
		
	}
?>