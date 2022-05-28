<?php
namespace App\Model;
use Peji\DB\DB;

class Ratings extends \Peji\DB\Model {
	var $table = 'ratings';

	function read() {
		$file = 'datasets/title.ratings.tsv.gz';
		if( ! file_exists( $file ) ) {
			$c = file_get_contents('https://datasets.imdbws.com/title.ratings.tsv.gz');
			file_put_contents($file, $c );
		}

		$lines = gzfile($file);

		try {
			DB::beginTransaction();


			foreach ($lines as $k => $line) {
				if( $k == 0 ) {
					$e = explode("\t", $line);
				} else {	
					$d = explode("\t", $line );
					$a = new Ratings;
					foreach( $e as $k1 => $v ) {
						$v = trim( $v );
						$a->$v = trim($d[$k1]);
					}
					$a->save();
				}
			}

			DB::commit();
		} catch (\Throwable $e) {

			DB::rollback();
		}	

	}
		
}