<?php

function count1( $a ) {
	return count( (array)$a );
}

function lockFile($file = '')
{
    if (file_exists($file)) {
        $type = 'r+';
    } else {
        $type = 'w+';
    }
    $fp = fopen($file, $type);
    if ($fp) {
        if (flock($fp, LOCK_EX | LOCK_NB)) {
            return $fp;
        }
    }
    return false;
}

function isLockFile($file = '')
{
    if (!file_exists($file)) {
        return false;
    }
    $fp = fopen($file, 'r+');
    if ($fp) {
        $lock = flock($fp, LOCK_EX | LOCK_NB);
        fclose($fp);
        if (!$lock) {
            return true;
        }
    }
    return false;
}

function unlockFile($fp)
{
    if ($fp) {
        flock($fp, LOCK_UN);
        fclose($fp);
    }
}


function strip_tags_content($text) {
    return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
    
 }

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


function mjson_encode( $arr ) {
	return json_encode( $arr, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}

function getArray( $a ) {
	$ret = [];
	foreach( $a as $v ) {
		$d = [];
		foreach( $v->columns as $v1 ) {
			$d[$v1] = $v->$v1;
		}
		$ret[] = $d;
	}
	return $ret;
}

function api_encode( $a ) {
	if( $a->columns ) {
		$ret = [];
		foreach( $a->columns as $v ) {
			$ret[$v] = $a->$v;
		}
		return mjson_encode( $ret );
	} else {
		$ret = [];
		foreach( $a as $v ) {
			$d = [];
			foreach( $v->columns as $v1 ) {
				$d[$v1] = $v->$v1;
			}
			$ret[] = $d;
		}
		return mjson_encode( $ret );
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