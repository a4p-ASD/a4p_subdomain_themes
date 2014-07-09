<?php

/**
 *	@author:	a4p ASD / Andreas Dorner
 *	@company:	apps4print / page one GmbH, Nürnberg, Germany
 *
 *
 *	@version:	1.0.1
 *	@date:		03.07.2014
 *
 *
 *	a4p_theme_manager.php
 *
 * apps4print - subdomain theme module for OXID eShop
 *
 */

// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------

class a4p_theme_manager {
	
	// ------------------------------------------------------------------------------------------------
	// ------------------------------------------------------------------------------------------------
	
	protected $o_a4p_debug_log					= null;
	
	// ------------------------------------------------------------------------------------------------
	// ------------------------------------------------------------------------------------------------
	
	public function __construct() {
		
		
		// ------------------------------------------------------------------------------------------------
		// init a4p_debug_log
		$o_oxModule								= oxNew( "oxModule" );
		$o_oxModule->load( "a4p_debug_log" );
		if ( $o_oxModule->isActive() ) {
		
			$this->o_a4p_debug_log					= oxNew( "a4p_debug_log" );
			$this->o_a4p_debug_log->a4p_debug_log_init( true, __CLASS__ . ".txt", null );
		}
		// ------------------------------------------------------------------------------------------------
		
		
	}

	// ------------------------------------------------------------------------------------------------
	
