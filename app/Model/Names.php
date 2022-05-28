<?php
namespace App\Model;
class Names extends \Peji\DB\Model {
	var $table = 'names';

	function read() {
		$file = MDIR.'datasets/name.basics.tsv.gz';
		if( ! file_exists( $file ) ) {
			$c = file_get_contents('https://datasets.imdbws.com/name.basics.tsv.gz');
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
					$a = new Names;
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