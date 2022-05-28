<?php

function api_encode( $a ) {
	if( $a->columns ) {
		$ret = [];
		foreach( $a->columns as $v ) {
			$ret[$v] = $a->$v;
		}
		return json_encode( $ret );
	} else {
		$ret = [];
		foreach( $a as $v ) {
			$d = [];
			foreach( $v->columns as $v1 ) {
				$d[$v1] = $v->$v1;
			}
			$ret[] = $d;
		}
		return json_encode( $ret );
	}
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
