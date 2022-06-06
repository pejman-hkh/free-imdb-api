<?php
namespace App\Model;
use Peji\DB\DB;

class Ratings extends \Peji\DB\Model {
	var $table = 'ratings';

	function getBasic() {
		return Basics::sql("where tconst = ? ")->findFirst([ $this->tconst ]);
	}

	function read() {
		$file = MDIR.'datasets/title.ratings.tsv.gz';
		//if( ! file_exists( $file ) ) {
			$c = file_get_contents('https://datasets.imdbws.com/title.ratings.tsv.gz');
			file_put_contents($file, $c );
		//}


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
					$a = new Ratings;
					foreach( $e as $k1 => $v ) {
						$v = trim( $v );
						$a->$v = trim($d[$k1]);
					}

					$check = Ratings::sql("where tconst = ? ")->findFirst([ $a->tconst ]);
					if( @$check->id ) {
						$a = $check;
					}

					$a->save();
					if( $a->basic ) {
						$a->basic->averageRating = $a->averageRating;
						$a->basic->numVotes = $a->numVotes;
						$a->basic->rateOrder = $a->averageRating * $a->numVotes;
						$a->basic->save();
					}
				}
			});

			DB::commit();
		} catch (\Throwable $e) {

			DB::rollback();
		}	

	}
		
}