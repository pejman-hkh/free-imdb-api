<?php
namespace App\Model;
use Peji\DB\DB;

class Crew extends \Peji\DB\Model {
	var $table = 'crew';

	function read() {
		$file = MDIR.'datasets/title.crew.tsv.gz';
		if( ! file_exists( $file ) ) {
			$c = file_get_contents('https://datasets.imdbws.com/title.crew.tsv.gz');
			file_put_contents($file, $c );
		}

		$lines = gzfile($file);
		foreach ($lines as $k => $line) {
			if( $k == 0 ) {
				$e = explode("\t", $line);
			} else {	
				$d = explode("\t", $line );
				$a = new Crew;
				foreach( $e as $k1 => $v ) {
					$v = trim( $v );
					$a->$v = trim($d[$k1]);
				}
				$a->save();
			}
		}		
	}
}