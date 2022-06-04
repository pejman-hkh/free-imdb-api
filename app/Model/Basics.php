<?php
namespace App\Model;
use Peji\DB\DB;

class Basics extends \Peji\DB\Model {
	var $table = 'basics';

	function readRating() {
		
		$from = 0;
		$i = 0;
		while( 1 ) {
			try {
				DB::beginTransaction();
				$ratings = Ratings::sql(" limit $from, 10000")->find();
				if( count( $ratings ) == 0 ) {
					break;
				}
				
				foreach( $ratings as $rating ) {
					if( $rating->basic->id ) {
						$rating->basic->averageRating = $rating->averageRating;
						$rating->basic->numVotes = $rating->numVotes;
						$rating->basic->save();
					}
				}
				$from = ($i + 1) * 10000;
				$i++;
				DB::commit();
			} catch (\Throwable $e) {
				DB::rollback();
			}

		}

	}

	function getPic() {
		return 'images/'.$this->tconst.'.jpg';
	}

	function getMovie() {
		return Movies::sql("where code = ? ")->findFirst([ $this->tconst ]);
	}


	function getRating() {
		return Ratings::sql("where tconst = ? ")->findFirst([ $this->tconst ]);
	}

	function getBasic() {
		return Basics::sql("where id = ? ")->findFirst([ $this->id ]);
	}

	function getAkas() {
		return Akas::sql("where titleId = ? ")->find([ $this->tconst ]);
	}

	function getPrincipals() {
		return Principals::sql("where tconst = ? ")->find([ $this->tconst ]);
	}

	function getCrews() {
		return Crew::sql("where tconst = ? ")->find([ $this->tconst ]);
	}

	function read() {
		$file = MDIR.'datasets/title.basics.tsv.gz';
		if( ! file_exists( $file ) ) {
			$c = file_get_contents('https://datasets.imdbws.com/title.basics.tsv.gz');
			file_put_contents($file, $c );
		}

		//$lines = gzfile($file);
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
					$a = new Basics;
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