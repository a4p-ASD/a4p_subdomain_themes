<?php

/**
 *	@author:	a4p ASD / Andreas Dorner
 *	@company:	apps4print / page one GmbH, Nürnberg, Germany
 *
 *
 *	@version:	3.0.0
 *	@date:		05.06.2013
 *
 *
 * a4p_sdt__oxviewconfig.php
 *
 * apps4print - subdomain theme module for OXID eShop
 *
 */

// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------

class a4p_sdt__oxviewconfig extends a4p_sdt__oxviewconfig_parent {
	
	// ------------------------------------------------------------------------------------------------

	/**
	 * Returns shops current (related to language) templates path
	 *
	 * @return string
	 */
	public function getTemplateDir()
	{

//trigger_error( "getTemplateDir" );

		return parent::getTemplateDir();
	}

	// ------------------------------------------------------------------------------------------------
	
	/**
	 * Returns shops base directory path
	 *
	 * @return string
	 */
	public function getBaseDir()
	{

//trigger_error( "getBaseDir" );

		return parent::getBaseDir();
	}

	// ------------------------------------------------------------------------------------------------

	/**
	 * true if blocks javascript code be enabled in templates
	 *
	 * @return bool
	 */
	public function isTplBlocksDebugMode()
	{

//trigger_error( "isTplBlocksDebugMode" );

//trigger_error( $_SERVER[ "SERVER_NAME" ] );

		return parent::isTplBlocksDebugMode();
	}


}

// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------

?>