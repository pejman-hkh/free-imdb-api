<?php
namespace App\Controller\User;

use App\Controller\User\appController;
use Peji\DB\DB;

use App\Model\Ratings;

class indexController extends appController {

	var $toIndex = true;
	public function before() {
		
	}

	function page404() {

	}

	public function index( $id = 0, $params = [] ) {
		array_shift( $params );
		$params = $this->keyPairParams( $params );

		$movies = Ratings::sql("")->paginate( (int)(isset($this->get['npage'])?$this->get['npage']:36), @$params['page']?:1 )->find();

		$this->set('movies', $movies);
	}

	public function after() {
	
	}

}

?>