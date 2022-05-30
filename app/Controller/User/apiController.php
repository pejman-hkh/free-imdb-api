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
use App\Model\Principals;
use App\Model\Movies;

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

		if( isset( $this->get['code'] ) ) {
			$ret = Ratings::sql("where tconst = ? ")->findFirst([ $this->get['code'] ]);
			echo api_encode( $ret );
		}


		if( isset( $this->post['codes'] ) ) {
			$ret = [];
			foreach( $this->post['codes'] as $code ) {
				$ret[] = Ratings::sql("where tconst = ? ")->findFirst([ $code ]);
			}
			echo api_encode( $ret );
		}
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

	function movies() {
		$this->disableView = 1;
		$find = Movies::sql("where code = ?")->findFirst([ $this->get['code'] ]);
		$find->actors = $find->actors;
		$find->directors = $find->directors;
		$find->writers = $find->writers;
		$find->countries = $find->countries;
		$find->languages = $find->languages;

		echo api_encode( $find );
	}

	public function after() {
	
	}

}

?>