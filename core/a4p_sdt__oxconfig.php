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
	public function getShopUrl( $iLang = null, $blAdmin = null )
	{

//trigger_error( "getShopUrl" );


if ( $_SERVER[ "SERVER_NAME" ] == "sub1.subshop.apps4print.com" ) {

	$this->setConfigParam( 'sTheme', "a4p_sub1" );

	$this->setConfigParam( 'sShopURL', "http://sub1.subshop.apps4print.com/" );

} else if ( $_SERVER[ "SERVER_NAME" ] == "sub2.subshop.apps4print.com" ) {

	$this->setConfigParam( 'sTheme', "a4p_sub2" );

	$this->setConfigParam( 'sShopURL', "http://sub2.subshop.apps4print.com/" );

}

		return parent::getShopUrl( $iLang, $blAdmin );
	}

	// ------------------------------------------------------------------------------------------------
	
}

// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------

?>