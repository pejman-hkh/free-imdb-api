<?php
namespace App\Model;
class Countries extends \Peji\DB\Model {
	var $table = 'countries';

	function getMShort() {
		$str = ( htmlspecialchars_decode( explode("?", $this->imdbLink)[1] ) );
		parse_str( $str, $qs );
		$short = strtolower( $qs['country_of_origin'] );
		return $short;
	}
}