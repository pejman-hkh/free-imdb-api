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
				unset( $object->$k );
			}

			$this->removeType( $o );
		}
	}

	function getInfo1() {

		$ret = new \StdClass;

		$data = json_decode( $this->datan );
		$this->removeType( $data );
		
		$ret->aboveTheFoldData = $data->props->pageProps->aboveTheFoldData;
		$ret->mainColumnData = $data->props->pageProps->mainColumnData;

		return $ret;
	}


	function simplify( $a ) {
		$ret = [];
		foreach( $a as $v ) {
			$ret[] = (object)[ 'name' => $v->name->nameText->text, 'code' => $v->name->id ];
		}
		return $ret;
	}

	function simplifyImages( $a ) {
		$ret = [];
		foreach( $a as $v ) {
			$v->caption = $v->caption->plainText;
			$ret[] = $v->node;
		}
		return $ret;
	}

	function simplifyExternal( $a ) {
		$ret = [];
		foreach( $a as $v ) {
			$ret[] = $v->node;
		}
		return $ret;
	}

	function simplifyCasts( $a ) {
		$ret = [];
		foreach( $a as $v ) {
			$ret[] = (object)[ 'name' => $v->node->name->nameText->text, 'code' => $v->node->name->id, 'characters' => $v->node->characters[0]->name ];
		}
		return $ret;
	}

	function simplifyLocation( $a ) {
		$ret = [];
		foreach( $a as $v ) {
			$ret[] = (object)[ 'text' => $v->node->text ];
		}
		return $ret;
	}


	function simplifyProduction( $a ) {
		$ret = [];
		foreach( $a as $v ) {
			$ret[] = (object)[ 'id' => $v->node->company->id, 'text' => $v->node->company->companyText->text ];
		}
		return $ret;
	}

	function simplifyMoreLike( $a ) {
		$ret = [];
		foreach( $a as $v ) {
			$ret[] = (object)[ 'code' => $v->node->id, 'title' => $v->node->titleText->text ];
		}
		return $ret;
	}

	function getInfo2() {

		$info = $this->info1;

		$a = new \StdClass;
		$a->writers = $this->simplify($info->mainColumnData->writers[0]->credits);
		$a->directors = $this->simplify( $info->mainColumnData->directors[0]->credits );
		$a->originalTitle = $info->aboveTheFoldData->originalTitleText->text;
		$a->metacritic = $info->aboveTheFoldData->metacritic->metascore->score;
		$a->genres = $info->aboveTheFoldData->genres->genres;
		$a->certificate = $info->aboveTheFoldData->certificate->rating;
		$date = $info->aboveTheFoldData->releaseDate;
		$a->releaseDate = $date->year.'/'.$date->month.'/'.$date->day;
		$a->runtime = round( $info->aboveTheFoldData->runtime->seconds / 60 );
		$a->primaryImage = $info->aboveTheFoldData->primaryImage;
		$a->plot = $info->aboveTheFoldData->plot->plotText->plainText;
		$a->countries = $info->mainColumnData->countriesOfOrigin->countries;
		$a->wins = $info->mainColumnData->wins->total;
		$a->nominations = $info->mainColumnData->nominations->total;
		$a->images = $this->simplifyImages( $info->mainColumnData->titleMainImages->edges );
		$a->casts = $this->simplifyCasts( $info->mainColumnData->cast->edges );
		$a->languages = $info->mainColumnData->spokenLanguages->spokenLanguages;
		$a->filmingLocations = $this->simplifyLocation( $info->mainColumnData->filmingLocations->edges );
		$a->filmingLocations = $info->mainColumnData->filmingLocations->edges;
		$a->budget = $info->mainColumnData->productionBudget->budget;
		$a->lifetimeGross = $info->mainColumnData->lifetimeGross->total;
		$a->openingWeekendGross = $info->mainColumnData->openingWeekendGross->total;
		$a->worldwideGross = $info->mainColumnData->worldwideGross->total;
		$a->keywords = $this->simplifyLocation( $info->aboveTheFoldData->keywords->edges);
		$a->production = $this->simplifyProduction( $info->aboveTheFoldData->production->edges);
		$a->prestigiousAwardSummary = $info->mainColumnData->prestigiousAwardSummary;
		$a->moreLikeThisTitles = $this->simplifyMoreLike( $info->mainColumnData->moreLikeThisTitles->edges );
		$a->detailsExternalLinks = $this->simplifyExternal( $info->mainColumnData->detailsExternalLinks->edges );
		$a->akas = $this->simplifyExternal( $info->mainColumnData->akas->edges);
		$a->technicalSpecifications = $info->mainColumnData->technicalSpecifications->soundMixes->items;
		$a->aspectRatios = $info->mainColumnData->aspectRatios->items;
		$a->colorations = $info->mainColumnData->colorations->items;
		return $a;	
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

		if( ! $html ) return;

		$t = $html->find("#__NEXT_DATA__", 0);
		$movie->datan = $t->innertext;

		$info = $movie->info1;

		$a = $movie->info2;

		$ids = [];
		if( @count( $a->writers ) > 0 ) foreach( $a->writers as $v ) {
			$c = Writers::sql("where name = ?")->findFirst([ $v->name ]);
			if( ! @$c->id ) {
				$w = new Writers;
				$w->name = $v->name;
				$w->const = $v->code;
				$id = $w->save();
			} else {
				$id = $c->id;
			}
			$ids[] = $id;
		}
		$movie->twriters = implode(',', array_unique($ids) );
	
		$ids = [];
		if( @count( $a->directors ) > 0 ) foreach( $a->directors as $v ) {
			$c = Directors::sql("where name = ?")->findFirst([ $v->name ]);
			if( ! @$c->id ) {
				$w = new Directors;
				$w->name = $v->name;
				$w->const = $v->code;
				$id = $w->save();
			} else {
				$id = $c->id;
			}
			$ids[] = $id;
		}
		$movie->tdirectors = implode(',', array_unique($ids) );

		$ids = [];
		if( @count( $a->genres ) > 0 ) foreach( $a->genres as $v ) {
			$c = Genres::sql("where title = ?")->findFirst([ $v->text ]);
			if( ! @$c->id ) {
				$w = new Genres;
				$w->title = $v->text;
				$id = $w->save();
			} else {
				$id = $c->id;
			}
			$ids[] = $id;
		}
		$movie->tgenres = implode(',', array_unique($ids) );

		$ids = [];
		if( @count( $a->casts ) > 0 ) foreach( $a->casts as $v ) {
			$c = Actors::sql("where name = ?")->findFirst([ $v->text ]);
			if( ! @$c->id ) {
				$w = new Actors;
				$w->name = $v->name;
				$w->const = $v->code;
				$id = $w->save();
			} else {
				$id = $c->id;
			}
			$ids[] = $id;
		}
		$movie->tactors = implode(',', array_unique($ids) );


		$ids = [];
		if( @count( $a->countries ) > 0 ) foreach( $a->countries as $v ) {
			$c = Countries::sql("where short = ?")->findFirst([ strtolower($v->id) ]);
			if( ! @$c->id ) {
				$w = new Countries;
				$w->name = $v->text;
				$w->short = strtolower($v->id);
				$id = $w->save();
			} else {
				$id = $c->id;
			}
			$ids[] = $id;
		}
		$movie->tcountries = implode(',', array_unique($ids) );

		$ids = [];
		if( @count( $a->languages ) > 0 ) foreach( $a->languages as $v ) {
			$c = Languages::sql("where short = ?")->findFirst([ strtolower($v->id) ]);
			if( ! @$c->id ) {
				$w = new Languages;
				$w->name = $v->text;
				$w->short = strtolower($v->id);
				$id = $w->save();
			} else {
				$id = $c->id;
			}
			$ids[] = $id;
		}
		$movie->tlanguages = implode(',', array_unique($ids) );

		
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


		$movie->save();

	}
}