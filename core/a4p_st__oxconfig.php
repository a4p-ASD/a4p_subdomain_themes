<?php

/**
 *	@author:	a4p ASD / Andreas Dorner
 *	@company:	apps4print / page one GmbH, Nürnberg, Germany
 *
 *
 *	@version:	1.0.0
 *	@date:		16.10.2013
 *
 *
 * a4p_st__oxconfig.php
 *
 * apps4print - subdomain theme module for OXID eShop
 *
 */

// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------

class a4p_st__oxconfig extends a4p_st__oxconfig_parent {
	
	// ------------------------------------------------------------------------------------------------
	// ------------------------------------------------------------------------------------------------
	
	protected $o_a4p_debug_log					= null;
		
	// ------------------------------------------------------------------------------------------------
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
		// init a4p_debug_log
		$o_oxModule								= oxNew( "oxModule" );
		$o_oxModule->load( "a4p_debug_log" );
		if ( $o_oxModule->isActive() ) {
		
			$this->o_a4p_debug_log					= oxNew( "a4p_debug_log" );
			$this->o_a4p_debug_log->a4p_debug_log_init( true, __CLASS__ . ".txt", null );
		}
		// ------------------------------------------------------------------------------------------------
		
		
		// ------------------------------------------------------------------------------------------------
		if ( $this->o_a4p_debug_log ) {
		#	$s_cur_theme						= $this->getConfigParam( "sTheme" );
		#	$this->o_a4p_debug_log->_log( "\$s_cur_theme", $s_cur_theme, __FILE__, __FUNCTION__, __LINE__ );
		}
		
		
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
		if ( $this->o_a4p_debug_log ) {
		#	$this->o_a4p_debug_log->_log( "\$a_domain_explode", $a_domain_explode, __FILE__, __FUNCTION__, __LINE__ );
		}
		
		
		// ------------------------------------------------------------------------------------------------
		// prüfen, ob Subdomain als Theme existiert
		$o_oxTheme								= oxNew( "oxTheme" );
		if ( $a_domain_explode[ "subdomain" ] ) {
		
			$b_oxTheme_exists					= $o_oxTheme->load( $a_domain_explode[ "subdomain" ] );
		}
		// ------------------------------------------------------------------------------------------------

		
		// ------------------------------------------------------------------------------------------------
		// Theme temporär setzen
		if ( $b_oxTheme_exists ) {
			
			$s_parent_theme						= null;
			$s_child_theme						= false;
			
			// ------------------------------------------------------------------------------------------------
			// Parent-Theme suchen/setzen
			$s_parent_theme						= $o_oxTheme->getInfo( "parentTheme" );
			if ( !is_null( $s_parent_theme ) ) {

				// Parent-Theme setzen
				$this->setConfigParam( "sTheme", $s_parent_theme );
				
				$s_child_theme					= $a_domain_explode[ "subdomain" ];
				
			} else {
				
				$this->setConfigParam( "sTheme", $a_domain_explode[ "subdomain" ] );
			}
			
			
			// ------------------------------------------------------------------------------------------------
			// Child-Theme setzen
			if ( $s_child_theme ) {
				
				$this->setConfigParam( "sCustomTheme",	$s_child_theme );
				
			}
			
		}			
		// ------------------------------------------------------------------------------------------------
		

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



		return parent::getShopUrl( $iLang, $blAdmin );
	}

	// ------------------------------------------------------------------------------------------------
	
}

// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------

?>