<?php
namespace App\Model;
class Movies extends \Peji\DB\Model {
	var $table = 'movies';

	function getPic() {
		return 'images/'.$this->code.'.jpg';
	}

	function getLanguages() {
		return Languages::sql("where id in(".($this->tlanguages?:-1).") ")->find();
	}

	function getCountries() {
		return Countries::sql("where id in(".($this->tcountries?:-1).") ")->find();
	}

	function getActors() {
		return Actors::sql("where id in(".($this->tactors?:-1).") ")->find();
	}

	function getDirectors() {
		return Directors::sql("where id in(".($this->tdirectors?:-1).") ")->find();
	}

	function getWriters() {
		return Writers::sql("where id in(".($this->twriters?:-1).") ")->find();
	}

	function getGenres() {
		return Genres::sql("where id in(".($this->tgenres?:-1).") ")->find();
	}

	function getImdbUrl() {
		return 'https://www.imdb.com/title/'.$this->code.'/';
	}


	function getBasic() {
		return Basics::sql("where tconst = ? ")->findFirst([ $this->code ]);
	}

	function getRating() {
		return Ratings::sql("where tconst = ? ")->findFirst([ $this->code ]);
	}

	function update() {

		$movie = $this;

		$ret = file_get_contents( $this->imdbUrl );
		$html = str_get_html( $ret );

	
		if( ! $html ) return;
		preg_match('#<script type\="application/ld\+json">(.*?)</script>#', $ret, $m );
		$data = ( json_decode($m[1]) );


		$nh1 = $html->find("h1", 0);
		$mainName = $nh1->innertext;

		$initems = [];
		foreach( $html->find(".ipc-inline-list__item") as $v ) {
			$tid =  $v->parent()->{'data-testid'};
	
			if( $v->children() ) {
				foreach( $v->children() as $v1 ) {
					$initems[ $tid ][] = ($v1->innertext);
				}
			} else {
				$initems[ $tid ][] = $v->innertext;
			}

		}

		$year = 0;
		foreach( $initems['hero-title-block__metadata'] as $v ) {
			if( preg_match('#[0-9]{4}#', $v ) ) {
				$year = $v;
				break;
			}
		}

		$stl = $html->find(".ipc-html-content-inner-div", 0);
		$movie->storyLine = strip_tags( $stl->innertext );

		$movie->summery = $data->description;

		$img = $html->find(".ipc-page-background .ipc-poster .ipc-image", 0);

		if( $img ) {

			if( preg_match('#190\,281#', $img->src ) ) {
				$fname = $movie->code.'.jpg';
				$ret = file_get_contents( $img->src );

				file_put_contents( MDIR.'images/'.$fname, $ret );
				$movie->src = $img->src;
				$movie->srcset = $img->srcset;
			}

		}

		$all = [];
		foreach( $html->find('.ipc-metadata-list__item') as $v ) {

			$label = @$v->find(".ipc-metadata-list-item__label", 0)->innertext;
			if( @$label ) {
				foreach( $v->find("li") as $v1 ) {
					$link = $v1->find("a",0)?:$v1->find("span",0);
					$all[$label][] = [ trim($link->innertext), $link->href ];
				}

			} else {

			}
		}
		//print_r( $all );
		//exit(0);

		@$all['Star'] = @$all['Star']?:@$all['Stars'];
		$ids = [];	
		foreach( @$all['Star'] as $v ) {
			
			$actor = Actors::where(['name' => $v[0] ] )->findFirst();

			if( @count( $actor ) == 0 ) {
				$a = new Actors;
				$a->name = $v[0];
				$e = explode('/', $v[1]);
				$a->const = $e[2];
				//$a->imdbLink = $v[1];
				$id = $a->save();

				//$a->savePic();

			} else {
				$id = $actor->id;
			}
			$ids[] = $id;
		}

		$movie->tactors = implode(',', array_unique($ids) );

		@$all['Writer'] = @$all['Writer']?:@$all['Writers'];
		$ids = [];
		foreach( @$all['Writer'] as $v ) {
			$writer = Writers::where([ 'name' => $v[0] ] )->findFirst();
			if( @count( $writer ) == 0 ) {
				$a = new Writers;
				$a->name = $v[0];
				$e = explode('/', $v[1]);
				$a->const = $e[2];				
				//$a->imdbLink = $v[1];
				$id = $a->save();

			} else {
				$id = $writer->id;
			}

			$ids[] = $id;
		}

		$movie->twriters = implode(',', array_unique($ids) );

		@$all['Director'] = @$all['Director']?:@$all['Directors'];

		$ids = [];
		foreach( @$all['Director'] as $v ) {
			$director = Directors::where([ 'name' => $v[0]] )->findFirst();
			
			if( @count( $director ) == 0 ) {
				$a = new Directors;
				$a->name = $v[0];
				$e = explode('/', $v[1]);
				$a->const = $e[2];				
				//$a->imdbLink = $v[1];
				$id = $a->save();

				//$a->savePic();

			} else {
				$id = $director->id;
			}

			$ids[] = $id;
		}


		$movie->tdirectors = implode(',', array_unique($ids) );

		@$all['Genre'] = @$all['Genre']?:@$all['Genres'];
		$ids = [];
		foreach( @$all['Genre'] as $v ) {
			$genre = Genres::sql(" where title like ? " )->findFirst([ '%'.$v[0].'%' ]);
			
			if( @count( $genre ) == 0 ) {
				$a = new Genres;
				$a->title = $v[0];
				//$a->imdbLink = $v[1];
				$id = $a->save();


			} else {
				$id = $genre->id;
			}

			$ids[] = $id;
		}
		$movie->tgenres = implode(',', array_unique($ids) );

		@$all['Country of origin'] = @$all['Country of origin']?:@$all['Countries of origin'];
		$ids = [];
		foreach( @$all['Country of origin'] as $v ) {
			$country = Countries::where([ 'name' => $v[0]] )->findFirst();
			
			if( count( $country ) == 0 ) {
				$a = new Countries;
				$a->name = $v[0];
				//$a->imdbLink = $v[1];
				$a->short = $a->mshort;
				$id = $a->save();


			} else {
				$id = $country->id;
			}

			$ids[] = $id;
		}
		$movie->tcountries = implode(',', array_unique($ids) );


		@$all['Language'] = @$all['Language']?:@$all['Languages'];
		$ids = [];
		foreach( @$all['Language'] as $v ) {
			$lang = Languages::where([ 'name' => $v[0]] )->findFirst();
			
			if( count( $lang ) == 0 ) {
				$a = new Languages;
				$a->name = $v[0];
				//$a->imdbLink = $v[1];
				$a->short = $a->mshort;
				$id = $a->save();

			
			} else {
				$id = $lang->id;
			}

			$ids[] = $id;
		}
		$movie->tlanguages = implode(',', array_unique($ids) );
		//print_r( $movie );
		//exit(0);

		$movie->save();		
	}
}