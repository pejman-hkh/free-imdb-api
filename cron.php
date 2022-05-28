<?php

ini_set('memory_limit', -1);

require_once __DIR__.'/vendor/autoload.php';
require_once 'public_html/helper.php';

use Peji\DB\DB as DB;
use Peji\Config as Config;

Config::setDir('config');
$dbConf = Config::file('db');

DB::init( $dbConf['host'], $dbConf['username'], $dbConf['password'], $dbConf['db'] );

DB::setAttr([
	\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'" ,
	\PDO::ATTR_PERSISTENT => false ,
	\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true ,
]);

define('MDIR', 'public_html/');

use App\Model\Akas;

try {

	$a = new Akas;
	$a->read();

} catch( Error $e ){
	Peji\Error::manage( $e );
}