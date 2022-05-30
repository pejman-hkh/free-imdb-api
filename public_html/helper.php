<?php

function isLocal() {
	if( $_SERVER['HTTP_HOST'] == 'localhost' || isset($_GET['debug_programmer']) ) {
		return 1;
	}
	return 0;
}

function gzfile_get_contents($filename, $callback ) {
	$sfp = gzopen($filename, "r");
	$k = 0;
	while ($line = fgets($sfp)) {
	    //echo $line;
	    $callback( $line, $k++ );
	}
}


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


function makePp( $params = [], $except = '' ) {

	if( @$params['page'] )
		unset( $params['page'] );

	$ret = '';
	$pre = '';

	foreach( $params as $k => $v ) {
		if( $k == $except ) continue;
		
		$ret .= $pre.$k.($v?'/'.$v:'');
		$pre = '/';
	}

	return $ret;
}