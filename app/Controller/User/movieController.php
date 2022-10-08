<?php
namespace App\Controller\User;

use App\Controller\User\appController;
use App\Model\Basics;
use App\Model\Movies;

class movieController extends appController {

	var $toIndex = true;
	public function before() {
		
	}

	function page404() {

	}

	public function index( $id = 0, $params = [] ) {
		//array_shift( $params );
		//$params = $this->keyPairParams( $params );

		$basics = Basics::sql(" where tconst = ?")->findFirst([ $params[1] ]);

		$this->set('movie', $basics);

		$this->set('title', ($basics->primaryTitle?:$basics->movie->api->title).' '.($basics->startYear?:$basics->movie->api->year) );

		$movie = Movies::sql("where code = ? ")->findFirst([ $params[1] ]);
		if( ! @$movie->id ) {
			$movie = new Movies;
			//$movie->code = $basics->tconst;
			//$movie->update();
		}

		if( isset( $this->get['info'] ) ) {
			$movie->getInfo1();
		}

		if( isset( $this->get['datan'] ) ) {
			print_r( json_decode( $movie->datan ) );
			$this->disableView = 1;
		}

		if( isset( $this->get['info2'] ) ) {
			/*print_r( json_decode( $movie->datan ) );*/
			print_r( $movie->getInfo2() );
			$this->disableView = 1;
		}

		if( isset( $this->get['deletePic'] ) ) {
			echo $movie->pic;
			unlink( $movie->pic );
			exit();
		}

		if( isset( $this->get['update'] ) ) {
			$movie->code = $params[1];
			$movie->update();

			$api = $movie->api;

			if( ! $basics->id ) {
				$a = new Basics;
			} else {
				$a = $basics;
			}

			if( $a && ! $basics->titleType ){
				$a->tconst = $movie->code;
				$a->titleType = $api->type;
				$a->primaryTitle = $api->title;
				$a->originalTitle = $api->originalTitle;
				$a->startYear = $api->year;
				$a->isAdult = $api->isAdult;
				$a->runtimeMinutes = $api->runtime;
				$gs = [];
				foreach( $api->genres as $genre ) {
					$gs[] = $genre->title;
				}

				$a->genres = implode(',', $gs);
				$a->averageRating = $api->rate;
				$a->numVotes = $api->numVotes;
				$a->rateOrder = $api->averageRating * $api->numVotes;
				$a->save();			
			}


			
			
		}

	}

	public function after() {
	
	}

}

?>