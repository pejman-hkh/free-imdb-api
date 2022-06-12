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

	function removeType( &$object ) {
		foreach( $object as $k => $o ) {
			if( $k == '__typename' ) {
				unset( $object[$k] );
			}

			$this->removeType( $o );
		}
	}

	function getInfo1() {

		//$ret = new \StdClass;

		$data = json_decode( $this->datan );
		$this->removeType( $data );
		
		print_r( $data );

		//return $ret;
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

		$proxy = "138.201.113.9:3828";
		if( isLocal() ) $proxy = '';


		curl_setopt($ch, CURLOPT_PROXY, $proxy);
		curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

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
		$headers[] = 'Cookie: ubid-main=135-0830405-5912511; session-id=137-9679125-8436758; uu=eyJpZCI6InV1NmM3MDY5ODRjZDlkNGQzYTliYzkiLCJwcmVmZXJlbmNlcyI6eyJmaW5kX2luY2x1ZGVfYWR1bHQiOmZhbHNlfX0=; adblk=adblk_no; _uetvid=2ea728b0bfc111ec9e347f01afe47388; _gcl_au=1.1.856029712.1650359698; _clck=on0o5m|1|f0r|0; session-id-time=2082787201l; session-token=ROTcN+VoWuVh4amoic9Hs0OVeG43yZdyhl97r5RE+okAhzffvPl7fWI2WVzfPIergEWroM2LCHdbHysvcYspLLPhTpVgCO4H3beRO5wcu4imBD0UqHcANyjCSWlPFLPgQrgSsvQMN6AVG2G6lThkhYBuYLMJU4aptFCoN5ZslkW6BfbAk6WCUkEzbs9nMxODpYAvaW/xUek=; csm-hit=tb:KYTR9K47MMZ6K5GC7WZR+s-0S2S5GNJM77PFWF2ZS3C|1654975066978&t:1654975066978&adb:adblk_no';

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
/*
		$t = $html->find("#__NEXT_DATA__", 0);

		$data = json_decode( $t->innertext );
		print_r( $data->props->pageProps->aboveTheFoldData ); 

		exit();*/

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

		$t = $html->find("#__NEXT_DATA__", 0);

		$movie->datas = mjson_encode( $all );
		$movie->datan = $t->innertext;

/*		print_r( $all );
		exit();
*/
		@$all['Star'] = @$all['Star']?:@$all['Stars'];
		$ids = [];	
		if( @count( (array)$all['Star'] ) ) foreach( @$all['Star'] as $v ) {
			
			$actor = Actors::where(['name' => $v[0] ] )->findFirst();

			if( @count( (array)$actor ) == 0 ) {
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
		if( @count( (array)$all['Writer'] ) ) foreach( @$all['Writer'] as $v ) {
			$writer = Writers::where([ 'name' => $v[0] ] )->findFirst();
			if( @count( (array)$writer ) == 0 ) {
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
		if( @count( (array)$all['Director'] ) ) foreach( @$all['Director'] as $v ) {
			$director = Directors::where([ 'name' => $v[0]] )->findFirst();
			
			if( @count( (array)$director ) == 0 ) {
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
		if( @count( (array)$all['Genre'] ) )  foreach( @$all['Genre'] as $v ) {
			$genre = Genres::sql(" where title like ? " )->findFirst([ '%'.$v[0].'%' ]);
			
			if( @count( (array)$genre ) == 0 ) {
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
		if( @count( (array)$all['Country of origin'] ) ) foreach( @$all['Country of origin'] as $v ) {
			$country = Countries::where([ 'name' => $v[0]] )->findFirst();
			
			if( @count( (array)$country ) == 0 ) {
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
		if( @count( (array)$all['Language'] ) ) foreach( @$all['Language'] as $v ) {
			$lang = Languages::where([ 'name' => $v[0]] )->findFirst();
			
			if( @count( (array)$lang ) == 0 ) {
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
		/*if( $movie->tgenres || $this->again > 5 ) {*/
		$movie->save();		
	/*	} else {
			echo "try again : ".$this->again." \n";
			$this->again++;
			$this->update();
		}*/

	}
}