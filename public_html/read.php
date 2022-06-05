<?php
ini_set('memory_limit', -1);
//ini_set('display_errors',1);


require_once __DIR__.'/../vendor/autoload.php';
require_once 'helper.php';
require_once 'simple_html_dom.php';

@mkdir(__DIR__ . '/../cache/locks/');
$file = __DIR__ . '/../cache/locks/read.lock';
$fp = lockFile($file);
if(!$fp) {
    die('already running');
}


use Peji\DB\DB as DB;
use Peji\Config as Config;
use Peji\View as View;

Config::setDir(__dir__.'/../config');
$dbConf = Config::file('db');


define('MDIR', __dir__.'/');
echo MDIR;
exit();

use App\Model\Basics;
use App\Model\Movies;

try {
	$i = 0;
	$from  = 0;
	while( 1 ) {


		DB::init( $dbConf['host'], $dbConf['username'], $dbConf['password'], $dbConf['db'] );

		DB::setAttr([
			\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'" ,
			\PDO::ATTR_PERSISTENT => false ,
			\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true ,
		]);

		$basics = Basics::sql(" order by rateOrder desc limit $from, 100")->find();
		foreach( $basics as $basic ) {
			echo $basic->tconst."\n";

			$movie = Movies::sql("where code = ? ")->findFirst([$basic->tconst]);
			if( @$movie->id ) {
				continue;
			}

			$m = new Movies;
			$m->code = $basic->tconst;
			$m->update();
		}

		$from = ($i + 1) * 100;
		$i++;

		DB::disconnect();
	}

} catch( Error $e ){
	Peji\Error::manage( $e );
}

unlockFile($fp);