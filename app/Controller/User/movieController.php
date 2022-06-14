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

		$this->set('title', $basics->primaryTitle.' '.$basics->startYear);

		$movie = Movies::sql("where code = ? ")->findFirst([$basics->tconst]);
		if( ! @$movie->id ) {
			$movie = new Movies;
			//$movie->code = $basics->tconst;
			//$movie->update();
		}

		if( isset( $this->get['info'] ) ) {
			$movie->getInfo1();
		}

		if( isset( $this->get['info2'] ) ) {
			print_r( $movie->getInfo2() );
		}

		if( isset( $this->get['deletePic'] ) ) {
			echo $movie->pic;
			unlink( $movie->pic );
			exit();
		}

		if( isset( $this->get['update'] ) ) {
			$movie->code = $basics->tconst;
			$movie->update();
		}

	}

	public function after() {
	
	}

}

?>