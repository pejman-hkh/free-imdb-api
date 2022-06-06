<?php
namespace App\Model;
class Movies extends \Peji\DB\Model {
	var $table = 'movies';

	function getInfo() {
		$ret = new \StdClass;
		foreach( json_decode($this->datas) as $k => $v ) {
			if( preg_match('#Nominated|Won#i', $k ) ) {
				$ret->awards = $k." ".$v[0][0];
			}

			if( preg_match('#Motion#i', $k ) ) {
				$ret->mpaa = $v[0][0];
			}

			if( preg_match('#Release#i', $k ) ) {
				$ret->releaseDate = $v[0][0];
			}

			if( preg_match('#sites#i', $k ) ) {
				$ret->sites = $v;
			}
			
			if( preg_match('#Production compan#i', $k ) ) {

				$cy = [];
				foreach( $v as $v1 ) {
					$e = explode("/", $v1[1] );
					$s = $e[2];
					$g = explode('?', $s);

					$cy[] = [ $v1[0], $g[0] ];
				}
				$ret->company = $cy;
			}

			if( preg_match('#Budget#i', $k ) ) {
				$ret->budget = $v[0][0];
			}

		}
		return $ret;
	}

	function getStoryLine1() {
		return preg_replace("#—(.*?)$#", "", $this->storyLine );
	}

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

	function request( $url ) {
	
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

		curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

		$headers = array();
		$headers[] = 'Authority: www.imdb.com';
		$headers[] = 'Pragma: no-cache';
		$headers[] = 'Cache-Control: no-cache';
		$headers[] = 'Sec-Ch-Ua: \" Not A;Brand\";v=\"99\", \"Chromium\";v=\"99\", \"Google Chrome\";v=\"99\"';
		$headers[] = 'Sec-Ch-Ua-Mobile: ?0';
		$headers[] = 'Sec-Ch-Ua-Platform: \"Linux\"';
		$headers[] = 'Upgrade-Insecure-Requests: 1';
		$headers[] = 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.84 Safari/537.36';
		$headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
		$headers[] = 'Sec-Fetch-Site: same-origin';
		$headers[] = 'Sec-Fetch-Mode: navigate';
		$headers[] = 'Sec-Fetch-User: ?1';
		$headers[] = 'Sec-Fetch-Dest: document';
		$headers[] = 'Accept-Language: en-US,en;q=0.9,fa;q=0.8';
	
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		if (curl_errno($ch)) {
		    echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);
		
		return $result;
	}


	function getRottentomatoes() {

	}

	var $again = 0;
	function update() {

		$movie = $this;

		$ret = $this->request( $this->imdbUrl );
		$html = str_get_html( $ret );

	
		if( ! $html ) return;
		preg_match('#<script type\="application/ld\+json">(.*?)</script>#', $ret, $m );
		$data = ( json_decode($m[1]) );


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
					$all[$label][] = [ trim(@$link->innertext), @$link->href ];
				}

			} else {

			}
		}

		$movie->datas = mjson_encode( $all );


		@$all['Star'] = @$all['Star']?:@$all['Stars'];
		$ids = [];	
		if( @count( $all['Star'] ) ) foreach( @$all['Star'] as $v ) {
			
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
		if( @count( $all['Writer'] ) ) foreach( @$all['Writer'] as $v ) {
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
		if( @count( $all['Director'] ) ) foreach( @$all['Director'] as $v ) {
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
		if( @count( $all['Genre'] ) )  foreach( @$all['Genre'] as $v ) {
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
		if( @count( $all['Country of origin'] ) ) foreach( @$all['Country of origin'] as $v ) {
			$country = Countries::where([ 'name' => $v[0]] )->findFirst();
			
			if( @count( $country ) == 0 ) {
				$a = new Countries;
				$a->name = $v[0];
				$a->imdbLink = $v[1];
				$a->short = $a->mshort;
				$id = $a->save();


			} else {

				$country->imdbLink = $v[1];
				$country->short = $country->mshort;
				$country->save();

				$id = $country->id;
			}

			$ids[] = $id;
		}
		$movie->tcountries = implode(',', array_unique($ids) );


		@$all['Language'] = @$all['Language']?:@$all['Languages'];
		$ids = [];
		if( @count( $all['Language'] ) ) foreach( @$all['Language'] as $v ) {
			$lang = Languages::where([ 'name' => $v[0]] )->findFirst();
			
			if( @count( $lang ) == 0 ) {
				$a = new Languages;
				$a->name = $v[0];
				$a->imdbLink = $v[1];
				$a->short = $a->mshort;
				$id = $a->save();

			
			} else {
				$lang->imdbLink = $v[1];
				$lang->short = $lang->mshort;
				$lang->save();

				$id = $lang->id;
			}

			$ids[] = $id;
		}
		$movie->tlanguages = implode(',', array_unique($ids) );
		if( $movie->tgenres || $this->again > 5 ) {
			$movie->save();		
		} else {
			echo "try again : ".$this->again." \n";
			$this->again++;
			$this->update();
		}

	}
}