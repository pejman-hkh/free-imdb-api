<?php
namespace App\Model;
use Peji\DB\DB;

class Episodes extends \Peji\DB\Model {
	var $table = 'episodes';

	function read() {
		$file = MDIR.'datasets/title.episode.tsv.gz';
		if( ! file_exists( $file ) ) {
			$c = file_get_contents('https://datasets.imdbws.com/title.episode.tsv.gz');
			file_put_contents($file, $c );
		}

		try {
			DB::beginTransaction();
			$e = [];
			gzfile_get_contents($file, function( $line, $k ) {
				global $e;


				if( $k % 100000 == 0 ) {
					DB::commit();
					DB::beginTransaction();
				}

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
			});

			DB::commit();
		} catch (\Throwable $e) {
			DB::rollback();
		}				
	}

}