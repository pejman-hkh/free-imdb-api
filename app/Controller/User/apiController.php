<?php
namespace App\Controller\User;

use App\Controller\User\appController;
use Peji\DB\DB;

use App\Model\Basics;

class apiController extends appController {

	public function before() {
		
	}


	public function index( $id = 0, $params = [] ) {
	
	}

	function basics() {
		
		$basic = Basics::sql("where tconst = ? ")->findFirst([ $this->get['code'] ]);
		return json_encode( $basic );

	}

	public function after() {
	
	}

}

?>