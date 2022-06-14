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
		$movie = Movies::sql("where code = ?")->findFirst([ $this->get['code'] ]);

		$info = $movie->info2;

		$ret = new \StdClass;
		$ret->title = $movie->basic->primaryTitle;
		$ret->originalTitle = $movie->basic->originalTitle;
		//$ret->summery = $movie->summery;
		//$ret->story = $movie->storyLine1;
		$ret->src = $movie->src;
		$ret->srcset = $movie->srcset;
		$ret->rate = $movie->rating->averageRating;
		$ret->numVotes = $movie->rating->numVotes;
		$ret->year = $movie->basic->startYear;
		$ret->runtime = $movie->basic->runtimeMinutes;
		$ret->isAdult = $movie->basic->isAdult;
		$ret->type = $movie->basic->titleType;

		$ret->actors = getArray($movie->actors);
		$ret->directors = getArray($movie->directors);
		$ret->writers = getArray($movie->writers);
		$ret->countries = getArray($movie->countries);
		$ret->languages = getArray($movie->languages);
		$ret->genres = getArray($movie->genres);
		$ret->metacritic = $info->metacritic;
		$ret->releaseDate = $info->releaseDate;
		$ret->plot = $info->plot;
		$ret->wins = $info->wins;
		$ret->nominations = $info->nominations;
		$ret->casts = $info->casts;
		$ret->budget = $info->budget;
		$ret->lifetimeGross = $info->lifetimeGross;
		$ret->openingWeekendGross = $info->openingWeekendGross;
		$ret->worldwideGross = $info->worldwideGross;
		$ret->keywords = $info->keywords;
		$ret->production = $info->production;
		$ret->prestigiousAwardSummary = $info->prestigiousAwardSummary;
		$ret->moreLikeThisTitles = $info->moreLikeThisTitles;

		echo mjson_encode( (array)$ret );
	}

	public function after() {
	
	}

}

?>