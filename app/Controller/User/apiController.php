<?php
namespace App\Controller\User;

use App\Controller\User\appController;
use Peji\DB\DB;

use App\Model\Basics;
use App\Model\Ratings;
use App\Model\Crew;
use App\Model\Names;
use App\Model\Akas;

class apiController extends appController {

	public function before() {
		
	}


	public function index( $id = 0, $params = [] ) {
	
	}

	function basics() {
		$this->disableView = 1;
		$basic = Basics::sql("where tconst = ? ")->findFirst([ $this->get['code'] ]);
		echo api_encode( $basic );
	}


	function ratings() {
		$this->disableView = 1;
		$basic = Ratings::sql("where tconst = ? ")->findFirst([ $this->get['code'] ]);
		echo api_encode( $basic );
	}


	function crew() {
		$this->disableView = 1;
		$basic = Crew::sql("where tconst = ? ")->findFirst([ $this->get['code'] ]);
		echo api_encode( $basic );
	}

	function names() {
		$this->disableView = 1;
		$basic = Names::sql("where nconst = ? ")->findFirst([ $this->get['code'] ]);
		echo api_encode( $basic );
	}

	function akas() {
		$this->disableView = 1;
		$basic = Akas::sql("where titleId = ? ")->findFirst([ $this->get['code'] ]);
		echo api_encode( $basic );
	}

	public function after() {
	
	}

}

?>