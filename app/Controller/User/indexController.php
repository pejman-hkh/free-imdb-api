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
	/*	if( isset( $this->get['ratings'] ) ) {
			$a = new Ratings;
			$a->read();
		}*/

	}

	public function after() {
	
	}

}

?>