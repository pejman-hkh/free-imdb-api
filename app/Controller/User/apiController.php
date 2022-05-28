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
		
		$this->disableView = 1;
		$basic = Basics::sql("where tconst = ? ")->findFirst([ $this->get['code'] ]);
		echo json_encode( $basic );

	}


	function ratings() {

		
		$this->disableView = 1;
		$basic = Basics::sql("where tconst = ? ")->findFirst([ $this->get['code'] ]);
		echo json_encode( $basic );
				
	}

	public function after() {
	
	}

}

?>