<?php

include "bootstrap.php";

$s_srcTheme_name			= "azure";
$s_trgTheme_name			= "designertemple";

$o_a4p_theme_manager		= oxNew( "a4p_theme_manager" );


$o_a4p_theme_manager->copy_theme_settings( $s_srcTheme_name, $s_trgTheme_name );
