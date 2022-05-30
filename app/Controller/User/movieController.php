<?php
namespace App\Controller\User;

use App\Controller\User\appController;
use Peji\DB\DB;

use App\Model\Basics;

class indexController extends appController {

	var $toIndex = true;
	public function before() {
		
	}

	function page404() {

	}

	public function index( $id = 0, $params = [] ) {
		//array_shift( $params );
		//$params = $this->keyPairParams( $params );

		print_r( $params );
		exit();
		
		$movie = Basics::sql(" where tconst = ?")->findFirst([ $params ]);
	}

	public function after() {
	
	}

}

?>