<?php
namespace App\Controller\User;

use App\Controller\User\appController;
use App\Model\Basics;

class movieController extends appController {

	var $toIndex = true;
	public function before() {
		
	}

	function page404() {

	}

	public function index( $id = 0, $params = [] ) {
		//array_shift( $params );
		//$params = $this->keyPairParams( $params );

		$movie = Basics::sql(" where tconst = ?")->findFirst([ $params[1] ]);

		$this->set('movie', $movie);

	}

	public function after() {
	
	}

}

?>