	public function create_child_theme( $s_theme_id, $s_theme_name, $s_parentTheme_name ) {
		
		
		// ------------------------------------------------------------------------------------------------
		if ( $this->o_a4p_debug_log ) {
			$this->o_a4p_debug_log->_log( "create_child_theme( \$s_theme_name, \$s_parentTheme_name )", "null", __FILE__, __FUNCTION__, __LINE__ );
		}
		
		
		$s_shopDir								= oxRegistry::getConfig()->getConfigParam( "sShopDir" );
		$s_shopDir								= $this->_appendSlash( $s_shopDir );
		
		
		$o_oxViewConfig							= oxNew( "oxViewConfig" );
		$s_modulePATH							= $o_oxViewConfig->getModulePath( "a4p_subdomain_themes" );
		$s_modulePATH							= $this->_appendSlash( $s_modulePATH );
		
		
		// ------------------------------------------------------------------------------------------------
		// create folder in application/views/<theme>
		$s_shop_application_views				= $s_shopDir . "application" . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR;
		$s_theme_folder_application_views		= $s_shop_application_views . $s_theme_name;
		if ( !file_exists( $s_theme_folder_application_views ) ) {
			$b_ret_mkdir						= mkdir( $s_theme_folder_application_views, 0777, true );
		}
		
		
		// ------------------------------------------------------------------------------------------------
		// create folder in out/<theme> + /img
		$s_shop_folder_out						= $s_shopDir . "out" . DIRECTORY_SEPARATOR;
		$s_theme_folder_out						= $s_shop_folder_out . $s_theme_name;
		if ( !file_exists( $s_theme_folder_out ) ) {
			$b_ret_mkdir						= mkdir( $s_theme_folder_out, 0777, true );
		}
		$s_theme_folder_out_img					= $s_theme_folder_out . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR;;
		if ( !file_exists( $s_theme_folder_out_img ) ) {
			$b_ret_mkdir						= mkdir( $s_theme_folder_out_img, 0777, true );
		}
		
		
		// ------------------------------------------------------------------------------------------------
		// copy theme.php from template to application/views/<theme>
		$s_src_file								= $s_modulePATH . "_theme_templates" . DIRECTORY_SEPARATOR . "theme.php";
		$s_trg_file								= $s_theme_folder_application_views . DIRECTORY_SEPARATOR . "theme.php";
		$b_ret_copy								= copy( $s_src_file, $s_trg_file );
		
		// ------------------------------------------------------------------------------------------------
		// replace placeholders in new theme.php
		$a_replace								= array(
													"<THEME_ID>"		=> $s_theme_id,
													"<THEME_TITLE>"		=> $s_theme_name,
													"<THEME_DESC>"		=> $s_theme_name,
													"<THEME_PARENT>"	=> $s_parentTheme_name
		);
		/*
			'id'							=> '<THEME_ID>',
			'title'							=> '<THEME_TITLE>',
			'description'					=> '<THEME_DESC>',
			'thumbnail'						=> 'img/a4p_logo.jpg',
			'version'						=> '1.0',
			'author'						=> 'apps4print',
			'parentTheme'					=> '<THEME_PARENT>',
			'parentVersions'				=> array( '0.5', '1.2', '1.3' )
		*/
		$s_file_contents						= file_get_contents( $s_trg_file );
		foreach( $a_replace as $s_search => $s_replace ) {
			$s_file_contents					= str_replace( $s_search, $s_replace, $s_file_contents );
		}
		$b_save_file							= file_put_contents( $s_trg_file, $s_file_contents );
		
		
		// ------------------------------------------------------------------------------------------------
		// copy theme_logo.jpg from template to out/img/<theme>
		$s_src_file								= $s_modulePATH . "_theme_templates" . DIRECTORY_SEPARATOR . "theme_logo.jpg";
		$s_trg_file								= $s_theme_folder_out_img . "theme_logo.jpg";
		$b_ret_copy								= copy( $s_src_file, $s_trg_file );
		
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------
	
	public function copy_theme_settings( $s_srcTheme_name, $s_trgTheme_name ) {
		
		
		
		#	oxRegistry::getConfig()
		
	#	public function saveShopConfVar( $sVarType, $sVarName, $sVarVal, $sShopId = null, $sModule = '' )
		
		
	#	public function getShopConfVar( $sVarName, $sShopId = null, $sModule = '' )
		
		
		
		

		// ----------------------------------------------------------------------------------------------------------------
		$this->_copy_oxconfigdisplay( $s_srcTheme_name, $s_trgTheme_name );
		
		
		$this->_copy_oxconfig( $s_srcTheme_name, $s_trgTheme_name );
		
		
		#return $o_theme_configs;
	}
	
	// ----------------------------------------------------------------------------------------------------------------
	
	protected function _copy_oxconfigdisplay( $s_srcTheme_name, $s_trgTheme_name ) {
		

		// ----------------------------------------------------------------------------------------------------------------
		// oxconfigdisplay
		$s_oxcfgmodule_src						= oxConfig::OXMODULE_THEME_PREFIX . $s_srcTheme_name;
		$s_oxcfgmodule_trg						= oxConfig::OXMODULE_THEME_PREFIX . $s_trgTheme_name;
		
		$o_theme_oxcfgmodule					= oxNew( "oxbase" );
		$o_theme_oxcfgmodule->init( "oxconfigdisplay" );
		$a_db_keys								= array( "oxcfgmodule" => $s_oxcfgmodule_src );
		$sSelect								= $o_theme_oxcfgmodule->buildSelectString( $a_db_keys );
		
		$rows									= oxDb::getDb( oxDb::FETCH_MODE_ASSOC )->Execute( $sSelect );
		
		while ( !$rows->EOF ) {
		
			$a_new								= $rows->fields;
				
			$a_new[ "oxid" ]					= oxUtilsObject::getInstance()->generateUID();
			$a_new[ "oxcfgmodule" ]				= $s_oxcfgmodule_trg;
			#$s_oxvarvalue							= oxRegistry::getConfig()->getShopConfVar( $a_new[ "oxvarname" ], $a_new[ "oxshopid" ], $s_oxcfgmodule_trg );
			#$a_new[ "oxvarvalue" ]					= $s_oxvarvalue;
			#$a_new[ "oxtimestamp" ]					= time();
				
			#oxRegistry::getConfig()->saveShopConfVar( $a_new[ "oxvartype" ], $a_new[ "oxvarname" ], $a_new[ "oxvarvalue" ], $a_new[ "oxshopid" ], $a_new[ "oxmodule" ] );
				
			$oDb								= oxDb::getDb();
		
			$sDeleteSql							= "DELETE FROM `oxconfigdisplay` WHERE OXCFGMODULE=" . $oDb->quote( $a_new[ "oxcfgmodule" ] )." AND OXCFGVARNAME=" . $oDb->quote( $a_new[ "oxcfgvarname" ] );
			$sInsertSql							= "INSERT INTO `oxconfigdisplay`";
			$sInsertSql							.= " (`OXID`, `OXCFGMODULE`, `OXCFGVARNAME`, `OXGROUPING`, `OXVARCONSTRAINT`, `OXPOS`) ";
			$sInsertSql							.= "VALUES ( '" . $a_new[ "oxid" ] . "', " . $oDb->quote( $a_new[ "oxcfgmodule" ] ) . ", " . $oDb->quote( $a_new[ "oxcfgvarname" ] ) . ", " . $oDb->quote( $a_new[ "oxgrouping" ] ) . ", " . $oDb->quote( $a_new[ "oxvarconstraint" ] ).", " . $oDb->quote( $a_new[ "oxpos" ] ) . ")";

			$oDb->execute( $sDeleteSql );
			$oDb->execute( $sInsertSql );

				
			$rows->MoveNext();
		}
		// ----------------------------------------------------------------------------------------------------------------
		
		
	}
	
	protected function _copy_oxconfig( $s_srcTheme_name, $s_trgTheme_name ) {
		
		
		$o_theme_configs						= oxNew( "oxbase" );
		$o_theme_configs->init( "oxconfig" );
		#$sSelect								= "SELECT * FROM oxconfig WHERE oxmodule LIKE '" . oxConfig::OXMODULE_THEME_PREFIX . "%'";
		#$sSelect								= "SELECT * FROM oxconfig WHERE oxmodule = '" . oxConfig::OXMODULE_THEME_PREFIX . $s_srcTheme_name . "'";
		
		$s_oxmodule_src							= oxConfig::OXMODULE_THEME_PREFIX . $s_srcTheme_name;
		
		$sSelect								= $o_theme_configs->buildSelectString( array( "oxmodule" => $s_oxmodule_src ) );
		
		$sSelect								= str_replace( ", `oxconfig`.`oxvarvalue`", ", `oxconfig`.`oxvarvalue`, BINARY `oxconfig`.`oxvarvalue` AS BINARY_VALUE", $sSelect );
		
		// ------------------------------------------------------------------------------------------------
		if ( $this->o_a4p_debug_log ) {
			$this->o_a4p_debug_log->_debug( "\$sSelect", $sSelect, __FILE__, __FUNCTION__, __LINE__ );
		}
		
		#$o_theme_configs->assignRecord( $sSelect );
		
		
		// ------------------------------------------------------------------------------------------------
		if ( $this->o_a4p_debug_log ) {
		#	$this->o_a4p_debug_log->_log( "\$o_theme_configs", $o_theme_configs, __FILE__, __FUNCTION__, __LINE__ );
		}
		
		$rows									= oxDb::getDb( oxDb::FETCH_MODE_ASSOC )->Execute( $sSelect );
		
		
		// ------------------------------------------------------------------------------------------------
		if ( $this->o_a4p_debug_log ) {
		#	$this->o_a4p_debug_log->_debug( "\$rows", $rows, __FILE__, __FUNCTION__, __LINE__ );
		}
		
		
		/*
		$a_new									= $rows->fields;
		
			$a_new[ "oxid" ]						= oxUtilsObject::getInstance()->generateUID();
			$a_new[ "oxmodule" ]					= oxConfig::OXMODULE_THEME_PREFIX . $s_trgTheme_name;
			$a_new[ "oxtimestamp" ]					= time();
		
		
			#$a_new[ "oxvarvalue" ]					= $rows->fields[ "BINARY_VALUE" ];
		
			// OK!!:
			$a_new[ "oxvarvalue" ]					= oxConfig::getInstance()->getConfigParam( "sCatIconsize" );
			*/
		
			/*
			$sUserSelect = is_numeric( $sUser ) ? "oxuser.oxcustnr = {$sUser} " : "oxuser.oxusername = " . $oDb->quote( $sUser );
			$sPassSelect = " oxuser.oxpassword = BINARY MD5( CONCAT( ".$oDb->quote( $sPassword ).", UNHEX( oxuser.oxpasssalt ) ) ) ";
			$sShopSelect = "";
			*/
			#echo utf8_encode( $a_new[ "oxvarvalue" ] );
		
			#echo oxConfig::getInstance()->getConfigParam( "sIconsize");        // 55*100
					// OK!!:
					// UPDATE `oxid_designertemple`.`oxconfig` SET `OXVARVALUE` = BINARY 0xe076e45d86330b WHERE `oxconfig`.`OXID` = 'deebe0c35d7cb86f8aff34385eca564d';
		
		
					// ------------------------------------------------------------------------------------------------
					if ( $this->o_a4p_debug_log ) {
					#	$this->o_a4p_debug_log->_debug( "\$a_new", $a_new, __FILE__, __FUNCTION__, __LINE__ );
		}		
		
		
		#public function saveShopConfVar( $sVarType, $sVarName, $sVarVal, $sShopId = null, $sModule = '' )
		#oxRegistry::getConfig()->saveShopConfVar( $a_new[ "oxvartype" ], $a_new[ "oxvarname" ], $a_new[ "oxvarvalue" ], $a_new[ "oxshopid" ], $a_new[ "oxmodule" ] );
		#$rs = oxDb::getDb( oxDb::FETCH_MODE_ASSOC )->select( $sSelect );
		
		while ( !$rows->EOF ) {
			
			
		// ------------------------------------------------------------------------------------------------
			if ( $this->o_a4p_debug_log ) {
			#	$this->o_a4p_debug_log->_debug( "\$rows->fields", $rows->fields, __FILE__, __FUNCTION__, __LINE__ );
			}
				
			$a_new									= $rows->fields;
				
			#$a_new[ "oxid" ]						= oxUtilsObject::getInstance()->generateUID();
			$a_new[ "oxmodule" ]					= oxConfig::OXMODULE_THEME_PREFIX . $s_trgTheme_name;
			#$a_new[ "oxvarvalue" ]					= oxConfig::getInstance()->getConfigParam( "sCatIconsize" );
			$a_new[ "oxtimestamp" ]					= time();
				
			#getShopConfVar( $sVarName, $sShopId = null, $sModule = '' )
			$s_oxvarvalue							= oxRegistry::getConfig()->getShopConfVar( $a_new[ "oxvarname" ], $a_new[ "oxshopid" ], oxConfig::OXMODULE_THEME_PREFIX . $s_srcTheme_name );
						
			$a_new[ "oxvarvalue" ]					= $s_oxvarvalue;
						
			oxRegistry::getConfig()->saveShopConfVar( $a_new[ "oxvartype" ], $a_new[ "oxvarname" ], $a_new[ "oxvarvalue" ], $a_new[ "oxshopid" ], $a_new[ "oxmodule" ] );
		
			$rows->MoveNext();
		}
		
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------
	
	/**
	 * @desc
	 * 
	 * 		Slash am Ende eines Pfades/Strings anhängen, falls nicht vorhanden
	 * 
	 * @return
	 * 
	 * 		string mit Slash am Ende
	 */
	protected function _appendSlash( $s_path ) {
		
		if ( substr( $s_path, strlen( $s_path ) - 1 ) !== DIRECTORY_SEPARATOR )
			$s_path.= DIRECTORY_SEPARATOR;	
		
		return $s_path;
	}

	// ------------------------------------------------------------------------------------------------
	
}

// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------

?>
