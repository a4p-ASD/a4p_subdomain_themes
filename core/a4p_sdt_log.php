<?php

/**
 *	@author:	a4p ASD / Andreas Dorner
 *	@company:	apps4print / page one GmbH, Nürnberg, Germany
 *
 *
 *	@version:	1.0.0
 *	@date:		12.07.2013
 *
 *
 * a4p_log_class.php
 *
 * apps4print - a4p_classes
 *
 */


/**
 * @desc
 *
 *
 *
 * @requirements
 *
 *
 *
 * @todo
 * 
 * 		rotate mit anderer endung
 * 
 * 		ausgabe plain/html
 * 
 * 		mail?
 *
 */


// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------

class a4p_sdt_log {

	// ------------------------------------------------------------------------------------------------
	// ------------------------------------------------------------------------------------------------

	private $b_debug			= null;
	private $s_logFileABS		= null;
	private $i_logFileMaxSize	= null;
	
	// ------------------------------------------------------------------------------------------------
	// ------------------------------------------------------------------------------------------------
	
	public function __construct( $b_debug = null, $s_logFileABS = null, $i_logFileMaxSize = null ) {
		
		$this->b_debug			= $b_debug;
		
		$this->s_logFileABS		= $s_logFileABS;
		
		$this->i_logFileMaxSize	= $i_logFileMaxSize;
		
	}
	
	// ------------------------------------------------------------------------------------------------
	
	/**
	 * 
	 * @param string $s_text
	 * @param unknown $_var			Ausgabe per var_dump(); wird bei Wert "null" nicht ausgegeben
	 * @param string $s__FILE__
	 * @param string $s__FUNCTION__
	 * @param int $i__LINE__
	 */
	public function _debug( $s_text, $_var, $s__FILE__ = null, $s__FUNCTION__ = null, $i__LINE__ = null, $b_with_backtrace = false ) {
		
		if ( $this->b_debug ) {
			
			ob_start();
			
			echo "<hr>";
			echo "<pre>\n";
			
			// __FILE__ | __FUNCTION | __LINE__
			echo "<small>" . $s__FILE__ . " | " . $s__FUNCTION__ . " | " . $i__LINE__ . "</small>\n";
			
			// Text
			echo "<b>" . $s_text . "</b>\n";
			
			// Variable
			if ( $_var !== "null" ) {
				echo "<small>\n";
				var_dump( $_var );
				echo "</small>\n";
			}

			echo "</pre>\n";
			
			
			// ------------------------------------------------------------------------------------------------
			// backtrace
			if ( $b_with_backtrace )
				debug_print_backtrace();
			
			
			echo "<hr>\n";
			
			$content = ob_get_contents();
			ob_end_clean();
			
			
			echo $content;
		}
	}
	
	// ------------------------------------------------------------------------------------------------
	
	/**
	 * 
	 * @param string $s_text
	 * @param unknown $_var			Ausgabe per var_dump(); wird bei Wert "null" nicht ausgegeben
	 * @param string $s__FILE__
	 * @param string $s__FUNCTION__
	 * @param int $i__LINE__
	 */
	public function _log( $s_text, $_var, $s__FILE__ = null, $s__FUNCTION__ = null, $i__LINE__ = null, $b_with_backtrace = false ) {
		
		if ( !is_null( $this->s_logFileABS ) ) {
			

			// ------------------------------------------------------------------------------------------------
			// Log-Ausgabe
			ob_start();
			
			#echo "\n";
			echo "\n";
				
			echo str_repeat( "=", 100 );
			
			// Datum
			echo date( "d-m-Y H:i:s" );
			echo "\n";
			
			// __FILE__ | __FUNCTION | __LINE__
			echo $s__FILE__ . " | " . $s__FUNCTION__ . " | " . $i__LINE__;
			echo "\n";
				
			// Text
			echo $s_text;
			echo "\n";
				
			// Variable
			if ( $_var !== "null" ) {
				var_dump( $_var );
				echo "\n";
			}
			
			
			// ------------------------------------------------------------------------------------------------
			// backtrace
			if ( $b_with_backtrace )
				debug_print_backtrace();
			
			
			$content = ob_get_contents();
			ob_end_clean();

			// ------------------------------------------------------------------------------------------------
			// Ordner prüfen und evtl. anlegen
			$this->_checkFolder();
			
			// ------------------------------------------------------------------------------------------------
			// log rotieren
			$this->rotateLog();			
			
			// ------------------------------------------------------------------------------------------------
			// Log schreiben
			file_put_contents( $this->s_logFileABS, $content, FILE_APPEND );
			
		}
		
	}
	
