<?php
namespace App\Controller\User;

use App\Controller\User\appController;
use Peji\DB\DB;

use App\Model\Basics;
use App\Model\Ratings;
use App\Model\Crew;
use App\Model\Names;
use App\Model\Akas;
use App\Model\Episodes;

class apiController extends appController {

	public function before() {
		
	}


	public function index( $id = 0, $params = [] ) {
	
	}

	function basics() {
		$this->disableView = 1;
		$ret = Basics::sql("where tconst = ? ")->findFirst([ $this->get['code'] ]);
		echo api_encode( $ret );
	}


	function ratings() {
		$this->disableView = 1;
		$ret = Ratings::sql("where tconst = ? ")->findFirst([ $this->get['code'] ]);
		echo api_encode( $ret );
	}


	function crew() {
		$this->disableView = 1;
		$ret = Crew::sql("where tconst = ? ")->findFirst([ $this->get['code'] ]);
		echo api_encode( $ret );
	}

	function names() {
		$this->disableView = 1;
		$ret = Names::sql("where nconst = ? ")->findFirst([ $this->get['code'] ]);
		echo api_encode( $ret );
	}

	function akas() {
		$this->disableView = 1;
		$ret = Akas::sql("where titleId = ? ")->find([ $this->get['code'] ]);
		echo api_encode( $ret );
	}


	function episodes() {
		$this->disableView = 1;
		$ret = Episodes::sql("where parentTconst = ? ")->find([ $this->get['code'] ]);
		echo api_encode( $ret );
	}

	function principals() {
		$this->disableView = 1;
		$ret = Principals::sql("where tconst = ? ")->find([ $this->get['code'] ]);
		echo api_encode( $ret );
	}

	public function after() {
	
	}

}

?>