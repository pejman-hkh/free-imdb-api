<?
ini_set('memory_limit', -1);
ini_set('display_errors',1);

require_once __DIR__.'/../vendor/autoload.php';
require_once 'helper.php';

use Peji\DB\DB as DB;
use Peji\Config as Config;
use Peji\View as View;

Config::setDir('../config');
$dbConf = Config::file('db');


define('siteUrl', $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'] );

DB::init( $dbConf['host'], $dbConf['username'], $dbConf['password'], $dbConf['db'] );

DB::setAttr([
	\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'" ,
	\PDO::ATTR_PERSISTENT => false ,
	\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true ,
]);

define('MDIR', '');

View::setDir( '../app/View' );

use App\Model\Ratings;

try {

/*
	$a = new Ratings;
	$a->read();
*/
	require_once '../route/route.php';
} catch( Error $e ){
	Peji\Error::manage( $e );
}