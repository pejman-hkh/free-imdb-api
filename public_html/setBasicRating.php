<?php
ini_set('memory_limit', -1);
//ini_set('display_errors',1);


require_once __DIR__.'/../vendor/autoload.php';
require_once 'helper.php';
require_once 'simple_html_dom.php';

@mkdir(__DIR__ . '/../cache/locks/');
$file = __DIR__ . '/../cache/locks/setBasicRating.lock';
$fp = lockFile($file);
if(!$fp) {
    die('already running');
}


use Peji\DB\DB as DB;
use Peji\Config as Config;
use Peji\View as View;

Config::setDir('../config');
$dbConf = Config::file('db');


define('MDIR', '');
use App\Model\Basics;

try {
	$a = new Basics;
	$a->readRating();

} catch( Error $e ){
	Peji\Error::manage( $e );
}

unlockFile($fp);