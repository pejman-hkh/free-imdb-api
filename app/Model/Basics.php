<?php
namespace App\Model;
class Basics extends \Peji\DB\Model {
	var $table = 'basics';


	function read() {
		$file = MDIR.'datasets/title.basics.tsv.gz';
		if( ! file_exists( $file ) ) {
			$c = file_get_contents('https://datasets.imdbws.com/title.basics.tsv.gz');
			file_put_contents($file, $c );
		}

		$lines = gzfile($file);
		foreach ($lines as $k => $line) {
			if( $k == 0 ) {
				$e = explode("\t", $line);

			} else {			
				$d = explode("\t", $line );
				$a = new Basics;
				foreach( $e as $k1 => $v ) {
					$v = trim( $v );
					$a->$v = trim($d[$k1]);
				}
				$a->save();
			}

		}
	}
}