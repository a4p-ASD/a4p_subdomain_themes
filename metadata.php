<?php

/*
 *	@author:	a4p ASD / Andreas Dorner
 *	@company:	apps4print / page one GmbH, Nürnberg, Germany
 *
 *
 *	@version:	1.0.0
 *	@date:		16.10.2013
 *
 *
 *	metadata.php
 *
 *	apps4print - subdomain themes module for OXID eShop
 *
 */

// ------------------------------------------------------------------------------------------------
// apps4print
// ------------------------------------------------------------------------------------------------

/**
 * apps4print - Module information
 * 
 * http://wiki.oxidforge.org/Features/Extension_metadata_file
 */

$sMetadataVersion = '1.0';

$aModule = array(
	'id'			=> 'a4p_subdomain_themes', 
	'title'			=> 'apps4print - subdomain themes module', 
	'description'	=> array(
		'de'			=> 'apps4print subdomain themes module', 
		'en'			=> 'apps4print subdomain themes module'
	),
	'lang'			=> 'de', 
	'thumbnail'		=> 'out/img/apps4print/_a4p_theme.jpg', 
	'version'		=> '<a4p_VERSION> (1.0.0)', 
	'author'		=> 'apps4print', 
	'url'			=> 'http://www.apps4print.com', 
	'email'			=> 'alexander.steiss@apps4print.com', 
	'extend'	  	=> array(
		'oxconfig'								=> 'apps4print/a4p_subdomain_themes/core/a4p_sdt__oxconfig', 
		'oxviewconfig'							=> 'apps4print/a4p_subdomain_themes/core/a4p_sdt__oxviewconfig' 
	),
	'files'			=> array(
	),
	'blocks'		=> array(
	),
	'settings'		=> array(
	),
	'templates'		=> array(
	)
);

// ------------------------------------------------------------------------------------------------
// apps4print
// ------------------------------------------------------------------------------------------------
