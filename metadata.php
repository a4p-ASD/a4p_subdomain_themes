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
 *	metadata.php
 *
 *	apps4print - subdomain themes - Themes je (Sub-)Domain wechseln
 *
 */

// ------------------------------------------------------------------------------------------------
// apps4print
// ------------------------------------------------------------------------------------------------

$sMetadataVersion   = '1.0';

$aModule = array(
	'id'			=> 'a4p_subdomain_themes', 
	'title'			=> 'apps4print - a4p_subdomain_themes', 
	'description'	=> array(
		'de'			=> 'Themes je (Sub-)Domain wechseln', 
		'en'			=> 'change themes by (sub-)domain'
	), 
	'lang'			=> 'de', 
	'thumbnail'		=> 'out/img/apps4print/a4p_logo.jpg', 
	'version'		=> '<a4p_VERSION> (1.0.1)', 
	'author'		=> 'apps4print', 
	'url'			=> 'http://www.apps4print.com', 
	'email'			=> 'support@apps4print.com', 
	'extend'	  	=> array(
		"oxconfig"								=> "apps4print/a4p_subdomain_themes/core/a4p_subdomain_themes__oxconfig" 
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
// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------
