<?php
namespace App\Model;
use Peji\DB\DB;

class Principals extends \Peji\DB\Model {
	var $table = 'principals';

	function read() {
		$file = MDIR.'datasets/title.principals.tsv.gz';
		if( ! file_exists( $file ) ) {
			$c = file_get_contents('https://datasets.imdbws.com/title.principals.tsv.gz');
			file_put_contents($file, $c );
		}

		$lines = gzfile($file);

		try {
			DB::beginTransaction();

			foreach ($lines as $k => $line) {
				print_r( $line );
				exit();
				
				if( $k % 1000 == 0 ) {
					DB::commit();
					DB::beginTransaction();
				}

				if( $k == 0 ) {
					$e = explode("\t", $line);
				} else {	
					$d = explode("\t", $line );
					$a = new Principals;
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