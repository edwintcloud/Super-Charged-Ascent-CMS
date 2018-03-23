<?php
	/*
	 * 2007 (c) -={ MikeP }=-
	 * TSV data files loader
	*/
	
	class CTSV {
		private static $TSV; // will hold all instances of loaded TSV files. Made static to avoid memory consumption
		
		private function __construct(){
		}
		
		private function __clone(){
			trigger_error('Cloning CTSV is not allowed.', E_USER_ERROR);
		}
		
		// loads TSV file from disk
		private static function loadTSV($name){
			// check if global container initialized
			if(!isset(self::$TSV)) self::$TSV = array();
			// now check if global container has loaded instance of required TSV file.
			if(!isset(self::$TSV[$name])){
				// load tsv file from disk, assuming the first column to be a key into the array
				$result = array();
				$fd = fopen('include/'.$name.'.tsv','r');
				if(!$fd) throw new Exception('TSV data file '.$name.'.tsv is not available');
				
				while($s = fgets($fd)){
					$s = trim($s);
					if(strlen($s) == 0) continue;
					$a = explode("\t",$s);
					$result[$a[0]] = $a;
				}
				fclose($fd);
				self::$TSV[$name] = $result;
			}
		}
		
		// lookup specified entry in TSV database, then return entry if found, false otherwise
		public static function lookupTSV($name,$entry){
			// just make sure the file is loaded
			CTSV::loadTSV($name);
			if(!isset(self::$TSV[$name][$entry])) return false;
			return self::$TSV[$name][$entry];
		}
		
		
	}

?>