	// ------------------------------------------------------------------------------------------------
	
	/**
	 * 
	 * @desc
	 * 
	 * 		sets debugging
	 * 
	 * @param unknown $b_debug
	 */
	public function setDebug( $b_debug ) {
		
		$this->b_debug = $b_debug;
	}

	// ------------------------------------------------------------------------------------------------
	
	/*
	 * @desc
	 * 
	 * 		sets s_logFileABS and enables file-logging
	 */
	public function setLog( $s_logFileABS ) {
		
		$this->s_logFileABS = $s_logFileABS;
		
		$s_logFilePATH = pathinfo( $this->s_logFileABS, PATHINFO_DIRNAME );
		if ( !file_exists( $s_logFilePATH ) )
			mkdir( $s_logFilePATH, 0777, true );
		
	}
	
	// ------------------------------------------------------------------------------------------------
	
	/**
	 * @desc
	 * 
	 * 		rotates logfile if s_logFileABS and i_logFileMaxSize is set
	 */
	private function rotateLog() {
		
		// ------------------------------------------------------------------------------------------------
		// nur wenn Variablen gesetzt sind
		if ( !is_null( $this->i_logFileMaxSize ) && !is_null( $this->s_logFileABS ) && file_exists( $this->s_logFileABS ) ) {
			
			$i_logSize					= filesize( $this->s_logFileABS );
			
			// ------------------------------------------------------------------------------------------------
			if ( $i_logSize > $this->i_logFileMaxSize ) {
				

				// ------------------------------------------------------------------------------------------------
				// rotate ausführen
				$this->_rotateLog();
				// ------------------------------------------------------------------------------------------------

			}
			
		}
	
	}

	// ------------------------------------------------------------------------------------------------
	
	/**
	 * 
	 */
	public function forceRotate() {
	
		$this->_rotateLog();
		
	}
	
	// ------------------------------------------------------------------------------------------------
	
	private function _rotateLog() {
		
		// ------------------------------------------------------------------------------------------------
		// nur wenn Variablen gesetzt sind
		if ( !is_null( $this->s_logFileABS ) && file_exists( $this->s_logFileABS ) ) {
				
			// ------------------------------------------------------------------------------------------------
			// Archiv-Dateiname ( *.1.log )
			$a_pathinfo				= pathinfo( $this->s_logFileABS );
			$i						= 1;
			$s_archiveLogName		= $a_pathinfo[ "dirname" ] . "/" . $a_pathinfo[ "filename" ] . "__" . $i . "." . $a_pathinfo[ "extension" ];
	
			// ------------------------------------------------------------------------------------------------
			// "freien" Archiv-Dateinamen ermitteln ( *.1, *.2 )
			while ( file_exists( $s_archiveLogName ) ) {
					
				$i++;
				$s_archiveLogName	= $a_pathinfo[ "dirname" ] . "/" . $a_pathinfo[ "filename" ] . "__" . $i . "." . $a_pathinfo[ "extension" ];
			}
	
			// ------------------------------------------------------------------------------------------------
			// Datei umbenennen
			$res = rename( $this->s_logFileABS, $s_archiveLogName );
	
			#$this->_debug( "log rotated", "null", __FILE__, __FUNCTION__, __LINE__ );
				
		}
		
	}
	
	// ------------------------------------------------------------------------------------------------
	
	/**
	 * @desc
	 * 
	 *  	prüft ob der Ordner für die Logdatei existiert - falls nicht wird er angelegt
	 *  
	 */
	private function _checkFolder() {
		
		if ( !is_null( $this->s_logFileABS ) ) {
			
			$s_folderABS		= pathinfo( $this->s_logFileABS, PATHINFO_DIRNAME );
			if ( !file_exists( $s_folderABS ) ) {
				mkdir( $s_folderABS, 0777, true );
			}
			
		}
		
	}	
	
	// ------------------------------------------------------------------------------------------------
	
}

// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------

?>
