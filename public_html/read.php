<?php
ini_set('memory_limit', -1);
//ini_set('display_errors',1);


require_once __DIR__.'/../vendor/autoload.php';
require_once 'helper.php';
require_once 'simple_html_dom.php';

@mkdir(__DIR__ . '/../cache/locks/');
$file = __DIR__ . '/../cache/locks/cron.lock';
$fp = lockFile($file);
if(!$fp) {
    die('already running');
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
use App\Model\Basics;
use App\Model\Movies;

try {
	$i = 0;
	$from  = 0;
	while( 1 ) {
		$basics = Basics::sql(" select a.* from basics as a join ratings as b on a.tconst = b.tconst where 1 order by ( b.averageRating * b.numVotes ) desc  limit $from, 1000")->find();
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

		$from = ($i + 1) * 1000;
		$i++;
	}

} catch( Error $e ){
	Peji\Error::manage( $e );
}

unlockFile($fp);