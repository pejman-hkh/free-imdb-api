<?php
namespace App\Model;
class Episodes extends \Peji\DB\Model {
	var $table = 'episodes';

	function read() {
		$file = 'datasets/title.episode.tsv.gz';
		if( ! file_exists( $file ) ) {
			$c = file_get_contents('https://datasets.imdbws.com/title.episode.tsv.gz');
			file_put_contents($file, $c );
		}

		$lines = gzfile($file);
		foreach ($lines as $k => $line) {
			if( $k == 0 ) {
				$e = explode("\t", $line);
			} else {	
				$d = explode("\t", $line );
				$a = new Episodes;
				foreach( $e as $k1 => $v ) {
					$v = trim( $v );
					$a->$v = trim($d[$k1]);
				}
				$a->save();
			}
		}		
	}

}