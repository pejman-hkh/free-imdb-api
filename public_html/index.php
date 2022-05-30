<?
ini_set('memory_limit', -1);
//ini_set('display_errors',1);


require_once __DIR__.'/../vendor/autoload.php';
require_once 'helper.php';
require_once 'simple_html_dom.php';

define('HOST', $_SERVER['HTTP_HOST'] );
define('HOST1', 'https://'.$_SERVER['HTTP_HOST'] );
define('siteUrl', $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'] );


if( ! isLocal() ) {
	if( $_SERVER['REQUEST_SCHEME'] == 'http' ) {
			$req = $_SERVER['REQUEST_URI'];
			header("Location: https://".HOST.$req,TRUE,301);
			exit();
	}
}



use Peji\DB\DB as DB;
use Peji\Config as Config;
use Peji\View as View;

Config::setDir('../config');
$dbConf = Config::file('db');

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