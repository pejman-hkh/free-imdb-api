<?php
namespace App\Controller\User;

use App\Controller\User\appController;
use Peji\DB\DB;

use App\Model\Basics;
use App\Model\Movies;

class indexController extends appController {

	var $toIndex = true;
	public function before() {
		
	}

	function page404() {

	}

	public function index( $id = 0, $params = [] ) {
		Movies::sql("")->find();

		array_shift( $params );
		$params = $this->keyPairParams( $params );

		$movies = Basics::sql(" select a.id,a.tconst from basics as a right join ratings as b on a.tconst = b.tconst where 1 order by ( b.averageRating * b.numVotes ) desc ")->paginate( (int)(isset($this->get['npage'])?$this->get['npage']:36), @$params['page']?:1 )->find();

		$path = explode("/", getPath() );
		$this->set('mcontroller', $path[0]?:'index' );

		$this->set('params', $params);
		$this->set('movies', $movies);
		$this->set('pagination', Basics::getPaginate() );
	}

	public function after() {
	
	}

}

?>