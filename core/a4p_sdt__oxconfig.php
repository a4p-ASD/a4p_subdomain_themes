<?php

/**
 *	@author:	a4p ASD / Andreas Dorner
 *	@company:	apps4print / page one GmbH, NÃ¼rnberg, Germany
 *
 *
 *	@version:	1.0.0
 *	@date:		16.10.2013
 *
 *
 * a4p_sdt__oxconfig.php
 *
 * apps4print - subdomain theme module for OXID eShop
 *
 */

// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------

class a4p_sdt__oxconfig extends a4p_sdt__oxconfig_parent {
	
	// ------------------------------------------------------------------------------------------------

	/**
	 * Returns config sShopURL or sMallShopURL if secondary shop
	 *
	 * @param int  $iLang   language
	 * @param bool $blAdmin if admin
	 *
	 * @return string
	 */
	public function getShopUrl( $iLang = null, $blAdmin = null ) {


		// ------------------------------------------------------------------------------------------------
		// Module-Pfad laden
		$o_oxviewconfig							= oxNew( "oxViewConfig" );
		$s_module_path_ABS						= $o_oxviewconfig->getModulePath( "a4p_subdomain_themes" );


		// ------------------------------------------------------------------------------------------------
		// Logdatei setzen
		$s_logFile_ABS							= $s_module_path_ABS . "_a4p_logs/a4p_sdt_module.log";

		#$o_a4p_log								= new a4p_sdt_log( true, "/var/www/vhosts/shop.scmcon.de/modules/apps4print/a4p_subdomain_themes/_a4p_logs/a4p_sdt_module.log", null );
		$o_a4p_log								= new a4p_sdt_log( true, $s_logFile_ABS, null );

		
		// ------------------------------------------------------------------------------------------------
		// URL in Subdomain, Domainname und Toplevel-Domain aufteilen
		// ------------------------------------------------------------------------------------------------
		
		// z.B. demo.shop.apps4print.com
		$a_url_explode							= explode( ".", $_SERVER[ "SERVER_NAME" ] );
		
		// umdrehen
		$a_url_reverse							= array_reverse( $a_url_explode );
		
		// umgekehrt zusammensetzen ( z.B. com.apps4print.shop.demo )
		$s_url_reverse							= implode( ".", $a_url_reverse );
		
		// Array mit 3 Keys ( [0] = tld, [1] = Domainname, [2] = Subdomain
		$a_domain_explode						= explode( ".", $s_url_reverse, 3 );
		$a_domain_explode						= array();
		list( $a_domain_explode[ "tld" ], $a_domain_explode[ "domain" ], $a_domain_explode[ "subdomain" ] )				= explode( ".", $s_url_reverse, 3 );
		
		
		
		// ------------------------------------------------------------------------------------------------
		// prüfen ob Subdomain als Theme existiert
		// ------------------------------------------------------------------------------------------------
		
		$oTheme									= oxNew( 'oxTheme' );
		$b_theme								= $oTheme->load( $a_domain_explode[ "subdomain" ] );
		#$o_a4p_log->_log( "\$b_theme", $b_theme, __FILE__, __FUNCTION__, __LINE__, false );


		if ( $b_theme ) {
			
			
			// ------------------------------------------------------------------------------------------------
			// Theme setzen
			$this->setConfigParam( 'sTheme', $a_domain_explode[ "subdomain" ] );
}			
			
			// ------------------------------------------------------------------------------------------------
			// Shop-URL zusammensetzen
			if ( $_SERVER[ "SERVER_PORT" ] == 80 )
				$s_server_protocol				= "http://";
			else if ( $_SERVER[ "SERVER_PORT" ] == 80 )
				$s_server_protocol				= "https://";
			else
				$s_server_protocol				= "http://";
				
			$s_shop_URL							= $s_server_protocol . $_SERVER[ "SERVER_NAME" ] . "/";
			
			
			// ------------------------------------------------------------------------------------------------
			// Shop-URL auf Subdomain setzen
			$this->setConfigParam( 'sShopURL', $s_shop_URL );

			
#		}
		
		
		/*
		if ( $_SERVER[ "SERVER_NAME" ] == "sub1.subshop.apps4print.com" ) {
		
			$this->setConfigParam( 'sTheme', "a4p_sub1" );
		
			$this->setConfigParam( 'sShopURL', "http://sub1.subshop.apps4print.com/" );
		
		} else if ( $_SERVER[ "SERVER_NAME" ] == "sub2.subshop.apps4print.com" ) {
		
			$this->setConfigParam( 'sTheme', "a4p_sub2" );
		
			$this->setConfigParam( 'sShopURL', "http://sub2.subshop.apps4print.com/" );
		
		}
		*/


		return parent::getShopUrl( $iLang, $blAdmin );
	}

	// ------------------------------------------------------------------------------------------------
	
}

// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------

?>