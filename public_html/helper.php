<?php

function api_encode( $a ) {
	$ret = [];
	foreach( $a->columns as $v ) {
		$ret[$v] = $a->$v;
	}
	return json_encode( $ret );
}

$globalPath = '';
function getPath() {
	global $globalPath;
	if( !empty( $globalPath ) ) {
		return $globalPath;
	}

	$appDir = str_replace( [ "public_html/index.php", "index.php"], "", $_SERVER['PHP_SELF'] );
	
	if( ! defined('baseUrl') ) 
		define( 'baseUrl', $appDir );

	$reqUri = explode("?", $_SERVER['REQUEST_URI'])[0] ;
	$globalPath = preg_replace( '#^'.$appDir.'#', "", $reqUri );
	return $globalPath;
}
