<?php

/**
 *	@author:	a4p ASD / Andreas Dorner
 *	@company:	apps4print / page one GmbH, Nürnberg, Germany
 *
 *
 *	@version:	1.0.1
 *	@date:		25.06.2014
 *
 *
 * a4p_subdomain_themes__oxconfig.php
 *
 * apps4print - subdomain theme module for OXID eShop
 *
 */

// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------

class a4p_subdomain_themes__oxconfig extends a4p_subdomain_themes__oxconfig_parent {
	
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
		///*
		$o_oxModule								= oxNew( "oxModule" );
		$o_oxModule->load( "a4p_debug_log" );
		if ( $o_oxModule->isActive() ) {
			$this->o_a4p_debug_log				= oxNew( "a4p_debug_log" );
			$this->o_a4p_debug_log->a4p_debug_log_init( true, __CLASS__ . ".txt", null );
		}
		//*/
		// ------------------------------------------------------------------------------------------------
		
		
		// ------------------------------------------------------------------------------------------------
		if ( $this->o_a4p_debug_log ) {
		#	$this->o_a4p_debug_log->_log( "\$this->isAdmin()", $this->isAdmin(), __FILE__, __FUNCTION__, __LINE__ );
		#	$this->o_a4p_debug_log->_log( "\$_SERVER", $_SERVER, __FILE__, __FUNCTION__, __LINE__ );
		#	$this->o_a4p_debug_log->_log( __CLASS__ . "::getShopUrl( \$iLang = null, \$blAdmin = null )", "null", __FILE__, __FUNCTION__, __LINE__ );
		#	$this->o_a4p_debug_log->_log( "\$iLang", $iLang, __FILE__, __FUNCTION__, __LINE__ );
		#	$this->o_a4p_debug_log->_log( "\$blAdmin", $blAdmin, __FILE__, __FUNCTION__, __LINE__ );
		}
		
		
		// ------------------------------------------------------------------------------------------------
		// auf Adminseite nicht ändern
		if ( $this->isAdmin() ) {
						
			return parent::getShopUrl( $iLang, $blAdmin );
		}
		// ------------------------------------------------------------------------------------------------
		
		
		
		// ------------------------------------------------------------------------------------------------
		// aktuelles Theme
		$s_cur_theme							= $this->getConfigParam( "sTheme" );
		$s_cur_child_theme						= $this->getConfigParam( "sCustomTheme" );
		// ------------------------------------------------------------------------------------------------
		
		
		// ------------------------------------------------------------------------------------------------
		if ( $this->o_a4p_debug_log ) {
		#	$this->o_a4p_debug_log->_log( "\$s_cur_theme", $s_cur_theme, __FILE__, __FUNCTION__, __LINE__ );
		#	$this->o_a4p_debug_log->_log( "\$s_cur_child_theme", $s_cur_child_theme, __FILE__, __FUNCTION__, __LINE__ );
		}
		
		
		// ------------------------------------------------------------------------------------------------
		// URL in Subdomain, Domainname und Toplevel-Domain aufteilen
		$a_domain_explode						= $this->explode_domain();
		// ------------------------------------------------------------------------------------------------
		
		
		// ------------------------------------------------------------------------------------------------
		if ( $this->o_a4p_debug_log ) {
		#	$this->o_a4p_debug_log->_log( "\$a_domain_explode", $a_domain_explode, __FILE__, __FUNCTION__, __LINE__ );
		}
		
		
		// ------------------------------------------------------------------------------------------------
		// URL als Themename suchen
		$s_url_themename						= false;
		if ( !is_null( $a_domain_explode[ "subdomain" ] ) )
			$s_url_themename					= $a_domain_explode[ "subdomain" ];
		else
			$s_url_themename					= $a_domain_explode[ "domain" ];
		

		// ------------------------------------------------------------------------------------------------
		#if( $s_url_themename == $s_cur_theme )
		#	$s_url_themename					= false;

		
		// ------------------------------------------------------------------------------------------------
		if ( $this->o_a4p_debug_log ) {
		#	$this->o_a4p_debug_log->_log( "\$s_url_themename", $s_url_themename, __FILE__, __FUNCTION__, __LINE__ );
		}

		
		// ------------------------------------------------------------------------------------------------
		// prüfen, ob Subdomain als Theme existiert
		$o_oxTheme								= oxNew( "oxTheme" );
		if ( $s_url_themename ) {
		
			$b_oxTheme_exists					= $o_oxTheme->load( $s_url_themename );
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

			// ------------------------------------------------------------------------------------------------
			if ( $this->o_a4p_debug_log ) {
			#	$this->o_a4p_debug_log->_log( "\$s_parent_theme", $s_parent_theme, __FILE__, __FUNCTION__, __LINE__ );
			}

			if ( !is_null( $s_parent_theme ) ) {

				// Parent-Theme setzen
				$this->setConfigParam( "sTheme", $s_parent_theme );
				
				$s_child_theme					= $s_url_themename;
				
			} else {
				
				$this->setConfigParam( "sTheme", $s_url_themename );
			}
			
			
			// ------------------------------------------------------------------------------------------------
			// Child-Theme setzen
			if ( $s_child_theme ) {
				
				$this->setConfigParam( "sCustomTheme",	$s_child_theme );
				
			} else {
				
				$this->setConfigParam( "sCustomTheme",	"" );
				
			}

			
			// ------------------------------------------------------------------------------------------------
			if ( $this->o_a4p_debug_log ) {
			#	$this->o_a4p_debug_log->_log( "getConfigParam sTheme", $this->getConfigParam( "sTheme" ), __FILE__, __FUNCTION__, __LINE__ );
			#	$this->o_a4p_debug_log->_log( "getConfigParam sCustomTheme", $this->getConfigParam( "sCustomTheme" ), __FILE__, __FUNCTION__, __LINE__ );
			}

			
		}
		// ------------------------------------------------------------------------------------------------
		

		// ------------------------------------------------------------------------------------------------
		// Shop-URL zusammensetzen
		if ( $_SERVER[ "SERVER_PORT" ] == 80 )
			$s_server_protocol				= "http://";
		else if ( $_SERVER[ "SERVER_PORT" ] == 443 )
			$s_server_protocol				= "https://";
		else
			$s_server_protocol				= "http://";
		$s_shop_URL							= $s_server_protocol . $_SERVER[ "SERVER_NAME" ] . "/";
		
		
		// ------------------------------------------------------------------------------------------------
		// Shop-URL auf Subdomain setzen
		$this->setConfigParam( "sShopURL", $s_shop_URL );



		return parent::getShopUrl( $iLang, $blAdmin );
	}

	// ------------------------------------------------------------------------------------------------
	
	public function explode_domain() {
		
		
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
		#$a_domain_explode						= explode( ".", $s_url_reverse, 3 );
		$a_domain_explode						= array();
		list( $a_domain_explode[ "tld" ], $a_domain_explode[ "domain" ], $a_domain_explode[ "subdomain" ] )				= explode( ".", $s_url_reverse, 3 );
		
		
		return $a_domain_explode;
	}
	
	// ------------------------------------------------------------------------------------------------
	
}

// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------

?>