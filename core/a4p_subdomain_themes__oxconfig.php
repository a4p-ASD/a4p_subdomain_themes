<?php

/**
 *	@author:	a4p ASD / Andreas Dorner
 *	@company:	apps4print / page one GmbH, Nürnberg, Germany
 *
 *
 *	@version:	1.0.6
 *	@date:		29.07.2016
 *
 *
 * a4p_subdomain_themes__oxconfig.php
 *
 *	apps4print - a4p_subdomain_themes - Themes je (Sub-)Domain wechseln
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
	 * @param int	$iLang	language
	 * @param bool $blAdmin if admin
	 *
	 * @return string
	 */
	public function getShopUrl( $iLang = null, $blAdmin = null ) {


		// ------------------------------------------------------------------------------------------------
		// init a4p_debug_log
		//
			/*
		$o_oxModule								= oxNew( "oxModule" );
		$o_oxModule->load( "a4p_debug_log" );
		if ( $o_oxModule->isActive() ) {
			$this->o_a4p_debug_log				= oxNew( "a4p_debug_log" );
			$this->o_a4p_debug_log->a4p_debug_log_init( true, __CLASS__ . ".txt", null );
		}
		//*/
		// ------------------------------------------------------------------------------------------------


		// ------------------------------------------------------------------------------------------------
		// auf Adminseite nicht ändern
		if ( $this->isAdmin() || $blAdmin ) {

			return parent::getShopUrl( $iLang, $blAdmin );
		}
		// ------------------------------------------------------------------------------------------------



		// ------------------------------------------------------------------------------------------------
		// aktuelles Themes
		$s_cur_theme							= $this->getConfigParam( "sTheme" );
		$s_cur_child_theme						= $this->getConfigParam( "sCustomTheme" );
		// ------------------------------------------------------------------------------------------------


		// ------------------------------------------------------------------------------------------------
		// URL in Subdomain, Domainname und Toplevel-Domain aufteilen
		$a_domain_explode						= $this->_explode_domain();
		// ------------------------------------------------------------------------------------------------


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
		// (Child-)Theme für (Sub-)Domain setzen
		$this->_set_theme( $s_url_themename );
		// ------------------------------------------------------------------------------------------------



		// ------------------------------------------------------------------------------------------------
		// Shop-URL auf aktuelle Domain setzen
		$this->_set_shopUrl();
		// ------------------------------------------------------------------------------------------------



		return parent::getShopUrl( $iLang, $blAdmin );
	}

	// ------------------------------------------------------------------------------------------------

	/**
	 * Returns config sSSLShopURL or sMallSSLShopURL if secondary shop
	 *
	 * @param int $iLang language (default is null)
	 *
	 * @return string
	 */
	public function getSslShopUrl( $iLang = null ) {


		// ------------------------------------------------------------------------------------------------
		// auf Adminseite nicht ändern
		if ( $this->isAdmin() ) {

			return parent::getSslShopUrl( $iLang );
		}
		// ------------------------------------------------------------------------------------------------


		// ------------------------------------------------------------------------------------------------
		// Shop-URL auf aktuelle Domain setzen
		$this->_set_SSLshopUrl();
		// ------------------------------------------------------------------------------------------------


		return parent::getSslShopUrl( $iLang );
	}

	// ------------------------------------------------------------------------------------------------

	protected function _set_theme( $s_themeName ) {


		// ------------------------------------------------------------------------------------------------
		// prüfen, ob Theme existiert
		$o_oxTheme								= oxNew( "oxTheme" );
		if ( $s_themeName ) {

			$b_oxTheme_exists					= $o_oxTheme->load( $s_themeName );
		}
		// ------------------------------------------------------------------------------------------------


		// ------------------------------------------------------------------------------------------------
		// Theme temporär setzen
		if ( $b_oxTheme_exists ) {

			$s_parent_theme						= null;
			$s_child_theme						= false;

			// ------------------------------------------------------------------------------------------------
			// Parent-Theme suchen
			$s_parent_theme						= $o_oxTheme->getInfo( "parentTheme" );


			// ------------------------------------------------------------------------------------------------
			// Parent-Theme setzen
			if ( !is_null( $s_parent_theme ) ) {

				// Parent-Theme setzen
				$this->setConfigParam( "sTheme", $s_parent_theme );

				$s_child_theme					= $s_themeName;

			} else {

				$this->setConfigParam( "sTheme", $s_themeName );
			}


			// ------------------------------------------------------------------------------------------------
			// Child-Theme setzen
			if ( $s_child_theme ) {

				$this->setConfigParam( "sCustomTheme",	$s_child_theme );

			} else {

				$this->setConfigParam( "sCustomTheme",	"" );

			}


		}
		// ------------------------------------------------------------------------------------------------


	}

	// ------------------------------------------------------------------------------------------------

	protected function _set_shopUrl() {


		$o_oxutilsserver						= oxRegistry::get( "oxUtilsServer" );


		$s_shop_URL__config						= $this->getConfigParam( "sShopURL" );


		// ------------------------------------------------------------------------------------------------
		// Shop-URL zusammensetzen
		/*
		if ( $_SER VER[ "SERVER_PORT" ] == 80 )
			$s_server_protocol					= "http://";
		else if ( $_SER VER[ "SERVER_PORT" ] == 443 )
			$s_server_protocol					= "https://";
		else
			$s_server_protocol					= "http://";
		*/
		$s_server_protocol						= "http://";
		#if ( $this->_checkSsl() )
		#	$s_server_protocol					= "https://";
		
		#if ( substr( $s_shop_URL__config, 0, 8 ) === "https://" )		// 6ms
		if ( $s_shop_URL__config[4] === "s" )							// 2ms
			$s_server_protocol					= "https://";


		#$s_shop_URL							= $s_server_protocol . $_SER VER[ "SERVER_NAME" ] . DIRECTORY_SEPARATOR;
		#$s_shop_URL								= $s_server_protocol . $this->_get_ser ver_url() . DIRECTORY_SEPARATOR;
		$s_shop_URL								= $s_server_protocol . $o_oxutilsserver->getServerVar( "HTTP_HOST" ) . DIRECTORY_SEPARATOR;

		// ------------------------------------------------------------------------------------------------
		// falls Shop in Unterorder liegt, diesen anhängen
		$s_domain_with_path						= substr( $s_shop_URL__config, strpos( $s_shop_URL__config, "//" ) + 2 );
		$s_server_path							= substr( $s_domain_with_path, strpos( $s_domain_with_path, "/" ) + 1 );

		$s_shop_URL								.= $s_server_path;


		// ------------------------------------------------------------------------------------------------
		// Shop-URL auf Subdomain setzen
		$this->setConfigParam( "sShopURL", $s_shop_URL );

	}

	// ------------------------------------------------------------------------------------------------

	protected function _set_SSLshopUrl() {


		$o_oxutilsserver						= oxRegistry::get( "oxUtilsServer" );


		$s_SSLshop_URL__config					= $this->getConfigParam( "sSSLShopURL" );

		// nur neu setzen, wenn auch Wert eingetragen ist
		if ( !is_null( $s_SSLshop_URL__config ) ) {


			// ------------------------------------------------------------------------------------------------
			// Shop-URL zusammensetzen
			$s_server_protocol						= "https://";


			#$s_shop_URL							= $s_server_protocol . $_SER VER[ "SERVER_NAME" ] . DIRECTORY_SEPARATOR;
			#$s_SSLshop_URL							= $s_server_protocol . $this->_get_ser ver_url() . DIRECTORY_SEPARATOR;
			$s_SSLshop_URL							= $s_server_protocol . $o_oxutilsserver->getServerVar( "HTTP_HOST" ) . DIRECTORY_SEPARATOR;

			// ------------------------------------------------------------------------------------------------
			// falls Shop in Unterorder liegt, diesen anhängen
			$s_domain_with_path						= substr( $s_SSLshop_URL__config, strpos( $s_SSLshop_URL__config, "//" ) + 2 );
			$s_server_path							= substr( $s_domain_with_path, strpos( $s_domain_with_path, "/" ) + 1 );

			$s_SSLshop_URL							.= $s_server_path;


			// ------------------------------------------------------------------------------------------------
			// Shop-URL auf Subdomain setzen
			$this->setConfigParam( "sSSLShopURL", $s_SSLshop_URL );

		}

	}

	// ------------------------------------------------------------------------------------------------

	protected function _explode_domain() {

		// ------------------------------------------------------------------------------------------------
		// URL in Subdomain, Domainname und Toplevel-Domain aufteilen
		// ------------------------------------------------------------------------------------------------

		$o_oxutilsserver						= oxRegistry::get( "oxUtilsServer" );


		// z.B. demo.shop.apps4print.com
		#$a_url_explode							= explode( ".", $_SER VER[ "SERVER_NAME" ] );
		#$a_url_explode							= explode( ".", $this->_get_ser ver_url() );
		$a_url_explode							= explode( ".", $o_oxutilsserver->getServerVar( "HTTP_HOST" ) );

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
