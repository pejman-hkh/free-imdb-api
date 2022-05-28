<?php
namespace App\Controller\User;

use App\Controller\User\appController;
use Peji\DB\DB;

use App\Model\Basics;
use App\Model\Ratings;
use App\Model\Crew;

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
		$basic = Ratings::sql("where tconst = ? ")->findFirst([ $this->get['code'] ]);
		echo json_encode( $basic );
	}


	function crew() {
		$this->disableView = 1;
		$basic = Crew::sql("where tconst = ? ")->findFirst([ $this->get['code'] ]);
		echo json_encode( $basic );
	}

	public function after() {
	
	}

}

?>